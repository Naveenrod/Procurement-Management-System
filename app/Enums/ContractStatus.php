<?php
namespace App\Enums;

enum ContractStatus: string {
    case Draft = 'draft';
    case Active = 'active';
    case Expired = 'expired';
    case Terminated = 'terminated';

    public function label(): string {
        return match($this) {
            ContractStatus::Draft => 'Draft',
            ContractStatus::Active => 'Active',
            ContractStatus::Expired => 'Expired',
            ContractStatus::Terminated => 'Terminated',
        };
    }

    public function color(): string {
        return match($this) {
            ContractStatus::Draft => 'gray',
            ContractStatus::Active => 'green',
            ContractStatus::Expired => 'yellow',
            ContractStatus::Terminated => 'red',
        };
    }
}
