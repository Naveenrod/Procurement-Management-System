<?php
namespace App\Enums;

enum Priority: string {
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string {
        return match($this) {
            Priority::Low => 'Low',
            Priority::Medium => 'Medium',
            Priority::High => 'High',
            Priority::Critical => 'Critical',
        };
    }

    public function color(): string {
        return match($this) {
            Priority::Low => 'green',
            Priority::Medium => 'blue',
            Priority::High => 'orange',
            Priority::Critical => 'red',
        };
    }
}
