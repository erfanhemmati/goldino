<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Register a new user
     * 
     * @param array $data User registration data
     * @return User The newly created user
     */
    public function register(array $data): User;
    
    /**
     * Authenticate a user
     * 
     * @param string $email User email
     * @param string $password User password
     * @return User|null Authenticated user or null if authentication fails
     */
    public function authenticate(string $email, string $password): ?User;
    
    /**
     * Log out a user
     * 
     * @param int $userId User ID
     * @return bool True if successful
     */
    public function logout(int $userId): bool;
    
    /**
     * Create an authentication token for a user
     * 
     * @param User $user The user
     * @return string The token
     */
    public function createToken(User $user): string;
} 