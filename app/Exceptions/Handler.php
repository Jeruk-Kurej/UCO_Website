<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Throwable;

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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle file upload errors
        if ($e instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
            return back()->withErrors([
                'file' => 'The uploaded file is too large. Maximum allowed size is ' . ini_get('upload_max_filesize') . '.'
            ])->withInput();
        }

        // Handle file exceptions
        if ($e instanceof FileException) {
            return back()->withErrors([
                'file' => 'There was an error uploading the file. Please try again.'
            ])->withInput();
        }

        // Handle 404 errors
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }
            return response()->view('errors.404', [], 404);
        }

        // Handle 403 errors
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }
            return back()->withErrors([
                'authorization' => 'You do not have permission to perform this action.'
            ]);
        }

        return parent::render($request, $e);
    }
}
