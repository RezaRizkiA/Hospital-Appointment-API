<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository 
{
    public function getAll()
    {
        return Transaction::with(['doctor', 'doctor.specialist', 'doctor.hospital', 'user'])
            ->latest()
            ->paginate(10);
    }

    public function getAllForUser(int $userId)
    {
        return Transaction::where('user_id', $userId)
            ->with(['doctor', 'doctor.specialist', 'doctor.hospital'])
            ->latest()
            ->paginate(10);
    }
}
