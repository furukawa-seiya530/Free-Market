<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sell extends Model
{
    use HasFactory;

    protected $table = 'sell';

    protected $fillable = [
        'user_id',
        'image',
        'category',
        'status',
        'name',
        'brand',
        'description',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class, 'sell_id');
    }

    public function order()
    {
        return $this->hasOne(\App\Models\Order::class, 'sell_id');
    }

    public function getIsSoldAttribute()
    {
        return $this->order()->exists();
    }
}
