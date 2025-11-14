<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user account is active
        if (isset($user->status) && $user->status === 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated. Please contact support.',
                'errors' => [],
            ], 403);
        }

        // Check if user account is suspended
        if (isset($user->status) && $user->status === 'suspended') {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended. Please contact support.',
                'errors' => [],
            ], 403);
        }

        return $next($request);
    }
}
