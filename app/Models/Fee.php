<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trade_id',
        'user_id',
        'amount',
        'percentage',
        'total_fee'
    ];
    
    /**
     * Get the trade that owns the fee.
     */
    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }
    
    /**
     * Get the user that owns the fee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 