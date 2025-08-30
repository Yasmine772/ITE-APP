<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Subject;
use App\Models\Advertisement;
use App\Livewire\ChatBox;
use app\Livewire\ConversationsWidget;


class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static string $view = 'filament.pages.dashboard';
    protected function getWidgets(): array
    {
        return [
            \App\Http\Livewire\ConversationsWidget::class ,
            \App\Http\Livewire\ChatBox::class,
        ];
    }

    public function getStudentCountsPerYear(): array
    {
        $years = User::role('student')
            ->selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year');

        $counts = [];
        foreach ($years as $year) {
            $counts[] = User::role('student')->whereYear('created_at', $year)->count();
        }

        return [
            'years' => $years->toArray(),
            'counts' => $counts,
        ];
    }

    public function getTeacherCountsPerYear(): array
    {
        $years = User::role('teacher')
            ->selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year');

        $counts = [];
        foreach ($years as $year) {
            $counts[] = User::role('teacher')->whereYear('created_at', $year)->count();
        }

        return [
            'years' => $years->toArray(),
            'counts' => $counts,
        ];
    }

    public function getLatestAdvertisements()
    {
        return Advertisement::latest()->take(5)->get();
    }

    public function getTotalStudents() { return User::role('student')->count(); }
    public function getTotalTeachers() { return User::role('teacher')->count(); }
    public function getTotalSubjects() { return Subject::count(); }


}
