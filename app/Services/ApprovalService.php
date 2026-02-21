<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ApprovalService
{
    public function submitForApproval(Model $model): void
    {
        $model->update(['status' => 'pending_approval']);
    }

    public function approve(Model $model, User $approver, ?string $notes = null): void
    {
        $data = ['status' => 'approved'];
        if (in_array('approved_by', $model->getFillable())) $data['approved_by'] = $approver->id;
        if (in_array('approved_at', $model->getFillable())) $data['approved_at'] = now();
        if ($notes && in_array('notes', $model->getFillable())) $data['notes'] = $notes;
        $model->update($data);
    }

    public function reject(Model $model, string $reason): void
    {
        $data = ['status' => 'rejected'];
        if (in_array('rejection_reason', $model->getFillable())) $data['rejection_reason'] = $reason;
        $model->update($data);
    }
}
