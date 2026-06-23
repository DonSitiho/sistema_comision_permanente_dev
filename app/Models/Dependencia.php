<?php
// app/Models/Dependencia.php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
 
class Dependencia extends Model
{
    use HasFactory;
 
    protected $table = 'dependencias';
 
    protected $fillable = [
        'nombre',
        'clave',
        'tipo',
        'region_id',
        'activo',
    ];
 
    protected $casts = [
        // boolean permite usar $dep->activo como true/false directamente
        'activo' => 'boolean',
    ];
 
    // ── Relaciones ────────────────────────────────────────────
 
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
 
    public function users()
    {
        return $this->hasMany(User::class);
    }
 
    // ── Scopes ───────────────────────────────────────────────
 
    // Uso: Dependencia::activas()->orderBy("nombre")->get()
    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
 
    // ── Helpers ──────────────────────────────────────────────
 
    // Etiqueta legible del tipo para mostrar en vistas
    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            'estatal'   => 'Dependencia Estatal',
            'municipal' => 'OIC Municipal',
            'oic'       => 'Órgano Interno de Control',
            default     => $this->tipo,
        };
    }
}
