<?php
// bootstrap/app.php — versión completa con listeners y middlewares
 
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
        // Alias para middlewares de Spatie Permission
        $middleware->alias([
            'permission'        => \Spatie\LaravelPermission\Middleware\PermissionMiddleware::class,
            'role'              => \Spatie\LaravelPermission\Middleware\RoleMiddleware::class,
            'role_or_permission'=> \Spatie\LaravelPermission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withEvents()
   /* ->withEvents(function ($events) {
        // Mapeo explícito: evento → listener(s)
        // Cada evento puede tener múltiples listeners en el array.
        $events->listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LogSuccessfulLogin::class
        );
        $events->listen(
            \Illuminate\Auth\Events\Failed::class,
            \App\Listeners\LogFailedLogin::class
        );
        $events->listen(
            \Illuminate\Auth\Events\Logout::class,
            \App\Listeners\LogLogout::class
        );
    })*/
    ->withExceptions(function (Exceptions $exceptions) {
        // Respuesta 403 personalizada para permisos de Spatie
        $exceptions->render(function (\Spatie\LaravelPermission\Exceptions\UnauthorizedException $e) {
            return response()->view('errors.403', [], 403);
        });
    })->create();