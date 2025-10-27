<?php

namespace App\Services;

use App\Repositories\AuthRepository;

class TransactionService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
}