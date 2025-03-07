<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;


enum StatusReceiptEnum: string implements HasLabel,HasColor,HasIcon
{
    case abierto = 'abierto';
    case autorizado = 'autorizado';
    case terminado = 'terminado';
    case cancelado = 'cancelado';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::abierto => 'Abierto',
            self::autorizado=> 'Autorizado',
            self::terminado=> 'Terminado',
            self::cancelado=> 'Cancelado',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::abierto => 'warning',
            self::autorizado=> 'primary',
            self::terminado=> 'success',
            self::cancelado=> 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::abierto => 'heroicon-m-bell-alert',
            self::autorizado=> 'heroicon-m-clipboard-document-check',
            self::terminado=> 'heroicon-m-check',
            self::cancelado => 'heroicon-m-x-circle',
        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::abierto => 'fa-regular fa-bell',
            self::autorizado=> 'fa-solid fa-clipboard-check',
            self::terminado=> 'fa-solid ffa-check-circle',
            self::cancelado => 'fa-solid fa-ban',
        };

    }
}
