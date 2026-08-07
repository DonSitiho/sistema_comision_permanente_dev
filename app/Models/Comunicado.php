<?php
namespace App\Models;
use App\Models\Concerns\HasDocumentos;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
class Comunicado extends Model
{
    use HasDocumentos;
    protected $table = "f3cmu_comunicados";
    protected $fillable = [
        "categoria_id", "emitido_por", "titulo", "cuerpo", "obligatorio",
        "alcance", "criterio", "estado", "enviado_at",
    ];
    protected $casts = [
        "obligatorio" => "boolean",
        "criterio" => "array",
        "enviado_at" => "datetime",
    ];
    public function categoria()
    {
        return $this->belongsTo(ComunicadoCategoria::class, "categoria_id");
    }
    public function emisor()
    {
        return $this->belongsTo(User::class, "emitido_por");
    }
    public function destinatarios()
    {
        return $this->belongsToMany(User::class, "f3cmu_destinatarios")
            ->withPivot(["leido_at", "aceptado_at"])
            ->withTimestamps();
    }
    // Solo editable mientras esta en borrador; enviado/archivado son de solo lectura.
    public function editable(): bool
    {
        return $this->estado === "borrador";
    }
    // Comunicados dirigidos a este usuario (para "Mis comunicados")
    public function scopeParaUsuario(Builder $query, User $usuario): Builder
    {
        return $query->whereHas("destinatarios", fn ($d) => $d->where("users.id", $usuario->id));
    }
    // Obligatorios que este usuario aun no acepta (para el modal al iniciar sesion)
    public static function obligatoriosPendientes(User $usuario)
    {
        return static::where("obligatorio", true)
            ->where("estado", "enviado")
            ->whereHas("destinatarios", fn ($d) => $d->where("users.id", $usuario->id)->whereNull("aceptado_at"))
            ->get();
    }
    // Porcentaje de destinatarios que ya abrieron el comunicado
    public function porcentajeLectura(): int
    {
        $total = $this->destinatarios()->count();
        if ($total === 0) {
            return 0;
        }
        $leidos = $this->destinatarios()->wherePivotNotNull("leido_at")->count();
        return (int) round(($leidos / $total) * 100);
    }
}