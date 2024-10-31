<?php

namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
       return true;
    }
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }
    public function update(User $user, Role $role): bool
    {
        return $user->isSuperAdmin();
    }
    public function delete(User $user, Role $role): bool
    {
        return $user->isSuperAdmin();
    }
}
