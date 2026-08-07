<?php
namespace App\Http\Middleware;
use App\Models\Comunicado;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class ComunicadosObligatorios
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($usuario = $request->user()) {
            $pendientes = Comunicado::where("obligatorio", true)
                ->where("estado", "enviado")
                ->whereHas("destinatarios", fn ($q) => $q->where("users.id", $usuario->id)->whereNull("aceptado_at"))
                ->exists();

            view()->share("hayObligatoriosPendientes", $pendientes);
        }

        return $next($request);
    }
}