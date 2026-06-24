<?php
// database/seeders/ScpcSeeder.php
 
namespace Database\Seeders;
 
use App\Models\Dependencia;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
 
class ScpcSeeder extends Seeder
{
    public function run(): void
    {
        // ── PASO 1: Limpiar caché de Spatie ──────────────────────────────
        // Obligatorio antes de crear/modificar roles y permisos.
        // Sin esto Spatie puede usar datos viejos del caché.
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();
 
        // ── PASO 2: Eliminar roles genéricos de la plantilla ──────────────
        // La plantilla viene con: administrator, developer, analyst, support, trial.
        // Los reemplazamos con los 5 roles institucionales del SCPC.
        $rolesGenericos = ['administrator', 'developer', 'analyst', 'support', 'trial'];
        Role::whereIn('name', $rolesGenericos)->delete();
 
        // ── PASO 3: Crear permisos por módulo ─────────────────────────────
        // Se crean ahora aunque los módulos M1-M3 no estén desarrollados aún.
        // Esto permite que el RBAC esté listo cuando lleguen esas fases.
        $permisos = [
            // M4 — Gestión de Usuarios
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'desactivar usuarios',
            // Roles y permisos
            'ver roles', 'crear roles', 'editar roles', 'eliminar roles',
            // Catálogos (dependencias, regiones)
            'ver dependencias', 'crear dependencias', 'editar dependencias',
            // M1 — Acuerdos (Fase 1)
            'ver acuerdos', 'crear acuerdos', 'editar acuerdos', 'cerrar acuerdos',
            // M1 — Compromisos (Fase 1)
            'ver compromisos', 'crear compromisos', 'editar compromisos',
            // M2 — Sesiones (Fase 1)
            'ver sesiones', 'crear sesiones', 'editar sesiones',
            // Auditoría
            'ver bitacora',
        ];
 
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name'       => $permiso,
                'guard_name' => 'web',
            ]);
        }
 
        // ── PASO 4: Crear roles y asignar permisos ────────────────────────
        $rolesPermisos = [
 
            // Acceso total — solo el administrador del sistema
            'Administrador' => Permission::all()->pluck('name')->toArray(),
 
            // Coordina la comisión: puede ver todo, gestiona sesiones y acuerdos
            'Secretario Técnico' => [
                'ver usuarios', 'ver dependencias',
                'ver acuerdos', 'crear acuerdos', 'editar acuerdos', 'cerrar acuerdos',
                'ver compromisos', 'crear compromisos', 'editar compromisos',
                'ver sesiones', 'crear sesiones', 'editar sesiones',
            ],
 
            // OIC municipal: ve y gestiona dentro de su dependencia
            'Enlace / OIC' => [
                'ver acuerdos',
                'ver compromisos', 'crear compromisos', 'editar compromisos',
                'ver sesiones',
            ],
 
            // Servidor público: solo sus tareas asignadas
            'Operativo' => [
                'ver acuerdos',
                'ver compromisos', 'editar compromisos',
                'ver sesiones',
            ],
 
            // Solo lectura para control y revisión
            'Auditor' => [
                'ver acuerdos', 'ver compromisos', 'ver sesiones',
                'ver usuarios', 'ver dependencias', 'ver bitacora',
            ],
        ];
 
        foreach ($rolesPermisos as $nombreRol => $permisosDelRol) {
            $rol = Role::firstOrCreate([
                'name'       => $nombreRol,
                'guard_name' => 'web',
            ]);
            $rol->syncPermissions($permisosDelRol);
        }
 
        // ── PASO 5: Regiones de Michoacán ─────────────────────────────────
        $regiones = [
            ['nombre' => 'Región Centro',    'descripcion' => 'Morelia y municipios aledaños'],
            ['nombre' => 'Región Ciénega',   'descripcion' => 'Jiquilpan, Sahuayo y zona Ciénega'],
            ['nombre' => 'Región Oriente',   'descripcion' => 'Zitácuaro, Ciudad Hidalgo'],
            ['nombre' => 'Región Norte',     'descripcion' => 'La Piedad, Puruarán'],
            ['nombre' => 'Región Occidente', 'descripcion' => 'Uruapan, Apatzingán'],
            ['nombre' => 'Región Sur',       'descripcion' => 'Lázaro Cárdenas, Coahuayana'],
        ];
 
        foreach ($regiones as $datos) {
            Region::firstOrCreate(['nombre' => $datos['nombre']], $datos);
        }
 
        // ── PASO 6: Dependencia raíz ──────────────────────────────────────
        $depRaiz = Dependencia::firstOrCreate(
            ['clave' => 'SECOE'],
            [
                'nombre' => 'Secretaría de Contraloría del Estado de Michoacán',
                'tipo'   => 'estatal',
                'activo' => true,
            ]
        );
 
        // ── PASO 7: Usuario administrador inicial ─────────────────────────
        // La contraseña es temporal. El administrador DEBE cambiarla
        // en el primer acceso al sistema.
        $admin = User::firstOrCreate(
            ['email' => 'admin@contraloria.michoacan.gob.mx'],
            [
                'name'              => 'Administrador del Sistema',
                'password'          => Hash::make('Cambiar#2024!'),
                'cargo'             => 'Administrador del Sistema SCPC',
                'dependencia_id'    => $depRaiz->id,
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
 
        $admin->syncRoles(['Administrador']);
 
        $this->command->info('✓ ScpcSeeder completado.');
        $this->command->info('  Roles creados: ' . Role::count());
        $this->command->info('  Permisos creados: ' . Permission::count());
        $this->command->info('  Regiones: ' . Region::count());
        $this->command->info('  Admin: admin@contraloria.michoacan.gob.mx');
        $this->command->warn('  ⚠ Cambiar la contraseña del admin en el primer acceso.');
    }
}