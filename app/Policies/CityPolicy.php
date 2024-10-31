<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CityPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function update(User $user, City $city): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function delete(User $user, City $city): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }

}
