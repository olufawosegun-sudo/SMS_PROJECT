<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaecRemittance;

class WaecRemittancePolicy
{
    /**
     * Determine if the user can view any remittances.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal']);
    }

    /**
     * Determine if the user can view the remittance.
     */
    public function view(User $user, WaecRemittance $remittance): bool
    {
        return $user->school_id === $remittance->school_id &&
               in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal']);
    }

    /**
     * Determine if the user can create remittances.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal']);
    }
}
