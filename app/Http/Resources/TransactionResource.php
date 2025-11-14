<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'amount' => number_format($this->amount, 2, '.', ''),
            'beneficiary_account_number' => $this->beneficiary_account_number,
            'beneficiary_name' => $this->beneficiary_name,
            'sender_account_number' => $this->sender_account_number,
            'sender_name' => $this->sender_name,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'beneficiary' => new BeneficiaryResource($this->whenLoaded('beneficiary')),
        ];
    }
}
