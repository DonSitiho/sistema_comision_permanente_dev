<?php

namespace App\Services;

use App\Models\Acuerdo;
use Illuminate\Support\Facades\DB;

class AcuerdoFolioService
{
    public static function generar(): string
    {
        return DB::transaction(function () {
            $anio = now()->year;

            $ultimo = Acuerdo::where("folio", "like", "CPC-ACU-{$anio}-%")
                ->lockForUpdate()
                ->orderByDesc("id")
                ->first();

            $consecutivo = $ultimo
                ? ((int) substr($ultimo->folio, -4)) + 1
                : 1;

            return sprintf("CPC-ACU-%d-%04d", $anio, $consecutivo);
        });
    }
}