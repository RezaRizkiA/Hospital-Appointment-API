<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyOrderController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $userId = Auth::id();
        $transaction = $this->transactionService->getAllForUser($userId);
        // return response()->json(TransactionResource::collection($transaction));
        return TransactionResource::collection($transaction);
    }

    public function show(int $id)
    {
        $userId = Auth::id();
        try {
            $transaction = $this->transactionService->getByIdForUser($id, $userId);
            return response()->json(new TransactionResource($transaction));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }
    }

    public function store(TransactionRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $transaction = $this->transactionService->create($validated);
            return response()->json(new TransactionResource($transaction), 201);
        } catch (MassAssignmentException $e) {
            return response()->json([
                'message' => 'Failed to create transaction.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
