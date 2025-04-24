<?php

namespace App\Console\Commands;

use App\Services\Interfaces\MatchingServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessOrderMatchingQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-matching-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process orders in the matching queue';

    /**
     * The matching service.
     *
     * @var MatchingServiceInterface
     */
    protected $matchingService;

    /**
     * Create a new command instance.
     */
    public function __construct(MatchingServiceInterface $matchingService)
    {
        parent::__construct();
        $this->matchingService = $matchingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting order matching queue processor...');
        
        // Run indefinitely until stopped
        while (true) {
            // Get an order ID from the queue with a timeout of 1 second
            $orderId = Redis::blpop('order_matching_queue', 1);
            
            if ($orderId) {
                // blpop returns [key, value], so we need to get the second element
                $orderId = $orderId[1];
                $this->info("Processing order ID: {$orderId}");
                
                try {
                    // Process the order
                    $this->matchingService->processOrder((int) $orderId);
                    $this->info("Processed order ID: {$orderId}");
                } catch (\Exception $e) {
                    $this->error("Error processing order ID {$orderId}: " . $e->getMessage());
                    Log::error("Error processing order ID {$orderId}: " . $e->getMessage(), [
                        'exception' => $e,
                    ]);
                }
            }
            
            // Small sleep to prevent CPU thrashing if the queue is empty
            usleep(100000); // 100ms
        }
        
        return 0;
    }
} 