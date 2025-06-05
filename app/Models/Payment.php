<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sale_id',
        'method',
        'total_amount',
        'down_payment',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}