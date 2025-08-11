<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Rating;
use App\Observers\RatingObserver;
use App\Models\CourseContent;
use App\Observers\CourseContentObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
use App\Models\Subject;
use App\Models\Course;
use App\Models\User;
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
        Relation::enforceMorphMap([
        'subject' => Subject::class,
        'course' => Course::class ,
        'user'    => User::class,
    ]);
        Rating::observe(RatingObserver::class);
        CourseContent::observe(CourseContentObserver::class);

    }
}
