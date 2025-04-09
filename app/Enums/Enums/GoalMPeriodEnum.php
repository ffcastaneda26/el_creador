<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
enum GoalMPeriodEnum: string implements HasLabel,HasColor,HasIcon
{
    case horas = 'horas';
    case dias = 'dias';
    case semanas = 'semanas';
    case meses = 'meses';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::horas => 'Horas',
            self::dias=> 'Dias',
            self::semanas=> 'Semanas',
            self::meses=> 'Meses',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::horas => 'warning',
            self::dias=> 'success',
            self::semanas=> 'primary',
            self::meses=> 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::horas => 'heroicon-m-clock',
            self::dias=> 'heroicon-m-calendar-days',
            self::semanas=> 'heroicon-m-calendar-days',
            self::meses => 'heroicon-m-calendar',
        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::horas => 'fa-regular fa-clock-o',
            self::dias=> 'fa-solid fa-calendar',
            self::semanas=> 'fa-solid fa-calendar-plus-o',
            self::meses => 'fa-solid fa-meetup',
        };

    }
}
