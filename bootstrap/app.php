<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if ($request->is('admin*') || $request->is('*/admin/*')) {
                return back()->withErrors([
                    'files' => 'The uploaded file is too large. Please upload smaller files or increase upload_max_filesize / post_max_size in your php.ini.'
                ])->withInput();
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'The uploaded POST data is too large. Content-Length exceeds maximum limit.'
            ], 413);
        });
    })->create();
