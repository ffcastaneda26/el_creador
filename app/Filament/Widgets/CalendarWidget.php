<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Forms\Components\Group;
use Filament\Widgets\Widget;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\EventResource;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group as ComponentsGroup;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    // protected static string $view = 'filament.widgets.calendar-widget';
    // public function fetchEvents(array $fetchInfo): array
    // {
    //     return Event::query()
    //         ->where('starts_at', '>=', $fetchInfo['start'])
    //         ->where('ends_at', '<=', $fetchInfo['end'])
    //         ->get()
    //         ->map(
    //             fn (Event $event) => [
    //                 'title' => $event->id,
    //                 'start' => $event->starts_at,
    //                 'end' => $event->ends_at,
    //                 'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
    //                 'shouldOpenUrlInNewTab' => true
    //             ]
    //         )
    //         ->all();
    // }
    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('starts_at', '>=', $fetchInfo['start'])
            ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Event $event) => EventData::make()
                    ->id($event->id)
                    ->title($event->title)
                    ->backgroundColor($event->color)
                    ->start($event->starts_at)
                    ->end($event->ends_at)
                    ->url(
                        url: EventResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                        shouldOpenUrlInNewTab: true
                    )
            )
            ->toArray();
    }
    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    // public function getFormSchema(): array
    // {
    //     return [
    //         ComponentsGroup::make([
    //             TextInput::make('title')
    //                 ->translateLabel()
    //                 ->required()
    //                 ->maxLength(255),
    //             Textarea::make('description')
    //                 ->translateLabel()
    //                 ->columnSpanFull(),
    //         ])->columns(2),
    //         ComponentsGroup::make([
    //             ColorPicker::make('color')
    //                 ->translateLabel()
    //                 ->default('#0df063')
    //                 ->required(),
    //            DateTimePicker::make('starts_at')
    //                 ->translateLabel()
    //                 ->default(now())
    //                 ->required(),
    //            DateTimePicker::make('ends_at')
    //                 ->translateLabel()
    //                 ->default(now())
    //                 ->required(),
    //         ])->columns(3),

    //     ];
    // }
}
