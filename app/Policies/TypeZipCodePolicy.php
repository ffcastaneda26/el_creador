<?php

namespace App\Policies;

use App\Models\TypeZipcode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TypeZipCodePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function update(User $user, TypeZipcode $typeZipcode): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }

    public function delete(User $user, TypeZipcode $typeZipcode): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
 
}
