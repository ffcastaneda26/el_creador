<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ClientTypeEnum:  string implements HasLabel,HasColor,HasIcon
{
    case fisica = 'Física';
    case moral = 'Moral';
    case sinefectosfiscales = 'Sin Efectos Fiscales';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::fisica => 'Física',
            self::moral=> 'Moral',
            self::sinefectosfiscales=> 'Sin Efectos Fiscales',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::fisica => 'warning',
            self::moral=> 'success',
            self::sinefectosfiscales=> 'danger',

       };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::fisica => 'heroicon-m-user',
            self::moral=> 'heroicon-m-building-library',
            self::sinefectosfiscales=> 'heroicon-m-no-symbol',

        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::fisica => 'fa-solid fa-user',
            self::moral=> 'fa-solid fa-building-columns',
            self::sinefectosfiscales=> 'fa-solid fa-circle-xmark',


        };

    }
}
