<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiaryResource extends JsonResource
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
            'beneficiary_name' => $this->beneficiary_name,
            'account_number' => $this->account_number,
            'bank_name' => $this->bank_name,
            'amount' => number_format($this->amount, 2, '.', ''),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
