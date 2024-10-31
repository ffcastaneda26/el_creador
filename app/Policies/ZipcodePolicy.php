<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ZipCode;
use Illuminate\Auth\Access\Response;

class ZipcodePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function update(User $user, ZipCode $zipCode): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }

    public function delete(User $user, ZipCode $zipCode): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }

}
