<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ComunicadoPermisosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = ["ver comunicados", "crear comunicados", "editar comunicados"];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(["name" => $permiso, "guard_name" => "web"]);
        }

        // "ver" para todos los roles, igual que "ver acuerdos"
        foreach (Role::all() as $role) {
            $role->givePermissionTo("ver comunicados");
        }

        // "crear"/"editar" solo Administrador y Secretario Tecnico
        foreach (["Administrador", "Secretario Técnico"] as $nombreRol) {
            $role = Role::where("name", $nombreRol)->first();
            $role?->givePermissionTo(["crear comunicados", "editar comunicados"]);
        }
    }
}