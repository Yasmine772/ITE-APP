<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Rating;
use App\Observers\RatingObserver;
use App\Models\CourseContent;
use App\Observers\CourseContentObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Rating::observe(RatingObserver::class);

        CourseContent::observe(CourseContentObserver::class);
    }
}
