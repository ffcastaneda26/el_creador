<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
enum GoalPeriodEnum: string implements HasLabel,HasColor,HasIcon
{
    case semanal = 'semanal';
    case mensual = 'mensual';
    case bimestral = 'bimestral';
    case trimestral = 'trimestral';
    case semestral = 'semestral';
    case anual = 'anual';




    public function getLabel(): ?string
    {
        return match ($this) {
            self::semanal => 'Semanal',
            self::mensual=> 'Mensual',
            self::bimestral=> 'Bimestral',
            self::trimestral=> 'Trimestral',
            self::semestral=> 'Semestral',
            self::anual=> 'Anual',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::semanal => 'warning',
            self::mensual=> 'success',
            self::bimestral=> 'primary',
            self::trimestral=> 'danger',
            self::semestral=> 'indigo',
            self::anual=> 'orange',
       };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::semanal => 'heroicon-m-clock',
            self::mensual=> 'heroicon-m-calendar-days',
            self::bimestral=> 'heroicon-m-calendar-days',
            self::trimestral => 'heroicon-m-calendar',
            self::semestral => 'heroicon-m-calendar',
            self::anual => 'heroicon-m-calendar',
        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::semanal => 'fa-regular fa-clock-o',
            self::mensual=> 'fa-solid fa-calendar',
            self::bimestral=> 'fa-solid fa-calendar-plus-o',
            self::trimestral => 'fa-solid fa-meetup',
            self::semestral=> 'fa-solid fa-calendar',
            self::anual=> 'fa-solid fa-calendar-plus-o',

        };

    }
}
