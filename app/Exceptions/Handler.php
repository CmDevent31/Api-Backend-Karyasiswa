<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException && $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Expired Token or Unauthorized Token',
            ], 401);
        }
    
        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found',
                'data' => (object)[],
            ], 404);
        }
    
        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.',
                'data' => [],
            ], 404);
        }
    
        if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Method Not Allowed.',
                'data' => (object)[],
            ], 405);
        }
    
        if ($exception instanceof QueryException && $exception->errorInfo[1] === 1062) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate Data',
                'data' => (object)[],
            ], 422);
        }
    
        return parent::render($request, $exception);
    
        // Jika sampai di sini, artinya terjadi kesalahan internal server
        return response()->json([
            'success' => false,
            'message' => 'Internal Server Error',
            'data' => [],
        ], 500);
    }
    
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}