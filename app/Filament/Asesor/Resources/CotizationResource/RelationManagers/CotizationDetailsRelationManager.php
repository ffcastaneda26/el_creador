<?php
namespace App\Filament\Asesor\Resources\CotizationResource\RelationManagers;

use App\Filament\Asesor\Resources\CotizationResource;
use App\Rules\ValidImageExtension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CotizationDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (callable $get, Set $set, ?string $state) {
                            $description = $get('description');
                            if (! $description || strlen($description)) {
                                $set('description', $state);
                            }

                        }),
                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')

                            ->numeric()
                            ->required()
                            ->default(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get, $this->ownerRecord->details)),
                        Forms\Components\TextInput::make('price')
                            ->label('Precio Unitario')

                            ->numeric()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get, $this->ownerRecord->details))])->columns(2),
                    Forms\Components\FileUpload::make('image')
                        ->required()
                        ->translateLabel()
                        ->getUploadedFileNameForStorageUsing(
                            fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend(time() . '_'),
                        )
                        ->directory('cotizations')
                        ->rules([new ValidImageExtension]), // Aquí añades la validación de tipos,

                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\MarkdownEditor::make('description')
                        ->translateLabel(),
                ]),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Cotization Items'))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->translateLabel(),
                Tables\Columns\TextColumn::make('quantity')->translateLabel(),
                Tables\Columns\TextColumn::make('price')->translateLabel(),
                Tables\Columns\ImageColumn::make('image')->circular()->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Add Item'))
                    ->after(function () {
                        $this->dispatch('recalculateTotals');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function () {
                        // Después de editar un detalle, forzamos la recarga del formulario principal
                        $this->dispatch('recalculateTotals');
                    }),
                Tables\Actions\DeleteAction::make()->after(
                    function ($record) {
                        Storage::disk('public')->delete($record->image);
                        $this->dispatch('recalculateTotals');
                    }
                ),
            ]);
    }
}
