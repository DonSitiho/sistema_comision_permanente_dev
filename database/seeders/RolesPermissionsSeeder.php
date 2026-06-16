<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definir Habilidades (Abilities)
        $abilities = [
            'ver',      // Read/View
            'crear',    // Create/Insert
            'editar',   // Update/Modify
            'eliminar', // Delete
        ];

        // 2. Definir Módulos (Permissions Groups)
        // Módulos que el Administrador podrá gestionar completamente.
        $modules = [
            'gestion de usuarios',      // Para Administrador (Control total)
            'gestion de registros',     // Datos primarios del sistema
            'gestion de catalogos',     // Listas de selección
            'reportes y consultas',     // Visualización de datos
            'configuracion del sistema',// Para Administrador
        ];

        // 3. Definir Permisos Específicos por Rol
        $permissions_by_role = [
            'administrador' => $modules, // Acceso total a todos los módulos
            
            'capturista' => [ // Enfocado en la entrada de datos (crear/editar)
                'gestion de registros',
                'gestion de catalogos',
            ],
            
            'usuario general' => [ // Solo consulta
                'gestion de registros', 
                'reportes y consultas', 
            ],
        ];

        // --- Lógica para la creación de Permisos (Todos) ---

        // Crear TODOS los permisos en la base de datos basándose en los módulos del Administrador.
        foreach ($modules as $permission) {
            foreach ($abilities as $ability) {
                // Usamos firstOrCreate para evitar errores si se ejecuta dos veces
                Permission::firstOrCreate(['name' => $ability . ' ' . $permission]);
            }
        }
        
        // --- Lógica para la asignación de Roles y Permisos ---

        foreach ($permissions_by_role as $role_name => $permissions) {
            $full_permissions_list = [];
            
            foreach ($permissions as $permission_module) {
                // Asignación de habilidades específicas a los roles
                foreach ($abilities as $ability) {
                    
                    if ($role_name === 'administrador') {
                        // El administrador obtiene todas las 4 habilidades en todos los módulos
                        $full_permissions_list[] = $ability . ' ' . $permission_module;
                    
                    } elseif ($role_name === 'capturista') {
                        // El Capturista solo puede 'crear' y 'editar' en sus módulos, y 'ver'
                        if (in_array($ability, ['ver', 'crear', 'editar'])) {
                            $full_permissions_list[] = $ability . ' ' . $permission_module;
                        }
                    
                    } elseif ($role_name === 'usuario general') {
                        // El Usuario General solo puede 'ver' en sus módulos
                        if ($ability === 'ver') {
                            $full_permissions_list[] = $ability . ' ' . $permission_module;
                        }
                    }
                }
            }

            // Crear el Rol y sincronizar sus permisos
            Role::firstOrCreate(['name' => $role_name])->syncPermissions($full_permissions_list);
        }

        // --- Asignación de Roles a Usuarios de Ejemplo ---
        
        if (User::count() > 0) {
            // Asegura que el usuario con ID 1 sea el Administrador
            User::find(1)?->assignRole('administrador');
            // Asigna un rol de ejemplo al usuario 2
            User::find(2)?->assignRole('capturista');
            // Asigna un rol de ejemplo al usuario 3
            User::find(3)?->assignRole('usuario general');
        }
    }
}