<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeneficiaryRequest;
use App\Http\Resources\BeneficiaryResource;
use App\Models\Beneficiary;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    /**
     * List all beneficiaries for authenticated user.
     */
    public function index(Request $request)
    {
        $beneficiaries = $request->user()
            ->beneficiaries()
            ->latest()
            ->get();

        return response()->json([
            'beneficiaries' => BeneficiaryResource::collection($beneficiaries),
        ]);
    }

    /**
     * Add a new beneficiary.
     */
    public function store(BeneficiaryRequest $request)
    {
        $beneficiary = $request->user()->beneficiaries()->create($request->validated());

        return response()->json([
            'message' => 'Beneficiary added successfully',
            'beneficiary' => new BeneficiaryResource($beneficiary),
        ], 201);
    }

    /**
     * Update a beneficiary.
     */
    public function update(BeneficiaryRequest $request, $id)
    {
        $beneficiary = $request->user()
            ->beneficiaries()
            ->findOrFail($id);

        $beneficiary->update($request->validated());

        return response()->json([
            'message' => 'Beneficiary updated successfully',
            'beneficiary' => new BeneficiaryResource($beneficiary),
        ]);
    }

    /**
     * Delete a beneficiary.
     */
    public function destroy(Request $request, $id)
    {
        $beneficiary = $request->user()
            ->beneficiaries()
            ->findOrFail($id);

        $beneficiary->delete();

        return response()->json([
            'message' => 'Beneficiary deleted successfully',
        ]);
    }
}
