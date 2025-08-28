<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AdminProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Account';
    protected static ?string $modelLabel = 'View Account';
    protected static ?string $title = 'My Profile';
    protected static string $view = 'filament.pages.admin-profile';

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void
    {
        $this->form->fill([
            'name'    => Auth::user()->name,
            'email'   => Auth::user()->email,
            'address' => Auth::user()->address,
            'bio'     => Auth::user()->bio,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->disabled(fn() => ! $this->isEditing),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->disabled(fn() => ! $this->isEditing),


                Forms\Components\TextInput::make('address')
                    ->label('Address')
                    ->disabled(fn() => ! $this->isEditing),


                Forms\Components\Textarea::make('bio')
                    ->label('Bio')
                    ->rows(3)
                    ->disabled(fn() => ! $this->isEditing),


                Forms\Components\Placeholder::make('updated_at')
                    ->label('Last Updated')
                    ->content(fn () => Auth::user()->updated_at->diffForHumans()),
            ])
            ->statePath('data');
    }


    public function toggleEdit(): void
    {
        $this->isEditing = ! $this->isEditing;
    }


    public function save(): void
    {
        $user = Auth::user();
        $user->update(array_filter($this->form->getState()));

        Notification::make()
            ->title('Profile updated successfully!')
            ->success()
            ->send();

        $this->isEditing = false;
    }
}
