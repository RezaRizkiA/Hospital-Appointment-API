<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository 
{
    /**
     * Get all transactions with related doctor, specialist, hospital, and user data, paginated.
     */
    public function getAll()
    {
        return Transaction::with(['doctor', 'doctor.specialist', 'doctor.hospital', 'user'])
            ->latest()
            ->paginate(10);
    }

    /**
     * Get a transaction by ID with related doctor, specialist, hospital, and user data.
     */
    public function getByIdForManager(int $id)
    {
        return Transaction::with(['doctor', 'doctor.specialist', 'doctor.hospital', 'user'])
            ->findOrFail($id);
    }

    /**
     * Update the status of a transaction by ID.
     */
    public function updateStatus(int $id, string $status)
    {
        $transaction = $this->getByIdForManager($id);
        $transaction->update(['status' => $status]);
        return $transaction;
    }


    /**
     * Get all transactions for a specific user with related doctor, specialist, and hospital data, paginated.
     */
    public function getAllForUser(int $userId)
    {
        return Transaction::where('user_id', $userId)
            ->with(['doctor', 'doctor.specialist', 'doctor.hospital'])
            ->latest()
            ->paginate(10);
    }

    /**
     * Get a transaction by ID for a specific user with related doctor, specialist, and hospital data.
     */
    public function getByIdForUser(int $id, int $userId)
    {
        return Transaction::where('id', $id)
            ->where('user_id', $userId)
            ->with(['doctor', 'doctor.specialist', 'doctor.hospital'])
            ->firstOrFail();
    }

    /**
     * Create a new transaction.
     */
    public function create(array $data)
    {
        return Transaction::create($data);
    }

    /**
     * Check if a time slot is already booked for a specific doctor on a given date and time.
     */
    public function isTimeSlotBooked(int $doctorId, string $date, string $time)
    {
        return Transaction::where('doctor_id', $doctorId)
            ->whereData('started_at', $date)
            ->whereTime('time_at', $time)
            ->exists(); // true if slot is booked, false otherwise
    }
}
