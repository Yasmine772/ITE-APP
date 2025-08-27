<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AdminProfileWidget extends Widget
{
    protected static string $view = 'filament.widgets.admin-profile-widget';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::check();
    }
}
