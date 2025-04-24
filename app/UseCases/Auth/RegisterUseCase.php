<?php

namespace App\UseCases\Auth;

use App\UseCases\BaseUseCase;
use App\Services\Interfaces\AuthServiceInterface;

class RegisterUseCase extends BaseUseCase
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
     * Register a new user
     *
     * @param array $data Registration data including name, email, password
     * @return array User and token data
     */
    public function execute(array $data): array
    {
        $user   = $this->authService->register([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
        ]);

        $token  = $this->authService->createToken($user);

        return [
            'user'      => $user,
            'token'     => $token
        ];
    }
}
