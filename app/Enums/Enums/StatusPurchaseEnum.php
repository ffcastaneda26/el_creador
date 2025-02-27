<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;


enum StatusPurchaseEnum: string implements HasLabel,HasColor,HasIcon
{
    case abierto = 'abierto';
    case autorizado = 'autorizado';
    case pendiente = 'pendiente';
    case surtido = 'surtido';
    case parcial = 'parcial';
    case cancelado = 'cancelado';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::abierto => 'Abierto',
            self::autorizado=> 'Autorizado',
            self::pendiente=> 'Pendiente',
            self::parcial=> 'Parcial',
            self::surtido=> 'Surtido',
            self::cancelado=> 'Cancelado',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::abierto => 'warning',
            self::autorizado=> 'primary',
            self::pendiente=> 'warning',

            self::parcial=> 'indigo',
            self::surtido=> 'success',
            self::cancelado=> 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::abierto => 'heroicon-m-bell-alert',
            self::autorizado=> 'heroicon-m-clipboard-document-check',
            self::pendiente=> 'heroicon-m-clock',

            self::parcial => 'heroicon-m-clipboard-document-list',
            self::surtido => 'heroicon-m-check-badge',
            self::cancelado => 'heroicon-m-x-circle',
        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::abierto => 'fa-regular fa-bell',
            self::autorizado=> 'fa-solid fa-clipboard-check',
            self::pendiente=> 'fa-solid fa-clock-o',
            self::parcial => 'fa-solid fa-file-circle-check',
            self::surtido => 'fa-solid fa-check-double',
            self::cancelado => 'fa-solid fa-ban',
        };

    }
}
