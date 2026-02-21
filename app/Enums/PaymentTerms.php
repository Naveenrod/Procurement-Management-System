<?php

namespace App\Enums;

enum PaymentTerms: string
{
    case Net30 = 'net30';
    case Net60 = 'net60';
    case Net90 = 'net90';
    case Immediate = 'immediate';

    public function label(): string
    {
        return match($this) {
            self::Net30 => 'Net 30',
            self::Net60 => 'Net 60',
            self::Net90 => 'Net 90',
            self::Immediate => 'Immediate',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Net30 => 'gray',
            self::Net60 => 'gray',
            self::Net90 => 'gray',
            self::Immediate => 'gray',
        };
    }
}
