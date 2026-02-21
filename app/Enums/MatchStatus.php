<?php

namespace App\Enums;

enum MatchStatus: string
{
    case Pending = 'pending';
    case Matched = 'matched';
    case Mismatch = 'mismatch';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Matched => 'Matched',
            self::Mismatch => 'Mismatch',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Matched => 'green',
            self::Mismatch => 'red',
        };
    }
}
