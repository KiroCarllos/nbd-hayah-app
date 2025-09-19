<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'is_anonymous',
        'message',
        'payment_method',
        'transaction_id',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that made the donation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
