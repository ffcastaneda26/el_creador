<?php

namespace App\Http\Responses;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Livewire\Features\SupportRedirects\Redirector;

class FortifyLoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        /** @var User|null $user */
        $user = $request->user();
        $panelId = $this->resolvePanelId($user);
        $panel = Filament::getPanel($panelId, isStrict: false) ?? Filament::getDefaultPanel();

        return redirect()->to($panel->getUrl());
    }

    private function resolvePanelId(?User $user): string
    {
        if (! $user) {
            return Filament::getDefaultPanel()->getId();
        }

        $roleToPanel = [
            'Super Admin' => 'admin',
            'Administrador' => 'admin',
            'Direccion' => 'direccion',
            'Dirección' => 'direccion',
            'Gerente' => 'gerente',
            'Produccion' => 'produccion',
            'Producción' => 'produccion',
            'Envios' => 'envios',
            'Envíos' => 'envios',
            'Almacen' => 'almacen',
            'Almacén' => 'almacen',
            'Asesor' => 'asesor',
            'Vendedor' => 'vendedor',
            'Capturista' => 'capturista',
        ];

        foreach ($roleToPanel as $role => $panelId) {
            if ($user->hasRole($role)) {
                return $panelId;
            }
        }

        return Filament::getDefaultPanel()->getId();
    }
}
