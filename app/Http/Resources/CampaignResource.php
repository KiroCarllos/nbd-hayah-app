<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CampaignResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="مساعدة الأسر المحتاجة"),
 *     @OA\Property(property="description", type="string", example="حملة لمساعدة الأسر المحتاجة في رمضان"),
 *     @OA\Property(property="target_amount", type="number", format="float", example=10000.00),
 *     @OA\Property(property="current_amount", type="number", format="float", example=5000.00),
 *     @OA\Property(property="remaining_amount", type="number", format="float", example=5000.00),
 *     @OA\Property(property="progress_percentage", type="number", format="float", example=50.0),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="is_priority", type="boolean", example=false),
 *     @OA\Property(property="images", type="array", @OA\Items(type="string"), example={"http://example.com/image1.jpg", "http://example.com/image2.jpg"}),
 *     @OA\Property(property="creator", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="donations_count", type="integer", example=25),
 *     @OA\Property(property="updates_count", type="integer", example=5),
 *     @OA\Property(property="latest_update", ref="#/components/schemas/CampaignUpdateResource"),
 *     @OA\Property(property="is_favorited", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
 * )
 */
class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $images = [];
        if ($this->images) {
            $imageArray = is_string($this->images) ? json_decode($this->images, true) : $this->images;
            if (is_array($imageArray)) {
                $images = array_map(function ($image) {
                    return asset('storage/' . $image);
                }, $imageArray);
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'target_amount' => (float) $this->target_amount,
            'current_amount' => (float) $this->current_amount,
            'remaining_amount' => (float) $this->remaining_amount,
            'progress_percentage' => (float) $this->progress_percentage,
            'is_active' => (bool) $this->is_active,
            'end_date' => $this->end_date,
            'is_priority' => (bool) $this->is_priority,
            'images' => $images,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'donations_count' => $this->donations_count ?? $this->donations()->count(),
            'updates_count' => (int) $this->updates_count ?? (int) $this->updates()->count(),
//            'latest_update' => new CampaignUpdateResource($this->whenLoaded('latestUpdate')),
            'latest_updates' => CampaignUpdateResource::collection($this->whenLoaded('updates')),
            'is_favorited' => $this->when(
                auth('sanctum')->check(),
                function () {
                    return $this->favorites()->where('user_id', auth('sanctum')->id())->exists();
                },
                false
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
