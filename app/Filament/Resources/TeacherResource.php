<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Education staff';
    protected static ?string $modelLabel = 'Teacher';
    protected static ?string $pluralModelLabel = 'Teachers';

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
                                $component->state($record->user->name ?? '');
                            }),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->disabled()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                $component->state($record->user->email ?? '');
                            }),
                        Forms\Components\TextInput::make('bio')
                            ->label('Bio')
                            ->disabled()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                $component->state($record->user->bio ?? '');
                            }),
                    ]),
                Forms\Components\Section::make('Academin information')
                    ->schema([
                        Forms\Components\TextInput::make('academic_qualification')
                            ->label('Academic qualification')
                            ->required(),

                        Forms\Components\TextInput::make('years_of_experience')
                            ->label('years of experience')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('degree')
                            ->label('Degree')
                            ->required(),
                    ]),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('name')
                    ->sortable()
                    ->searchable()
                    ->default('غير محدد'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('email')
                    ->searchable()
                    ->default('غير محدد'),

                Tables\Columns\TextColumn::make('academic_qualification')
                    ->label('Academic qualification')
                    ->searchable(),

                Tables\Columns\TextColumn::make('years_of_experience')
                    ->label('years of experience')
                    ->sortable(),

                Tables\Columns\TextColumn::make('degree')
                    ->label('Degree')
                    ->searchable(),


            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('message')
                    ->label('Send Message')
                    ->icon('heroicon-o-chat-bubble-left-right'),
                   // ->url(fn ($record) => route('chat.with.teacher', $record))
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),


            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
            'view' => Pages\ViewTeacher::route('/{record}'),

        ];
    }

}







