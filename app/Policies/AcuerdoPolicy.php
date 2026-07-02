<?php

namespace App\Policies;

use App\Models\Acuerdo;
use App\Models\User;

class AcuerdoPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole("Administrador")) return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can("ver acuerdos");
    }

    public function view(User $user, Acuerdo $acuerdo): bool
    {
        if (!$user->can("ver acuerdos")) return false;
        if ($user->hasAnyRole(["Secretario Tecnico", "Auditor"])) return true;
        return $user->can("view", $acuerdo->sesion);
    }

    public function create(User $user): bool
    {
        return $user->can("crear acuerdos");
    }

    public function update(User $user, Acuerdo $acuerdo): bool
    {
        if (!$user->can("editar acuerdos")) return false;
        if ($user->hasRole("Secretario Tecnico")) return true;
        return $user->can("update", $acuerdo->sesion);
    }

    public function delete(User $user, Acuerdo $acuerdo): bool
    {
        return $user->can("cerrar acuerdos");
    }
}