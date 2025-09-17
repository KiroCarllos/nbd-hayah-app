<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DonationResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="amount", type="number", format="float", example=100.00),
 *     @OA\Property(property="is_anonymous", type="boolean", example=false),
 *     @OA\Property(property="status", type="string", example="completed"),
 *     @OA\Property(property="payment_method", type="string", example="wallet"),
 *     @OA\Property(property="transaction_id", type="string", nullable=true, example="TXN123456"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="campaign", ref="#/components/schemas/CampaignResource"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
 * )
 */
class DonationResource extends JsonResource
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
            'amount' => (float) $this->amount,
            'is_anonymous' => (bool) $this->is_anonymous,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'user' => $this->when(
                !$this->is_anonymous && $this->relationLoaded('user'),
                new UserResource($this->user)
            ),
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
