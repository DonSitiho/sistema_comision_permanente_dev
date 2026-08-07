<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ComunicadoCategoria extends Model
{
    protected $table = "f3cmu_categorias";
    protected $fillable = ["nombre", "slug", "color"];
    public function comunicados()
    {
        return $this->hasMany(Comunicado::class, "categoria_id");
    }
}