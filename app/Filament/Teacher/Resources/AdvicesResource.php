<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Resources\AdvicesResource\Pages;
use App\Models\Advice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    fn(Builder $query) => $query->where('teacher_id', auth()->user()->teacher?->id)
                )
                ->placeholder(null)
                ->label('Your subjects')
                ->default(fn() => auth()->user()->teacher->subjects->first()?->id)
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
            'index' => AdvicesResource\Pages\ListAdvices::route('/'),
            'create' => AdvicesResource\Pages\CreateAdvices::route('/create'),
            'edit' => AdvicesResource\Pages\EditAdvices::route('/{record}/edit'),
        ];
    }
}
