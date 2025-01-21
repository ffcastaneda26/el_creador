<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusWarehouseRequestEnum: string implements HasLabel,HasColor,HasIcon
{

    case abierto = 'abierto';
    case autorizado = 'autorizado';
    case surtido = 'surtido';
    case parcial = 'parcial';
    case cancelado = 'cancelado';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::abierto => 'Abierto',
            self::autorizado=> 'Autorizado',
            self::parcial=> 'Surtido Parcial',
            self::surtido=> 'Surtido',
            self::cancelado=> 'Cancelado',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::abierto => 'warning',
            self::autorizado=> 'primary',
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
            self::parcial => 'heroicon-m-clipboard-document-list',
            self::surtido => 'heroicon-m-check-badge',
            self::cancelado => 'heroicon-m-x-circle',
        };
    }
}
