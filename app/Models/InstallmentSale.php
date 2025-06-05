<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentSale extends Model
{
    protected $fillable = [
        'sale_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}