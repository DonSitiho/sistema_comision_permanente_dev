<?php
// app/Models/User.php
 
namespace App\Models;
 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
 
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
 
    protected $fillable = [
        'name',
        'email',
        'password',
        'cargo',
        'dependencia_id',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
        // 2FA — columnas preparadas, flujo activado en fase posterior
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];
 
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];
 
    protected $casts = [
        'email_verified_at'       => 'datetime',
        'last_login_at'           => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'is_active'               => 'boolean',
    ];
 
    // ── Relaciones ────────────────────────────────────────────
 
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }
 
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
 
    // ── Accessors ─────────────────────────────────────────────
 
    // Fallback a avatar generado con iniciales cuando no hay foto
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return 'https://ui-avatars.com/api/?name='
            . urlencode($this->name)
            . '&background=1F3864&color=fff&size=128';
    }
 
    // ── Helpers ──────────────────────────────────────────────
 
    // Encapsula la lógica de 2FA para no repetirla en vistas/middleware
    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_confirmed_at);
    }
}
