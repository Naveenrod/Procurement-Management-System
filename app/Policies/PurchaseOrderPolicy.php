<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
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

    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->id === $purchaseOrder->created_by;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'buyer']);
    }

    public function update(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->id === $purchaseOrder->created_by
            && in_array($purchaseOrder->status?->value, ['draft', 'pending_approval']);
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->id === $purchaseOrder->created_by;
    }

    public function approve(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Only admin/manager — handled by before()
        return false;
    }

    public function reject(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Only admin/manager — handled by before()
        return false;
    }

    public function send(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->id === $purchaseOrder->created_by
            && $purchaseOrder->status?->value === 'approved';
    }

    public function exportPdf(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->id === $purchaseOrder->created_by;
    }
}
