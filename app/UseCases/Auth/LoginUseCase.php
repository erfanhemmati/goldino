<?php

namespace App\UseCases\Auth;

use App\UseCases\BaseUseCase;
use App\Services\Interfaces\AuthServiceInterface;

class LoginUseCase extends BaseUseCase
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
     * Authenticate a user
     *
     * @param array $data Login data including email and password
     * @return array|null User and token data or null if authentication fails
     */
    public function execute(array $data): ?array
    {
        $user = $this->authService->authenticate(
            $data['email'],
            $data['password']
        );

        if (! $user) {
            return null;
        }

        $token = $this->authService->createToken($user);

        return [
            'user'      => $user,
            'token'     => $token
        ];
    }
}
