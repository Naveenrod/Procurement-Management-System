<?php
namespace App\Enums;

enum RfqStatus: string {
    case Draft = 'draft';
    case Published = 'published';
    case Closed = 'closed';
    case Awarded = 'awarded';
    case Cancelled = 'cancelled';

    public function label(): string {
        return match($this) {
            RfqStatus::Draft => 'Draft',
            RfqStatus::Published => 'Published',
            RfqStatus::Closed => 'Closed',
            RfqStatus::Awarded => 'Awarded',
            RfqStatus::Cancelled => 'Cancelled',
        };
    }

    public function color(): string {
        return match($this) {
            RfqStatus::Draft => 'gray',
            RfqStatus::Published => 'blue',
            RfqStatus::Closed => 'yellow',
            RfqStatus::Awarded => 'green',
            RfqStatus::Cancelled => 'red',
        };
    }
}
