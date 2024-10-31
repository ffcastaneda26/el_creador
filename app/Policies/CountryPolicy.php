<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CountryPolicy
{    
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function update(User $user, Country $country): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }

    public function delete(User $user, Country $country): bool
    {
           return $user->isSuperAdmin() || $user->isAdministrador();
    }
}
