<?php
//app/Models/Concerns/HasDocumentos.php
namespace App\Models\Concerns;

use App\Models\Documento;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasDocumentos
{
    public function documentos(): MorphMany
    {
        return $this->morphMany(Documento::class, "documentable");
    }
}