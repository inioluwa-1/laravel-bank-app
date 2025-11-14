<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class VerifyTransactionPin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user has set transaction PIN
        if (!$user->transaction_pin) {
            return response()->json([
                'success' => false,
                'message' => 'Please set up your transaction PIN first',
                'errors' => ['transaction_pin' => ['Transaction PIN not set']],
            ], 400);
        }

        // Check if transaction PIN is provided in request
        if (!$request->has('transaction_pin')) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction PIN is required',
                'errors' => ['transaction_pin' => ['Transaction PIN is required']],
            ], 400);
        }

        // Verify transaction PIN
        if (!Hash::check($request->transaction_pin, $user->transaction_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid transaction PIN',
                'errors' => ['transaction_pin' => ['Invalid transaction PIN']],
            ], 401);
        }

        return $next($request);
    }
}
