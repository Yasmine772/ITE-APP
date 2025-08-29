<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\NotificationResource\Pages;
use App\Filament\Teacher\Resources\NotificationResource\RelationManagers;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Notifications\DatabaseNotification;

class NotificationResource extends Resource
{

    protected static ?string $model = DatabaseNotification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $pluralLabel = 'Notifications';
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', $user->getMorphClass());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->disabled()
                    ->label('Title')
                    ->formatStateUsing(fn ($state, $record) => data_get(
                        is_array($record->data) ? $record->data : json_decode($record->data, true),
                        'title',
                        '-'
                    )),
                Forms\Components\Textarea::make('message')
                    ->disabled()
                    ->label('Message')
                    ->formatStateUsing(fn ($state, $record) => data_get(
                        is_array($record->data) ? $record->data : json_decode($record->data, true),
                        'message',
                        '-'
                    )),
                Forms\Components\TextInput::make('created_at')
                    ->disabled()
                    ->label('Created At')
                    ->formatStateUsing(fn ($state, $record) => $record?->created_at?->format('d/m/Y H:i') ?? '-')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn(DatabaseNotification $record) => data_get(
                        is_array($record->data) ? $record->data : json_decode($record->data, true),
                        'title',
                        '-'
                    ))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
              //  Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check')
                    ->visible(fn(DatabaseNotification $record): bool => $record->read_at === null)
                    ->action(fn(DatabaseNotification $record) => $record->markAsRead()),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
          //  'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
