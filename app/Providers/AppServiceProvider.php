<?php
// app/Providers/AppServiceProvider.php
 
namespace App\Providers;
 
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
 
    public function boot(): void
    {
        // ── Rate limiting de login ────────────────────────────
        // 5 intentos por minuto por combinación email+IP.
        // Al superar el límite Laravel devuelve HTTP 429 automáticamente.
       RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('email') . '|' . $request->ip());
        });
 
        // ── Observer de User (Dev 1 registra aquí) ───────────
        // El Observer dispara AuditService automáticamente en
        // created / updated / deleted sin necesidad de llamarlo
        // manualmente en cada controlador.
        User::observe(UserObserver::class);
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/comision_permanente/public/livewire/update', $handle);
        });

        // 2. Ruta para el Javascript nativo
        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/comision_permanente/public/livewire/livewire.js', $handle);
        });

    }
}
