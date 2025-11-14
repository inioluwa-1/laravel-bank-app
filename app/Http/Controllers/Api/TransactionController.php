<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    /**
     * Deposit funds into user account.
     */
    public function deposit(DepositRequest $request)
    {
        $user = $request->user();

        DB::beginTransaction();
        try {
            // Increment user balance
            $user->increment('balance', $request->amount);

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'sender_account_number' => $request->sender_account_number,
                'sender_name' => $request->sender_name ?? 'External Deposit',
                'beneficiary_account_number' => $user->account_number,
                'beneficiary_name' => $user->name,
                'status' => 'completed',
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Deposit successful',
                'transaction' => new TransactionResource($transaction),
                'new_balance' => number_format($user->fresh()->balance, 2, '.', ''),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Deposit failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Transfer funds to beneficiary.
     */
    public function transfer(TransferRequest $request)
    {
        $user = $request->user();

        // Verify transaction PIN
        if (!Hash::check($request->transaction_pin, $user->transaction_pin)) {
            return response()->json([
                'error' => 'Invalid transaction PIN',
            ], 401);
        }

        // Check sufficient balance
        if ($user->balance < $request->amount) {
            return response()->json([
                'error' => 'Insufficient funds',
            ], 400);
        }

        // Check if transferring to self
        if ($user->account_number === $request->beneficiary_account_number) {
            return response()->json([
                'error' => 'Cannot transfer to your own account',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Deduct from sender
            $user->decrement('balance', $request->amount);

            // Find beneficiary user (optional - for internal transfers)
            $beneficiaryUser = User::where('account_number', $request->beneficiary_account_number)->first();
            
            // If internal transfer, credit beneficiary
            if ($beneficiaryUser) {
                $beneficiaryUser->increment('balance', $request->amount);
            }

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'transfer',
                'amount' => $request->amount,
                'beneficiary_id' => $request->beneficiary_id,
                'beneficiary_account_number' => $request->beneficiary_account_number,
                'beneficiary_name' => $request->beneficiary_name ?? $beneficiaryUser?->name ?? 'Unknown',
                'sender_account_number' => $user->account_number,
                'sender_name' => $user->name,
                'status' => 'completed',
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Transfer successful',
                'transaction' => new TransactionResource($transaction),
                'new_balance' => number_format($user->fresh()->balance, 2, '.', ''),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Transfer failed. Please try again.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transaction history for authenticated user.
     */
    public function index(Request $request)
    {
        $query = $request->user()->transactions();

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->with('beneficiary')
            ->latest('created_at')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'transactions' => TransactionResource::collection($transactions),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }

    /**
     * Get a single transaction.
     */
    public function show(Request $request, $id)
    {
        $transaction = $request->user()
            ->transactions()
            ->with('beneficiary')
            ->findOrFail($id);

        return response()->json([
            'transaction' => new TransactionResource($transaction),
        ]);
    }
}
