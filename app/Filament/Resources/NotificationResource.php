<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;

class NotificationResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;
    protected static ?string $navigationGroup = 'Notifications';
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $pluralLabel = 'Notifications';
    protected static ?string $modelLabel = 'My Notifications';


    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Section::make('')
                    ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->disabled()
                    ->formatStateUsing(fn ($state, $record) => data_get(
                        is_array($record->data) ? $record->data : json_decode($record->data, true),
                        'title',
                        '-'
                    )),

                Forms\Components\Textarea::make('message')
                    ->label('Message')
                    ->disabled()
                    ->formatStateUsing(fn ($state, $record) => data_get(
                        is_array($record->data) ? $record->data : json_decode($record->data, true),
                        'message',
                        '-'
                    )),
                        Forms\Components\TextInput::make('created_at')
                            ->label('Created At')
                            ->disabled()
                            ->formatStateUsing(fn ($state, $record) => $record && $record->created_at
                                ? $record->created_at->format('d/m/Y H:i')
                                : '-'
                            ),
                  ]),




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
                    ->searchable()

            ])
            ->headerActions([
                Tables\Actions\Action::make('markAllAsRead')
                    ->label('mark all as read')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->action(function () {
                        DatabaseNotification::query()->update(['read_at' => now()]);
                        Notification::make()
                            ->title('marked as read')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('deleteAll')
                    ->label('Delete all')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(function () {
                        DatabaseNotification::query()->delete();
                        Notification::make()
                            ->title('Deleted all ')
                            ->success()
                            ->send();
                    }),
            ])


            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check')
                    ->visible(fn(DatabaseNotification $record): bool => $record->read_at === null)
                    ->action(function (DatabaseNotification $record) {
                        $record->markAsRead();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ]);
    }


    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', $user->getMorphClass());
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'view'  => Pages\ViewNotification::route('/{record}'),
        ];
    }
}
