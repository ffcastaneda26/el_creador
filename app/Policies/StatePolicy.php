<?php

namespace App\Policies;

use App\Models\State;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatePolicy
{

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }

    public function update(User $user, State $state): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }
    public function delete(User $user, State $state): bool
    {
        return $user->isSuperAdmin() || $user->isAdministrador();
    }

}
