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
}
