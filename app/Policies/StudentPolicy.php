<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        // Owner, Principal, Admin, and Teachers can view students
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin', 'Teacher', 'SuperAdmin']);
    }

    /**
     * Determine if the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner, Principal, Admin, and Teachers can view
        if (in_array($user->role->name, ['Owner', 'Principal', 'Admin', 'Teacher'])) {
            return true;
        }

        // Students can view their own profile
        if ($user->role->name === 'Student' && $user->id === $student->user_id) {
            return true;
        }

        // Guardians can view their wards
        if ($user->role->name === 'Guardian') {
            return $student->guardians()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine if the user can create students.
     */
    public function create(User $user): bool
    {
        // Owner, Principal and Admin can create students
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin', 'SuperAdmin']);
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner, Principal and Admin can update students
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin']);
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner, Principal and Admin can delete students
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin']);
    }

    /**
     * Determine if the user can restore the student.
     */
    public function restore(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner, Principal and Admin can restore students
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin']);
    }

    /**
     * Determine if the user can permanently delete the student.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner and Principal can permanently delete students
        return in_array($user->role->name, ['Owner', 'Principal']);
    }

    /**
     * Determine if the user can promote students.
     */
    public function promote(User $user): bool
    {
        // Owner, Principal and Admin can promote students
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin']);
    }

    /**
     * Determine if the user can change student status.
     */
    public function changeStatus(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner, Principal and Admin can change student status
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin']);
    }

    /**
     * Determine if the user can view student grades.
     */
    public function viewGrades(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner, Principal, Admin, and Teachers can view grades
        if (in_array($user->role->name, ['Owner', 'Principal', 'Admin', 'Teacher'])) {
            return true;
        }

        // Students can view their own grades
        if ($user->role->name === 'Student' && $user->id === $student->user_id) {
            return true;
        }

        // Guardians can view their ward's grades
        if ($user->role->name === 'Guardian') {
            return $student->guardians()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine if the user can manage student documents.
     */
    public function manageDocuments(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner, Principal and Admin can manage documents
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin']);
    }

    /**
     * Determine if the user can enroll students.
     */
    public function enroll(User $user): bool
    {
        // Owner, Principal and Admin can enroll students
        return in_array($user->role->name, ['Owner', 'Principal', 'Admin']);
    }

    /**
     * Determine if the user can transfer students.
     */
    public function transfer(User $user, Student $student): bool
    {
        // User must belong to the same school as the student
        if ($user->school_id !== $student->school_id) {
            return false;
        }

        // Owner and Principal can transfer students
        return in_array($user->role->name, ['Owner', 'Principal']);
    }
}
