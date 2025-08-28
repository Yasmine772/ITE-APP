<?php

namespace App\Filament\Resources\AssignmentResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SolutionsRelationManager extends RelationManager
{
    protected static string $relationship = 'solution';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),

                FileUpload::make('solutionFile')
                    ->label('add solution file')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),

                Hidden::make('teacher_id')->default(auth()->user()->teacher->id)->required(),
                Hidden::make('teacher_details')->default(auth()->user()->teacher)->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('solutions')
            ->columns([
                TextColumn::make('title')
        ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('view_file')
                ->label('View file')
                ->icon('heroicon-o-eye')
                ->visible(fn($record) => !empty($record->solutionFile))
                ->modalContent(fn($record) => view('filament.resources.forms.components.pdf-viewer', [
                    'file_url' => asset('storage/' . $record->solutionFile)
                ]))
                ->modalSubmitAction(false)
                ->modalCancelAction(false),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
