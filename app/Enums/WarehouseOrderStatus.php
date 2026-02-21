<?php

namespace App\Enums;

enum WarehouseOrderStatus: string
{
    case Pending = 'pending';
    case Receiving = 'receiving';
    case Putaway = 'putaway';
    case Picking = 'picking';
    case Packing = 'packing';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Receiving => 'Receiving',
            self::Putaway => 'Putaway',
            self::Picking => 'Picking',
            self::Packing => 'Packing',
            self::Shipped => 'Shipped',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Receiving => 'blue',
            self::Putaway => 'indigo',
            self::Picking => 'orange',
            self::Packing => 'purple',
            self::Shipped => 'cyan',
            self::Completed => 'green',
            self::Cancelled => 'red',
        };
    }
}
