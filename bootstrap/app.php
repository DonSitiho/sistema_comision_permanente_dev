<?php
// bootstrap/app.php
 
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
 
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
 
        // Aplicar throttle:login a las rutas de autenticación.
        // "login" referencia el RateLimiter definido en AppServiceProvider.
       //$middleware->throttleWithRedis();
 
        $middleware->group('web', [
            // Los middlewares web de Laravel ya están aquí por defecto.
            // Solo agregamos el alias para usarlo en rutas.
        ]);
 
         // Alias para usar en rutas: ->middleware("throttle:login")
    $middleware->alias([
        'permission'         => \Spatie\LaravelPermission\Middleware\PermissionMiddleware::class,
        'role'               => \Spatie\LaravelPermission\Middleware\RoleMiddleware::class,
        'role_or_permission' => \Spatie\LaravelPermission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Personalizar respuesta 403 del sistema
        $exceptions->render(function (\Spatie\LaravelPermission\Exceptions\UnauthorizedException $e) {
            return response()->view('errors.403', [], 403);
        });
    })->create();
