<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Students';
    protected static ?string $modelLabel = 'student';
    protected static ?string $pluralModelLabel = 'students';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal data')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->disabled()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                $component->state($record->name);
                            }),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->disabled()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                $component->state($record->email);
                            }),
                        Forms\Components\TextInput::make('bio')
                            ->label('Bio')
                            ->disabled()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                $component->state($record->bio);
                            }),
                    ]),
//                Forms\Components\Section::make('Academin information')
//                    ->schema([
//                        Forms\Components\TextInput::make('academic_qualification')
//                            ->label('Academic qualification')
//                            ->required(),
//
//                        Forms\Components\TextInput::make('years_of_experience')
//                            ->label('years of experience')
//                            ->numeric()
//                            ->required(),
//
//                        Forms\Components\TextInput::make('degree')
//                            ->label('Degree')
//                            ->required(),
//                    ]),
           ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label('email')->searchable(),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
         //

        ];
    }




    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->role('student');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}'),
        ];
    }
}
