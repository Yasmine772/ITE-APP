<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticlesResource\Pages;
use App\Models\Article;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ArticlesResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Article details')
                ->schema([
                    Textarea::make('title')->rows(1)->readOnly(),
                    Textarea::make('content')->rows(100)->readOnly(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Stack::make([
                TextColumn::make('title'),
                TextColumn::make('content')->limit(90), 
            ]),        
        ])
            ->filters([
                Filter::make('Pending')
                    ->query(fn(Builder $query): Builder => $query->where('status', 'Pending'))
                    ->default(),
        ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
               
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
            'index' => Pages\ListArticles::route('/'),
            'view' => Pages\ViewArticles::route('/view/{record}'),
        ];
    }
}
