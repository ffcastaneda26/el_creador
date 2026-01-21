<?php

namespace App\Support;

use App\Models\User;
use Filament\Notifications\Notification;

class RoleNotifier
{
    public static function notify(array $roles, string $title, string $body): void
    {
        $users = User::role($roles)->get();
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
