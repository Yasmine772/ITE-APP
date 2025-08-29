<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\TeacherReferenceResource\Pages;
use App\Filament\Teacher\Resources\TeacherReferenceResource\RelationManagers;
use App\Models\TeacherReference;
use App\Services\ResourceService;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Resource as ResourceModel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TeacherReferenceResource extends Resource
{
    protected static ?string $model =ResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $pluralLabel = 'Resources';

    public static function getEloquentQuery(): Builder
    {
        $teacherId = auth()->user()->teacher->id ?? null;
        return ResourceModel::query()->where('teacher_id', $teacherId);
    }




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required(),
                Forms\Components\FileUpload::make('file')
                    ->label('PDF File')
                    ->directory('resources')
                    ->required(),
                Forms\Components\Select::make('resourceable_type')
                    ->label('Type')
                    ->options([
                        'course' => 'Course',
                        'subject' => 'Subject',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('resourceable_name')
                    ->label('Course/Subject Name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('resourceable_type')->label('Type')->sortable(),
                Tables\Columns\TextColumn::make('resourceable.name')->label('Name'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->action(function ($record, array $data) {

                        $service = app(ResourceService::class);
                        $service->update($data, $record->id);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        /** @var ResourceService $service */
                        $service = app(ResourceService::class);
                        $service->destroy($record->id);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function ($records) {

                        $service = app(ResourceService::class);
                        foreach ($records as $record) {
                            $service->destroy($record->id);
                        }
                    }),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherReferences::route('/'),
            'create' => Pages\CreateTeacherReference::route('/create'),
            'edit' => Pages\EditTeacherReference::route('/{record}/edit'),
        ];
    }
}
