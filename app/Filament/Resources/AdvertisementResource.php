<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvertisementResource\Pages;
use App\Models\Advertisement;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Events';
    protected static ?string $pluralLabel = 'Advertisements';
    protected static ?string $modelLabel = 'Advertisement';

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
                Tables\Columns\TextColumn::make('created_at')->label('Created At'),
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
            'index' => Pages\ListAdvertisements::route('/'),
            'create' => Pages\CreateAdvertisement::route('/create'),
            'edit' => Pages\EditAdvertisement::route('/{record}/edit'),
            'view' => Pages\ViewAdvertisement::route('/{record}'),
        ];
    }
}
