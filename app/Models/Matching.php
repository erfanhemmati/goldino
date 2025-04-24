<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matching extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'matching_order_id',
        'amount',
        'status'
    ];
    
    /**
     * Get the order that owns the matching record.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    /**
     * Get the matching order that owns the matching record.
     */
    public function matchingOrder()
    {
        return $this->belongsTo(Order::class, 'matching_order_id');
    }
} 