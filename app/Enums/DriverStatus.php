<?php

namespace App\Enums;

enum DriverStatus: string
{
    case Available = 'available';
    case OnTrip = 'on_trip';
    case OffDuty = 'off_duty';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::Available => 'Available',
            self::OnTrip => 'On Trip',
            self::OffDuty => 'Off Duty',
            self::Suspended => 'Suspended',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Available => 'green',
            self::OnTrip => 'blue',
            self::OffDuty => 'gray',
            self::Suspended => 'red',
        };
    }
}
