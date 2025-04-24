<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coin_id',
        'total_amount',
        'available_amount',
        'locked_amount',
    ];

    protected $casts = [
        'total_amount'      => 'decimal:8',
        'available_amount'  => 'decimal:8',
        'locked_amount'     => 'decimal:8',
    ];

    /**
     * Get the user that owns the balance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coin that this balance is for.
     */
    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }
}
