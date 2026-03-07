<?php

namespace App\Policies;

use App\Models\Rfq;
use App\Models\User;

class RfqPolicy
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

    public function view(User $user, Rfq $rfq): bool
    {
        return $user->id === $rfq->issued_by;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'buyer']);
    }

    public function update(User $user, Rfq $rfq): bool
    {
        return $user->id === $rfq->issued_by
            && $rfq->status === 'draft';
    }

    public function delete(User $user, Rfq $rfq): bool
    {
        return $user->id === $rfq->issued_by;
    }

    public function publish(User $user, Rfq $rfq): bool
    {
        return $user->id === $rfq->issued_by;
    }

    public function close(User $user, Rfq $rfq): bool
    {
        return $user->id === $rfq->issued_by;
    }

    public function award(User $user, Rfq $rfq): bool
    {
        return $user->id === $rfq->issued_by;
    }

    public function exportPdf(User $user, Rfq $rfq): bool
    {
        return $user->id === $rfq->issued_by;
    }
}
