<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NextOfKinRequest;
use App\Http\Requests\TransactionPinRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Get authenticated user details.
     */
    public function show(Request $request)
    {
        $user = $request->user()->load('nextOfKin');
        
        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        
        $user->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user->fresh()),
        ]);
    }

    /**
     * Upload profile picture.
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        $user->update(['profile_picture' => $path]);

        return response()->json([
            'message' => 'Profile picture uploaded successfully',
            'profile_picture_url' => Storage::url($path),
        ]);
    }

    /**
     * Create transaction PIN.
     */
    public function createTransactionPin(TransactionPinRequest $request)
    {
        $user = $request->user();

        if ($user->transaction_pin) {
            return response()->json([
                'error' => 'Transaction PIN already exists. Use update endpoint to change it.',
            ], 400);
        }

        $user->update([
            'transaction_pin' => Hash::make($request->transaction_pin),
        ]);

        return response()->json([
            'message' => 'Transaction PIN created successfully',
        ]);
    }

    /**
     * Update transaction PIN.
     */
    public function updateTransactionPin(TransactionPinRequest $request)
    {
        $user = $request->user();

        if (!$user->transaction_pin) {
            return response()->json([
                'error' => 'No transaction PIN found. Create one first.',
            ], 400);
        }

        // Verify current PIN
        if (!Hash::check($request->current_pin, $user->transaction_pin)) {
            return response()->json([
                'error' => 'Current transaction PIN is incorrect.',
            ], 401);
        }

        $user->update([
            'transaction_pin' => Hash::make($request->transaction_pin),
        ]);

        return response()->json([
            'message' => 'Transaction PIN updated successfully',
        ]);
    }

    /**
     * Add or update next of kin.
     */
    public function addNextOfKin(NextOfKinRequest $request)
    {
        $user = $request->user();

        $nextOfKin = $user->nextOfKin()->updateOrCreate(
            ['user_id' => $user->id],
            $request->validated()
        );

        return response()->json([
            'message' => 'Next of kin details saved successfully',
            'next_of_kin' => $nextOfKin,
        ]);
    }
}
