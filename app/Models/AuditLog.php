<?php
// app/Models/AuditLog.php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class AuditLog extends Model
{
    // Tabla append-only: existe created_at pero NO updated_at.
    // Esta constante le dice a Eloquent que no busque updated_at.
    public const UPDATED_AT = null;
 
    protected $table = 'audit_logs';
 
    protected $fillable = [
        'user_id',
        'accion',
        'entidad',
        'entidad_id',
        'valores',
        'ip',
        'user_agent',
    ];
 
    protected $casts = [
        // Eloquent convierte JSON → array automáticamente al leer
        'valores'    => 'array',
        'created_at' => 'datetime',
    ];
 
    // ── Relaciones ────────────────────────────────────────────
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    // ── Scopes útiles para la vista de bitácora ───────────────
 
    public function scopeDeUsuario($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
 
    public function scopeDeAccion($query, string $accion)
    {
        return $query->where('accion', $accion);
    }
 
    public function scopeEntreFechas($query, string $desde, string $hasta)
    {
        return $query->whereBetween('created_at', [$desde, $hasta]);
    }
}
