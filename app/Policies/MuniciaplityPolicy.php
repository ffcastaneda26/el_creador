<?php

namespace App\Policies;

use App\Models\Municipality;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MuniciaplityPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function update(User $user, Municipality $municipality): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function delete(User $user, Municipality $municipality): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
}
