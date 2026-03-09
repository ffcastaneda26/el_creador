<?php

namespace App\Support;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleNotifier
{
    public static function notify(array $roles, string $title, string $body): void
    {
        $normalizedTargetRoles = collect($roles)
            ->map(fn (string $role): string => Str::of($role)->ascii()->lower()->toString())
            ->unique();

        $matchingRoleNames = Role::query()
            ->pluck('name')
            ->filter(fn (string $roleName): bool => $normalizedTargetRoles->contains(
                Str::of($roleName)->ascii()->lower()->toString()
            ))
            ->unique()
            ->values();

        if ($matchingRoleNames->isEmpty()) {
            return;
        }

        $users = User::role($matchingRoleNames->all())->get();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            Notification::make()
                ->title($title)
                ->body($body)
                ->sendToDatabase($user);
        }
    }
}
