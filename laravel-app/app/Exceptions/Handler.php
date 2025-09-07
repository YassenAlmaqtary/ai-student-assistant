<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->apiErrorResponse($e);
        }
        return parent::render($request, $e);
    }

    protected function apiErrorResponse(Throwable $e): JsonResponse
    {
        $status = 500;
        $error = [
            'type' => class_basename($e),
            'message' => config('app.debug') ? $e->getMessage() : $this->friendlyMessage($e),
        ];

        if ($e instanceof ValidationException) {
            $status = 422;
            $error['validation'] = $e->errors();
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
        } elseif ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
        }

        if (config('app.debug')) {
            $error['trace_id'] = spl_object_hash($e);
        }

        return response()->json([
            'success' => false,
            'error' => $error,
        ], $status);
    }

    protected function friendlyMessage(Throwable $e): string
    {
        return match(true) {
            $e instanceof ValidationException => 'بيانات غير صحيحة',
            $e instanceof AuthenticationException => 'غير مصدق',
            default => 'حدث خطأ غير متوقع',
        };
    }
}
