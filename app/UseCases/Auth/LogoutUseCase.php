<?php

namespace App\UseCases\Auth;

use App\UseCases\BaseUseCase;
use App\Services\Interfaces\AuthServiceInterface;

class LogoutUseCase extends BaseUseCase
{
    /**
     * @var AuthServiceInterface    $authService
     */
    private AuthServiceInterface    $authService;

    /**
     * @param AuthServiceInterface  $authService
     */
    public function __construct
    (
        AuthServiceInterface        $authService
    )
    {
        $this->authService        = $authService;
    }

    /**
     * Log out a user
     *
     * @param array $data User data with user_id
     * @return bool True if successful
     */
    public function execute(array $data): bool
    {
        return $this->authService->logout($data['user_id']);
    }
}
