<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // ← ✅ これが必要です！

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'sell_id',
    'payment_method',
    'postal_code',
    'address',
    'building',
];

    public function item()
    {
        return $this->belongsTo(Sell::class, 'sell_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

}
