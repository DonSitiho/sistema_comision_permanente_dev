<?php
//app/Models/Concerns/HasDocumentos.php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasDocumentos extends Model
{
    public function documentos() 
    { 
        return $this->morphMany(Documento::class, "documentable"); 
    } 
}
