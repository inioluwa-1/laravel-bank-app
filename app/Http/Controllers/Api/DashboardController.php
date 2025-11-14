<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard data for a user.
     */
    public function show(Request $request, $userId)
    {
        // Ensure user can only view their own dashboard
        if ($request->user()->id != $userId) {
            return response()->json([
                'error' => 'Unauthorized access',
            ], 403);
        }

        $user = User::with('nextOfKin')->findOrFail($userId);

        // Get transaction statistics
        $totalDeposits = $user->transactions()
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->sum('amount');

        $totalTransfers = $user->transactions()
            ->where('type', 'transfer')
            ->where('status', 'completed')
            ->sum('amount');

        $totalTransactions = $user->transactions()
            ->where('status', 'completed')
            ->count();

        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with('beneficiary')
            ->latest('created_at')
            ->limit(5)
            ->get();

        // Get monthly statistics (current month)
        $monthlyDeposits = $user->transactions()
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $monthlyTransfers = $user->transactions()
            ->where('type', 'transfer')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Get beneficiaries count
        $beneficiariesCount = $user->beneficiaries()->count();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'account_number' => $user->account_number,
                'account_type' => $user->account_type,
                'balance' => number_format($user->balance, 2, '.', ''),
                'profile_picture' => $user->profile_picture,
            ],
            'statistics' => [
                'total_deposits' => number_format($totalDeposits, 2, '.', ''),
                'total_transfers' => number_format($totalTransfers, 2, '.', ''),
                'total_transactions' => $totalTransactions,
                'beneficiaries_count' => $beneficiariesCount,
                'monthly_deposits' => number_format($monthlyDeposits, 2, '.', ''),
                'monthly_transfers' => number_format($monthlyTransfers, 2, '.', ''),
            ],
            'recent_transactions' => TransactionResource::collection($recentTransactions),
        ]);
    }
}
