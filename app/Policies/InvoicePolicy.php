<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
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

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->submitted_by;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'buyer']);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->submitted_by
            && $invoice->status?->value === 'pending';
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->submitted_by
            && $invoice->status?->value === 'pending';
    }

    public function approve(User $user, Invoice $invoice): bool
    {
        // Only admin/manager — handled by before()
        return false;
    }

    public function threeWayMatch(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->submitted_by;
    }

    public function exportPdf(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->submitted_by;
    }
}
