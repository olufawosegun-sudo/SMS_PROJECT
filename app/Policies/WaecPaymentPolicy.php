<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaecPayment;

class WaecPaymentPolicy
{
    /**
     * Determine if the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        // Owner, Principal, Vice Principal can view all payments
        return in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal']);
    }

    /**
     * Determine if the user can view the payment.
     */
    public function view(User $user, WaecPayment $payment): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $payment->school_id) {
            return false;
        }

        // Owner, Principal, Vice Principal can view all payments
        if (in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal'])) {
            return true;
        }

        // Students can view their own payments
        if ($user->role->name === 'Student' && $user->student && $user->student->id === $payment->student_id) {
            return true;
        }

        // Guardians can view their ward's payments
        if ($user->role->name === 'Guardian' && $user->guardian) {
            if ($payment->guardian_id === $user->guardian->id) {
                return true;
            }

            // Also check if guardian is linked to the student
            return $payment->student->guardians()->where('guardians.id', $user->guardian->id)->exists();
        }

        return false;
    }

    /**
     * Determine if the user can create payments.
     */
    public function create(User $user): bool
    {
        // Guardians and Students can submit payments
        return in_array($user->role->name, ['Guardian', 'Student']);
    }

    /**
     * Determine if the user can update the payment.
     */
    public function update(User $user, WaecPayment $payment): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $payment->school_id) {
            return false;
        }

        // Only owner can update payments in certain cases
        if ($user->role->name === 'Owner') {
            return true;
        }

        // Guardian/Student can only update their own pending payments
        if (in_array($user->role->name, ['Guardian', 'Student'])) {
            $canModify = ($user->role->name === 'Guardian' && $payment->guardian_id === $user->guardian?->id)
                      || ($user->role->name === 'Student' && $payment->student_id === $user->student?->id);

            return $canModify && $payment->status === 'pending';
        }

        return false;
    }

    /**
     * Determine if the user can approve payments.
     */
    public function approve(User $user, WaecPayment $payment): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $payment->school_id) {
            return false;
        }

        // ONLY Principal (and Vice Principal) can approve payments
        // Owner can also approve for oversight purposes
        return in_array($user->role->name, ['Principal', 'Vice Principal', 'Owner']);
    }

    /**
     * Determine if the user can reject payments.
     */
    public function reject(User $user, WaecPayment $payment): bool
    {
        // Same as approve - only Principal can reject
        return $this->approve($user, $payment);
    }

    /**
     * Determine if the user can cancel the payment.
     */
    public function cancel(User $user, WaecPayment $payment): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $payment->school_id) {
            return false;
        }

        // Owner and Principal can cancel any payment
        if (in_array($user->role->name, ['Owner', 'Principal'])) {
            return true;
        }

        // Guardian/Student can cancel their own pending payments
        if (in_array($user->role->name, ['Guardian', 'Student'])) {
            $isOwner = ($user->role->name === 'Guardian' && $payment->guardian_id === $user->guardian?->id)
                    || ($user->role->name === 'Student' && $payment->student_id === $user->student?->id);

            return $isOwner && $payment->status === 'pending';
        }

        return false;
    }

    /**
     * Determine if the user can download receipt.
     */
    public function downloadReceipt(User $user, WaecPayment $payment): bool
    {
        // Only approved payments have receipts
        if ($payment->status !== 'approved') {
            return false;
        }

        return $this->view($user, $payment);
    }

    /**
     * Determine if the user can view payment reports.
     */
    public function viewReports(User $user): bool
    {
        // Only Owner can view comprehensive reports
        return $user->role->name === 'Owner';
    }

    /**
     * Determine if the user can export payment data.
     */
    public function export(User $user): bool
    {
        // Owner and Principal can export
        return in_array($user->role->name, ['Owner', 'Principal']);
    }

    /**
     * Determine if the user can view approval history.
     */
    public function viewApprovalHistory(User $user, WaecPayment $payment): bool
    {
        // User must belong to the same school
        if ($user->school_id !== $payment->school_id) {
            return false;
        }

        // Owner, Principal can view approval history
        return in_array($user->role->name, ['Owner', 'Principal', 'Vice Principal']);
    }
}
