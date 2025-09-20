<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'title',
        'content',
        'type',
        'images',
        'is_important',
        'created_by',
    ];

    protected $casts = [
        'images' => 'array',
        'is_important' => 'boolean',
    ];

    // Accessor to ensure images is always an array
    public function getImagesAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function getTypeNameAttribute()
    {
        $types = [
            'general' => 'عام',
            'medical' => 'طبي',
            'financial' => 'مالي',
            'progress' => 'تقدم الحالة',
            'urgent' => 'عاجل',
        ];

        return $types[$this->type] ?? 'عام';
    }
}
