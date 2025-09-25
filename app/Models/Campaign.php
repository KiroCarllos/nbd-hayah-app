<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'current_amount',
        'images',
        'is_priority',
        'is_active',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'images' => 'array',
        'is_priority' => 'boolean',
        'is_active' => 'boolean',
        'end_date' => 'date',
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
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorite_campaigns');
    }

    public function favorites()
    {
        return $this->hasMany(UserFavoriteCampaign::class);
    }

    public function updates()
    {
        return $this->hasMany(CampaignUpdate::class);
    }

    public function latestUpdate()
    {
        return $this->hasOne(CampaignUpdate::class)->latest();
    }

    // Accessors & Mutators
    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount == 0) return 0;
        return min(100, ($this->current_amount / $this->target_amount) * 100);
    }

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePriority($query)
    {
        return $query->where('is_priority', true);
    }

    /**
     * Scope to get campaigns that can accept donations
     */
    public function scopeCanAcceptDonations($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })
            ->whereRaw('current_amount < target_amount');
    }

    // Helper methods
    public function isFavoritedBy($userId)
    {
        if (!$userId) {
            return false;
        }

        return UserFavoriteCampaign::where('user_id', $userId)
            ->where('campaign_id', $this->id)
            ->exists();
    }

    /**
     * Check if campaign is completed (reached 100% of target amount)
     */
    public function isCompleted()
    {
        return $this->current_amount >= $this->target_amount;
    }

    /**
     * Check if campaign has ended (past end_date)
     */
    public function hasEnded()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Check if campaign is inactive
     */
    public function isInactive()
    {
        return !$this->is_active;
    }

    /**
     * Check if campaign can accept donations
     */
    public function canAcceptDonations()
    {
        return $this->is_active && !$this->isCompleted() && !$this->hasEnded();
    }

    /**
     * Get campaign status text
     */
    public function getStatusText()
    {
        if (!$this->is_active) {
            return 'غير نشطة';
        }

        if ($this->hasEnded()) {
            return 'منتهية';
        }

        if ($this->isCompleted()) {
            return 'مكتملة';
        }

        return 'نشطة';
    }

    /**
     * Get campaign status badge class
     */
    public function getStatusBadgeClass()
    {
        if (!$this->is_active) {
            return 'bg-secondary';
        }

        if ($this->hasEnded()) {
            return 'bg-danger';
        }

        if ($this->isCompleted()) {
            return 'bg-success';
        }

        return 'bg-primary';
    }
}
