<?php

namespace App\Enums;

enum VendorStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Suspended = 'suspended';
    case Blacklisted = 'blacklisted';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Suspended => 'Suspended',
            self::Blacklisted => 'Blacklisted',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Approved => 'green',
            self::Suspended => 'orange',
            self::Blacklisted => 'red',
        };
    }
}
