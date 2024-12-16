<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Support\Facades\App;
use Filament\Support\Contracts\HasLabel;

enum KeyMovementTypeEnum: string implements HasLabel,HasColor,HasIcon
{
    case I = 'I';
    case O = 'O';


    public function getLabel(): ?string
    {
        if(App::isLocale('en')){
            return match ($this) {
                self::I => 'Input',
                self::O => 'Output',
           };
        }
        return match ($this) {
            self::I => 'Entrada',
            self::O => 'Salida',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::I => 'success',
            self::O => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::I => 'heroicon-m-arrow-down-circle',
            self::O => 'heroicon-m-arrow-up-circle',
        };
    }
}
