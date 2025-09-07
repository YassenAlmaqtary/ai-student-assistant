<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function ok($data = null, int $status = 200)
    {
        return response()->json(['success' => true, 'data' => $data], $status);
    }

    protected function error(string $message, int $status = 400, $meta = null)
    {
        return response()->json(['success' => false, 'error' => ['message' => $message, 'meta' => $meta]], $status);
    }
}
