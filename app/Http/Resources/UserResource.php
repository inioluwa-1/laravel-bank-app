<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'unique_user_id' => $this->unique_user_id,
            'name' => $this->name,
            'email' => $this->email,
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'balance' => number_format($this->balance, 2, '.', ''),
            'has_transaction_pin' => !empty($this->transaction_pin),
            'profile_picture' => $this->profile_picture,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'next_of_kin' => new NextOfKinResource($this->whenLoaded('nextOfKin')),
        ];
    }
}
