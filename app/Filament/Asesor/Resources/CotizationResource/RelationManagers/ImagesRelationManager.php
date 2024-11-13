<?php

namespace App\Filament\Asesor\Resources\CotizationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'Images';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('image')
                        ->required()
                        ->translateLabel()
                        ->getUploadedFileNameForStorageUsing(
                            fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                ->prepend(time().'_'),
                        )
                        ->directory('cotizations')
                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\MarkdownEditor::make('description')
                        ->required()
                        ->translateLabel(),
                ]),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
                ->heading(__('Images'))
                ->recordTitleAttribute('name')
                ->columns([
                    Tables\Columns\TextColumn::make('name')->translateLabel(),
                    Tables\Columns\ImageColumn::make('image')->circular()->translateLabel(),
                    Tables\Columns\TextColumn::make('description')->limit(80)->translateLabel(),

                ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label(__('Add Image')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
