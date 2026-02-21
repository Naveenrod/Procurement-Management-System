<?php

namespace App\Enums;

enum WarehouseOrderType: string
{
    case Inbound = 'inbound';
    case Outbound = 'outbound';
    case Internal = 'internal';

    public function label(): string
    {
        return match($this) {
            self::Inbound => 'Inbound',
            self::Outbound => 'Outbound',
            self::Internal => 'Internal',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Inbound => 'blue',
            self::Outbound => 'orange',
            self::Internal => 'gray',
        };
    }
}
