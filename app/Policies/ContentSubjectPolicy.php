<?php

// namespace App\Policies;

// use App\Models\ContentSubject;
// use App\Models\User;
// use Illuminate\Auth\Access\Response;

// class ContentSubjectPolicy
// {
//     /**
//      * Determine whether the user can view any models.
//      */
//     public function viewAny(User $user): bool
//     {
//         return false;
//     }

//     /**
//      * Determine whether the user can view the model.
//      */
//     public function view(User $user, ContentSubject $contentSubject): bool
//     {
//         return false;
//     }

//     /**
//      * Determine whether the user can create models.
//      */
//     public function create(User $user): bool
//     {
//         return false;
//     }

//     /**
//      * Determine whether the user can update the model.
//      */
//     public function update(User $user, ContentSubject $contentSubject): bool
//     {
//         return false;
//     }

//     /**
//      * Determine whether the user can delete the model.
//      */
//     public function delete(User $user, ContentSubject $contentSubject): bool
//     {
//         return false;
//     }

//     /**
//      * Determine whether the user can restore the model.
//      */
//     public function restore(User $user, ContentSubject $contentSubject): bool
//     {
//         return false;
//     }

//     /**
//      * Determine whether the user can permanently delete the model.
//      */
//     public function forceDelete(User $user, ContentSubject $contentSubject): bool
//     {
//         return false;
//     }

// } 
namespace App\Policies;

use App\Models\ContentSubject;
use App\Models\Subject;
use App\Models\User;

class ContentSubjectPolicy
{
    public function view(User $user, ContentSubject $contentSubject): bool
    {
        return $this->ownsSubject($user, $contentSubject);
    }

    public function update(User $user, ContentSubject $contentSubject): bool
    {
        return $this->ownsSubject($user, $contentSubject);
    }

    public function delete(User $user, ContentSubject $contentSubject): bool
    {
        return $this->ownsSubject($user, $contentSubject);
    }

    public function create(User $user, Subject $subject): bool
    {
        return $user->teacher && $subject->teacher_id === $user->teacher->id;
    }

    private function ownsSubject(User $user, ContentSubject $contentSubject): bool
    {
        return $user->teacher && $contentSubject->subject && $contentSubject->subject->teacher_id === $user->teacher->id;
    }
}
