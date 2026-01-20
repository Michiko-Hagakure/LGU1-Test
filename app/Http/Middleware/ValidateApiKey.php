<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey) {
            return response()->json([
                'error' => 'API key is required',
                'message' => 'Please provide X-API-Key header'
            ], 401);
        }
        
        $validApiKey = config('services.energy_efficiency.api_key');
        
        if ($apiKey !== $validApiKey) {
            return response()->json([
                'error' => 'Invalid API key',
                'message' => 'The provided API key is not authorized'
            ], 403);
        }
        
        return $next($request);
    }
}
