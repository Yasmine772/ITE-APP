<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceResource\Pages;
use App\Models\Resource;
use Filament\Forms;
use Filament\Resources\Resource as FilamentResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

class ResourceResource extends FilamentResource
{
    protected static ?string $model = Resource::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Resources';


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Title'),
                Forms\Components\FileUpload::make('file')
                    ->label('PDF File')
                    ->disk('public')
                    ->directory('resources')
                    ->visibility('public'),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title')->sortable()->searchable(),

                Tables\Columns\TextColumn::make('resourceable_type')
                    ->label('Type of resource')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {

        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->where('teacher_id', $teacher->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResources::route('/'),
            //'create' => Pages\CreateResource::route('/create'),
            //'edit' => Pages\EditResource::route('/{record}/edit'),
           // 'view' => Pages\ViewResource::route('/{record}'),
        ];
    }
}
