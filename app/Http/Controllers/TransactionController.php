<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(
        TransactionService $transactionService
    ) {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = $this->transactionService->getAll();
        // return response()->json(TransactionResource::collection($transactions));
        return TransactionResource::collection($transactions);
    }

    public function show(int $id)
    {
        try {
            //code...
            $transaction = $this->transactionService->getByIdForManager($id);
            return response()->json(new TransactionResource($transaction));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        try {
            $transaction = $this->transactionService->updateStatus($id, $validated['status']);
            return response()->json([
                'message' => 'Transaction status updated successfully.',
                'data' => new TransactionResource($transaction),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }
    }

    // public function store(TransactionRequest $request)
    // {
    //     $data = $request->validated();
    //     $transaction = $this->transactionService->create($data);
    //     return response()->json(new TransactionResource($transaction), 201);
    // }
}
