<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

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

    ];

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Article::class, 'articles');
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
        'academic_qualification',
        'experience',
        'degree'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */


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
    public function ratings(): \Illuminate\Database\Eloquent\Relations\HasMany
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
    public function courseProgresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseProgress::class);
    }

    public function courseContentProgresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseContentProgress::class);
    }

    public function resources(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Resource::class,Teacher::class );
    }
    public function personalBlogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PersonalBlog::class);
    }
    public function assignDefaultRole(): void
    {
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentPermission = [];
        foreach (config('permission.roles_permissions.student') as $group => $actions) {
            foreach ($actions as $action) {
                $studentPermission[] = "$group.$action";
            }
        }
        foreach ($studentPermission as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            if (! $studentRole->hasPermissionTo($permission)) {
                $studentRole->givePermissionTo($permission);
            }
        }
        if ($this->roles->isEmpty()) {
            $this->assignRole('student');
        }

    }
   public function myResources(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
   {
        return $this->belongsToMany(Resource::class);
   }
    public function receivedAdvertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_user')->withTimestamps();
    }


}
