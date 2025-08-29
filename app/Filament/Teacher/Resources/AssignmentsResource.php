<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Resources\AssignmentsResource\Pages;
use App\Filament\Teacher\Resources\AssignmentResource\RelationManagers\SolutionsRelationManager;
use App\Models\Assignment;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class AssignmentsResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
         ->schema([
            Section::make('Assignment Details')
                ->schema([
                TextInput::make('title')->required(),
                FileUpload::make('file')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),

                    Hidden::make('teacher_id')->default(fn() => auth()->user()->teacher->id),
                Select::make('subject_id')
                    ->label('subject')
                    ->required()
                    ->relationship('subject', 'name',
                    fn (Builder $query) => $query->where('teacher_id', auth()->user()->teacher->id)
                    ),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()
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
                    ->default(fn() => auth()->user()->teacher->subjects->first()?->id)
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            return $query->where('subject_id', $data['value']);
                        }
                        return $query->where('subject_id', auth()->user()->teacher->subjects->first()?->id);
                    })
            ])
            ->actions([
                Action::make('view_file')
                    ->label('View file')
                    ->icon('heroicon-o-eye')
                    ->visible(fn($record) => !empty($record->file))
                    ->modalContent(fn($record) => view('filament.resources.forms.components.pdf-viewer', [
                        'file_url' => asset('storage/' . $record->file)
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

            // Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),

            Action::make('add_solution')
                ->label('Add solution')
                ->icon('heroicon-o-document-arrow-up')
                ->visible(fn($record): bool => !$record->solution()->exists())
                ->modalHeading('add assignment solution')
                ->form([
                    TextInput::make('title')->required(),

                    FileUpload::make('solutionFile')
                        ->label('add solution file')
                        ->acceptedFileTypes(['application/pdf'])
                        ->required(),
                ])
                ->action(function (array $data, $record) {
                    $record->solution()->create([
                        'title' =>  $data['title'],
                        'solutionFile' => $data['solutionFile'],
                        'teacher_id' =>  auth()->user()->teacher->id,
                        'teacher_details' =>  auth()->user()->teacher
                    ]);
                    Notification::make()
                        ->body('Added successfully')
                        ->success()
                        ->send();
                }),
        ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SolutionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => AssignmentsResource\Pages\ListAssignments::route('/'),
            'create' => AssignmentsResource\Pages\CreateAssignments::route('/create'),
            'edit' => AssignmentsResource\Pages\EditAssignments::route('/{record}/edit'),
            'view' => AssignmentsResource\Pages\ViewAssignment::route('/view/{record}'),
        ];
    }
}
