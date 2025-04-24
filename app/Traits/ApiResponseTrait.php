<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Return a success response with data
     *
     * @param mixed $data The data to return
     * @param string|null $message Optional message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    protected function respondSuccess($data = null, string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = ['success' => true];
        
        if ($message) {
            $response['message'] = __($message);
        }
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return response()->json($response, $statusCode);
    }
    
    /**
     * Return a created response with data
     *
     * @param mixed $data The data to return
     * @param string|null $message Optional message
     * @return JsonResponse
     */
    protected function respondCreated($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->respondSuccess($data, $message, 201);
    }
    
    /**
     * Return an error response
     *
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    protected function respondError(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => __($message)
        ], $statusCode);
    }
    
    /**
     * Return an unauthorized error response
     *
     * @param string $message Error message
     * @return JsonResponse
     */
    protected function respondUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->respondError($message, 401);
    }
    
    /**
     * Return a not found error response
     *
     * @param string $message Error message
     * @return JsonResponse
     */
    protected function respondNotFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->respondError($message, 404);
    }
    
    /**
     * Return a validation error response
     *
     * @param array $errors Validation errors
     * @param string $message Error message
     * @return JsonResponse
     */
    protected function respondValidationError(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }
} 