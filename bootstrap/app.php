<?php

use App\Http\Middleware\AddSecurityHeaders;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Support\Env;

// Only load .env if APP_ENV isn't already set
if (getenv('APP_ENV') !== 'testing') {
    // Step 1: Load the main .env file
    $dotenv = Dotenv\Dotenv::createMutable(dirname(__DIR__));
    $dotenv->load();

    // Step 2: Only load additional .env file if APP_DEVELOPMENT_ENV is set
    $developmentEnv = Env::get('APP_DEVELOPMENT_ENV');

    if ($developmentEnv) {
        $additionalEnvFile = ".env.{$developmentEnv}";
        $additionalEnvPath = dirname(__DIR__) . "/{$additionalEnvFile}";

        if (file_exists($additionalEnvPath)) {
            $dotenv = Dotenv\Dotenv::createMutable(dirname(__DIR__), $additionalEnvFile);
            $dotenv->load();
        }
    }
}



// Step 3: Configure the application
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Add AddSecurityHeaders middleware only in production mode
        if (Env::get('APP_ENV') === 'production') {
            $middleware->web(append: [
                AddSecurityHeaders::class,
            ]);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

return $app;
