<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function ok($data = null, int $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    protected function fail(string $message, int $status = 400, array $extra = [])
    {
        return response()->json([
            'success' => false,
            'error' => array_merge(['message' => $message], $extra),
        ], $status);
    }
}
