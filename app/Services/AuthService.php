<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Interfaces\AuthServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    /**
     * @var UserRepositoryInterface     $userRepository
     */
    protected UserRepositoryInterface   $userRepository;

    /**
     * @param UserRepositoryInterface   $userRepository
     */
    public function __construct
    (
        UserRepositoryInterface         $userRepository
    )
    {
        $this->userRepository         = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function register(array $data): User
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
        ]);

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(string $email, string $password): ?User
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return $this->userRepository->findByEmail($email);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function logout(int $userId): bool
    {
        $user = $this->userRepository->findById($userId);

        if (! $user) {
            return false;
        }

        $user->tokens()->delete();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function createToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
