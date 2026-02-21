<?php
namespace App\Enums;

enum GoodsReceiptStatus: string {
    case Pending = 'pending';
    case Partial = 'partial';
    case Complete = 'complete';
    case Cancelled = 'cancelled';

    public function label(): string {
        return match($this) {
            GoodsReceiptStatus::Pending => 'Pending',
            GoodsReceiptStatus::Partial => 'Partial',
            GoodsReceiptStatus::Complete => 'Complete',
            GoodsReceiptStatus::Cancelled => 'Cancelled',
        };
    }

    public function color(): string {
        return match($this) {
            GoodsReceiptStatus::Pending => 'yellow',
            GoodsReceiptStatus::Partial => 'blue',
            GoodsReceiptStatus::Complete => 'green',
            GoodsReceiptStatus::Cancelled => 'red',
        };
    }
}
