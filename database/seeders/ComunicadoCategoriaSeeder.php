<?php

namespace Database\Seeders;

use App\Models\ComunicadoCategoria;
use Illuminate\Database\Seeder;

class ComunicadoCategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ["nombre" => "Informativo", "slug" => "informativo", "color" => "info"],
            ["nombre" => "Actividad", "slug" => "actividad", "color" => "danger"],
            ["nombre" => "Circular", "slug" => "circular", "color" => "primary"],
        ];

        foreach ($categorias as $categoria) {
            ComunicadoCategoria::firstOrCreate(["slug" => $categoria["slug"]], $categoria);
        }
    }
}