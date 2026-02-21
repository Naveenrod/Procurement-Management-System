<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case Available = 'available';
    case InUse = 'in_use';
    case Maintenance = 'maintenance';
    case Retired = 'retired';

    public function label(): string
    {
        return match($this) {
            self::Available => 'Available',
            self::InUse => 'In Use',
            self::Maintenance => 'Maintenance',
            self::Retired => 'Retired',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Available => 'green',
            self::InUse => 'blue',
            self::Maintenance => 'yellow',
            self::Retired => 'gray',
        };
    }
}
