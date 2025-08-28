<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvicesResource\Pages;
use App\Models\Advice;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class AdvicesResource extends Resource
{
    protected static ?string $model = Advice::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\RichEditor::make('content')
                ->label('Content')
                ->required()
                ->disableToolbarButtons([
                    'attachFiles',
                    'blockquote',
                    'bulletList',
                    'codeBlock',
                    'italic',
                    'link',
                    'orderedList',
                    'strike',
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')->label('Content')->limit(70),
            ])
            ->filters([
            SelectFilter::make('subject_id')
                ->relationship(
                    'subject',
                    'name',
                    fn(Builder $query) => $query->where('teacher_id', auth()->user()->teacher->id)
                )
                ->placeholder(null)
                ->label('Your subjects')
                ->query(function (Builder $query, array $data) {
                    if (isset($data['value'])) {
                        $query->where('subject_id', $data['value']);
                    }
                })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
               // Tables\Actions\BulkActionGroup::make([
               //     Tables\Actions\DeleteBulkAction::make(),
               // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvices::route('/'),
            'create' => Pages\CreateAdvices::route('/create'),
            'edit' => Pages\EditAdvices::route('/{record}/edit'),
        ];
    }
}
