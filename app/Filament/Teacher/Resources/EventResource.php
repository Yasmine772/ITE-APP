<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\EventResource\Pages;
use App\Filament\Teacher\Resources\EventResource\RelationManagers;
use App\Models\Advertisement;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;

class EventResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->required(),
                    ]),
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->user()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Teacher')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter by Teacher')
                    ->options(User::role('teacher')->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => auth()->user()->id === $record->user_id),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()->id === $record->user_id),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
           // 'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }
}
