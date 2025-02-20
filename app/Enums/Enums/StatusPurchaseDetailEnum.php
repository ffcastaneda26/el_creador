<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;


enum StatusPurchaseDetailEnum: string implements HasLabel,HasColor,HasIcon
{
    case pendiente = 'pendiente';
    case parcial = 'parcial';
    case surtida = 'surtida';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::pendiente => 'Pendiente',
            self::parcial=> 'surtida Parcial',
            self::surtida=> 'Surtida',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::pendiente => 'warning',
            self::parcial=> 'indigo',
            self::surtida=> 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::pendiente => 'heroicon-m-bell-alert',
            self::parcial => 'heroicon-m-clipboard-document-list',
            self::surtida => 'heroicon-m-check-badge',
        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::pendiente => 'fa-regular fa-bell',
            self::parcial => 'fa-solid fa-file-circle-check',
            self::surtida => 'fa-solid fa-check-double',
        };

    }
}
