<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Pending = 'pending';
    case Matched = 'matched';
    case Approved = 'approved';
    case Paid = 'paid';
    case Disputed = 'disputed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Matched => 'Matched',
            self::Approved => 'Approved',
            self::Paid => 'Paid',
            self::Disputed => 'Disputed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Matched => 'blue',
            self::Approved => 'green',
            self::Paid => 'emerald',
            self::Disputed => 'red',
            self::Cancelled => 'gray',
        };
    }
}
