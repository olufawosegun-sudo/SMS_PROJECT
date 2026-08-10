<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaecCandidate;

class WaecCandidatePolicy
{
    /**
     * Determine if the user can view any candidates.
     */
    public function viewAny(User $user): bool
    {
        // Owner, Principal, Vice Principal, and Teachers can view candidates list
        return in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal', 'Teacher']);
    }

    /**
     * Determine if the user can view the candidate.
     */
    public function view(User $user, WaecCandidate $candidate): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $candidate->school_id) {
            return false;
        }

        // Owner, Principal, Vice Principal can view
        if (in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal'])) {
            return true;
        }

        // Teachers can view
        if ($user->role->name === 'Teacher') {
            return true;
        }

        // Students can view their own candidacy
        if ($user->role->name === 'Student' && $user->student && $user->student->id === $candidate->student_id) {
            return true;
        }

        // Guardians can view their ward's candidacy
        if ($user->role->name === 'Guardian' && $user->guardian) {
            return $candidate->student->guardians()->where('guardians.id', $user->guardian->id)->exists();
        }

        return false;
    }

    /**
     * Determine if the user can create candidates.
     */
    public function create(User $user): bool
    {
        // Only Owner, Principal, and Vice Principal can register candidates
        return in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal']);
    }

    /**
     * Determine if the user can update the candidate.
     */
    public function update(User $user, WaecCandidate $candidate): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $candidate->school_id) {
            return false;
        }

        // Only Owner and Principal can update candidates
        return in_array($user->role->name, ['Owner', 'Principal']);
    }

    /**
     * Determine if the user can delete/cancel the candidate.
     */
    public function delete(User $user, WaecCandidate $candidate): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $candidate->school_id) {
            return false;
        }

        // Only Owner and Principal can delete candidates
        return in_array($user->role->name, ['Owner', 'Principal']);
    }

    /**
     * Determine if the user can assign candidate numbers.
     */
    public function assignCandidateNumber(User $user, WaecCandidate $candidate): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $candidate->school_id) {
            return false;
        }

        // Only Owner and Principal can assign candidate numbers
        return in_array($user->role->name, ['Owner', 'Principal']);
    }

    /**
     * Determine if the user can view candidate payment details.
     */
    public function viewPaymentDetails(User $user, WaecCandidate $candidate): bool
    {
        return $this->view($user, $candidate);
    }
}
