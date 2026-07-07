<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Bitacora\BitacoraIndex;  // ← agregar
use Illuminate\Support\Facades\Route;
use App\Livewire\Sesiones\SesionModal;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    // Bitácora — solo Administrador y Auditor          ← agregar
    Route::middleware(['permission:ver bitacora'])
        ->get('/admin/bitacora', BitacoraIndex::class)
        ->name('admin.bitacora');

    //Crear Convocatoria
    Route::get('/convocatorias', function () {
        return view('pages.crearConvocatoria');})
        ->middleware(['auth'])->name('crear-convocatoria'); 
        
    //Mis Convocatorias
    Route::get('/mis-convocatorias', function () {
        return view('pages.misConvocatorias');})
        ->middleware(['auth'])->name('mis-convocatorias');

});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';