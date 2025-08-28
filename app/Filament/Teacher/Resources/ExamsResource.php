<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Resources\ExamsResource\Pages;
use App\Models\Exam;
use App\Rules\OnlyOneCorrectOption;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class ExamsResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Exam Details')
                        ->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('description')->required(),
                            TextInput::make('duration')->numeric()->suffix('minutes')->minValue(60)->required(),
                            Select::make('subject_id')
                                ->label('subject')
                                ->required()
                                ->relationship('subject', 'name',
                            fn (Builder $query) => $query->where('teacher_id', auth()->user()->teacher->id)
                            ),
                            Hidden::make('user_id')->default(auth()->user()->id)->required()
                        ]),

                    Wizard\Step::make('Add Questions')
                        ->schema([
                            Repeater::make('questions')
                                ->relationship()
                                ->label('Questions')
                                ->schema([
                                    Textarea::make('question_text')
                                        ->label('Question text')->required(),
                                    TextInput::make('mark')
                                        ->label('Mark')->numeric()->minValue(1)->required(),

                                    Repeater::make('options')
                                        ->relationship()
                                        ->label('Options')
                                        ->schema([
                                            Textarea::make('answer_text')
                                                ->label('Answer text')->required()->rows(2),

                                            ToggleButtons::make('is_correct')
                                                ->label('Is correct ?')
                                                ->inline()
                                                ->boolean()
                                                ->options([
                                                    true => 'Yes',
                                                    false => 'No',
                                                ])
                                                ->required(),
                                        ])
                                        ->rule([new OnlyOneCorrectOption])
                                        ->columns(2)
                                        ->defaultItems(3)
                                        ->maxItems(3)
                                        ->addActionLabel('New option'),
                                ])
                                ->columns(1)
                                ->defaultItems(1)
                                ->addActionLabel('New Question')
                                ->collapsible()
                                ->columnSpan('full'),
                        ])
            ])
                ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('description')->limit(20),
                TextColumn::make('duration')->suffix(' minutes'),
        ])
            ->filters([
                    SelectFilter::make('subject_id')
                        ->relationship(
                            'subject', 'name',
                        fn (Builder $query) => $query->where('teacher_id', auth()->user()->teacher->id)
                        )
                    ->placeholder(null)
                    ->label('Your subjects')
                    ->default(fn() => auth()->user()->teacher->subjects->first()?->id)
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                        return $query->where('subject_id', $data['value']);
                        }
                    return $query->where('subject_id', auth()->user()->teacher->subjects->first()?->id);
                })
        ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [ ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ExamsResource\Pages\ListExams::route('/'),
            'create' => ExamsResource\Pages\CreateExams::route('/create'),
            'edit' => ExamsResource\Pages\EditExams::route('/{record}/edit'),
            'view' => ExamsResource\Pages\ViewExams::route('/{record}/view'),
        ];
    }
}

