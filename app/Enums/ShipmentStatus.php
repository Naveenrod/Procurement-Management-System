<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case Pending = 'pending';
    case InTransit = 'in_transit';
    case Delivered = 'delivered';
    case Delayed = 'delayed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::InTransit => 'In Transit',
            self::Delivered => 'Delivered',
            self::Delayed => 'Delayed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::InTransit => 'blue',
            self::Delivered => 'green',
            self::Delayed => 'red',
            self::Cancelled => 'gray',
        };
    }
}
