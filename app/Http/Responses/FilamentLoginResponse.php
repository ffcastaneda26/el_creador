<?php

namespace App\Http\Responses;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Livewire\Features\SupportRedirects\Redirector;

class FilamentLoginResponse implements LoginResponseContract
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
            'super admin' => 'admin',
            'super_admin' => 'admin',
            'dueno ceo' => 'direccion',
            'direccion' => 'direccion',
            'administrador contador' => 'admin',
            'administrador' => 'admin',
            'director ventas' => 'gerente',
            'gerente ventas' => 'gerente',
            'gerente' => 'gerente',
            'asesor' => 'asesor',
            'vendedor' => 'vendedor',
            'capturista' => 'capturista',
            'director produccion' => 'produccion',
            'gerente produccion' => 'produccion',
            'operativo produccion' => 'produccion',
            'produccion' => 'produccion',
            'gerente cae' => 'almacen',
            'almacen' => 'almacen',
            'chofer entrega' => 'envios',
            'envios' => 'envios',
        ];

        foreach ($user->getRoleNames() as $roleName) {
            $normalizedRole = Str::of($roleName)->ascii()->lower()->toString();

            if (array_key_exists($normalizedRole, $roleToPanel)) {
                return $roleToPanel[$normalizedRole];
            }
        }

        return Filament::getDefaultPanel()->getId();
    }
}
