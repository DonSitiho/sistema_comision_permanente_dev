<?php
// app/Models/Region.php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Region extends Model
{
    use HasFactory;
 
    protected $table = 'regiones';
 
    protected $fillable = ['nombre', 'descripcion'];
 
    // Una región tiene muchas dependencias
    public function dependencias()
    {
        return $this->hasMany(Dependencia::class);
    }
}
