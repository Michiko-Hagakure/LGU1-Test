<?php

namespace App\Traits;

trait HasJsonEndpoint
{
    /**
     * Return JSON response for AJAX polling
     */
    protected function jsonResponse($data, $stats = [])
    {
        return response()->json([
            'data' => $data,
            'stats' => $stats,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
