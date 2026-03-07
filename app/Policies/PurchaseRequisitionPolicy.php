<?php

namespace App\Policies;

use App\Models\PurchaseRequisition;
use App\Models\User;

class PurchaseRequisitionPolicy
{
    /** Admins and managers bypass all checks */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'buyer']);
    }

    public function view(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->id === $requisition->requested_by;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'buyer']);
    }

    public function update(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->id === $requisition->requested_by
            && $requisition->status?->value === 'draft';
    }

    public function delete(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->id === $requisition->requested_by;
    }

    public function submit(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->id === $requisition->requested_by
            && $requisition->status?->value === 'draft';
    }

    public function approve(User $user, PurchaseRequisition $requisition): bool
    {
        // Only admin/manager — handled by before()
        return false;
    }

    public function reject(User $user, PurchaseRequisition $requisition): bool
    {
        // Only admin/manager — handled by before()
        return false;
    }

    public function convertToPo(User $user, PurchaseRequisition $requisition): bool
    {
        return $user->id === $requisition->requested_by
            && $requisition->status?->value === 'approved';
    }
}
