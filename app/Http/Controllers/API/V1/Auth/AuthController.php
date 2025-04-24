<?php

namespace App\Http\Controllers\API\V1\Auth;

use Illuminate\Http\JsonResponse;
use App\UseCases\Auth\LoginUseCase;
use App\Http\Controllers\Controller;
use App\UseCases\Auth\LogoutUseCase;
use App\UseCases\Auth\RegisterUseCase;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Requests\API\V1\Auth\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @param RegisterUseCase $useCase
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, RegisterUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute([
            'name'      => $request->validated('name'),
            'email'     => $request->validated('email'),
            'password'  => $request->validated('password'),
        ]);

        return $this->respondCreated($result, 'User registered successfully');
    }

    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @param LoginUseCase $useCase
     * @return JsonResponse
     */
    public function login(LoginRequest $request, LoginUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute([
            'email'     => $request->validated('email'),
            'password'  => $request->validated('password'),
        ]);

        if (! $result) {
            return $this->respondUnauthorized('Invalid login credentials');
        }

        return $this->respondSuccess($result, 'Login successful');
    }

    /**
     * Logout user (revoke token)
     *
     * @param LogoutUseCase $useCase
     * @return JsonResponse
     */
    public function logout(LogoutUseCase $useCase): JsonResponse
    {
        $useCase->execute([
            'user_id' => auth()->id()
        ]);

        return $this->respondSuccess(null, 'Logged out successfully');
    }
}
