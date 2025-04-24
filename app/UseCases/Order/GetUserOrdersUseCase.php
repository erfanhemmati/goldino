<?php

namespace App\UseCases\Order;

use App\UseCases\BaseUseCase;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Interfaces\OrderServiceInterface;

class GetUserOrdersUseCase extends BaseUseCase
{
    /**
     * @var OrderServiceInterface   $orderService
     */
    private OrderServiceInterface   $orderService;

    /**
     * @param OrderServiceInterface $orderService
     */
    public function __construct
    (
        OrderServiceInterface       $orderService
    )
    {
        $this->orderService       = $orderService;
    }

    /**
     * Get orders for a user
     *
     * @param array $data Must contain 'user_id' key, may contain 'per_page'
     * @return LengthAwarePaginator Paginated orders
     */
    public function execute(array $data): LengthAwarePaginator
    {
        $perPage = $data['per_page'] ?? 15;

        return $this->orderService->getUserOrders(
            $data['user_id'],
            [],
            $perPage
        );
    }
}
