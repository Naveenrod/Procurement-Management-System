<?php

namespace App\Enums;

enum RequisitionStatus: string
{
    case Draft = 'draft';
    case PendingApproval = 'pending_approval';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Ordered = 'ordered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Draft => 'Draft',
            self::PendingApproval => 'Pending Approval',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Ordered => 'Ordered',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft => 'gray',
            self::PendingApproval => 'yellow',
            self::Approved => 'green',
            self::Rejected => 'red',
            self::Ordered => 'blue',
            self::Cancelled => 'gray',
        };
    }
}
