<?php

namespace App\Services;

use App\Enums\OrderType;
use App\Services\Interfaces\FeeCalculatorServiceInterface;

class FeeCalculatorService implements FeeCalculatorServiceInterface
{
    /**
     * Fee percentage tiers configuration.
     * @var array<int, array{up_to: ?float, percentage: float}>
     */
    protected array $tiers;

    /**
     * Minimum fee bound (in smallest currency unit).
     * @var float
     */
    protected float $min;

    /**
     * Maximum fee bound (in smallest currency unit).
     * @var float
     */
    protected float $max;

    public function __construct()
    {
        $config         = config('fees');
        $this->tiers    = $config['tiers'] ?? [];
        $this->min      = (float) ($config['min'] ?? 0);
        $this->max      = (float) ($config['max'] ?? 0);
    }

    /**
     * Calculate the fee for a trade given amount, price, and order type.
     *
     * {@inheritdoc}
     */
    public function calculateFee(float $amount, float $price, string $type): float
    {
        $percentage = 0.0;

        foreach ($this->tiers as $tier) {
            if (is_null($tier['up_to']) || $amount <= $tier['up_to']) {
                $percentage = $tier['percentage'];
                break;
            }
        }

        // Buy orders: fee based on amount only
        if ($type === OrderType::BUY->value) {
            return $amount * ($percentage / 100);
        }

        // Sell orders: fee based on trade total, then enforce bounds
        $rawFee = $amount * $price * ($percentage / 100);
        $fee    = round($rawFee);

        if ($fee < $this->min) {
            return $this->min;
        }

        if ($fee > $this->max) {
            return $this->max;
        }

        return $fee;
    }
}
