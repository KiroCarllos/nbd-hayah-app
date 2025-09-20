<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CampaignUpdateResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="تحديث حول الحالة"),
 *     @OA\Property(property="content", type="string", example="تم نقل المريض إلى المستشفى وحالته مستقرة"),
 *     @OA\Property(property="type", type="string", example="medical"),
 *     @OA\Property(property="type_name", type="string", example="طبي"),
 *     @OA\Property(property="is_important", type="boolean", example=true),
 *     @OA\Property(property="images", type="array", @OA\Items(type="string"), example={"http://example.com/update1.jpg"}),
 *     @OA\Property(property="creator", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
 * )
 */
class CampaignUpdateResource extends JsonResource
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
            'content' => $this->content,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'is_important' => (bool) $this->is_important,
            'images' => $images,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
