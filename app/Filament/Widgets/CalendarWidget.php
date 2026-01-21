<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Manufacturing;
use App\Filament\Resources\EventResource;
use App\Filament\Resources\ManufacturingResource;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    // protected static string $view = 'filament.widgets.calendar-widget';
    public function fetchEvents(array $fetchInfo): array
    {
        $events = Event::query()
            ->get()
            ->map(fn (Event $event) => [
                'id' => 'event-' . $event->id,
                'title' => $event->title,
                'backgroundColor' => $event->color,
                'start' => $event->starts_at,
                'end' => $event->ends_at,
                'url' => EventResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                'shouldOpenUrlInNewTab' => false,
            ])
            ->all();

        $manufacturings = Manufacturing::query()
            ->whereNotNull('fecha_inicio')
            ->whereNotNull('fecha_fin')
            ->get()
            ->map(fn (Manufacturing $manufacturing) => [
                'id' => 'manufacturing-' . $manufacturing->id,
                'title' => 'Orden de Fabricacion #' . $manufacturing->folio,
                'backgroundColor' => '#fb8c00',
                'start' => $manufacturing->fecha_inicio,
                'end' => $manufacturing->fecha_fin,
                'url' => ManufacturingResource::getUrl(name: 'edit', parameters: ['record' => $manufacturing]),
                'shouldOpenUrlInNewTab' => false,
            ])
            ->all();

        return array_merge($events, $manufacturings);
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

