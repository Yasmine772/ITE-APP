<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasProfilePhoto;
    use HasTeams;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'image',
        'gender',
        'birth_date',
        'bio',
        'role',
    ];


    public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Article::class, 'articles');
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function advertisements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function exam() 
    {
        return $this->hasMany(Exam::class, 'exams');
    }

    public function answer()
    {
        return $this->hasMany(Answer::class);
    }

    public function mark()
    {
        return $this->hasMany(Mark::class, 'marks');
    }









    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'roles', // إذا كان هناك داعي لإخفاءها من الـ JSON
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function teacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Teacher::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    public function subscriptions()
    {
        return $this->hasMany(CourseSubscription::class);
    }
    public function subscribedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_subscriptions')
            ->withPivot(['status', 'is_paid', 'paid_at'])
            ->withTimestamps();
    }
       public function courseProgresses()
    {
        return $this->hasMany(CourseProgress::class);
    }

    public function courseContentProgresses()
    {
        return $this->hasMany(CourseContentProgress::class);
    }
}
