<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'total_amount',
        'status',
        'sale_date',
    ];   
    protected $casts = [
        'sale_date' => 'datetime',
        'due_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_items') 
                    ->withPivot('quantity', 'product_id', 'unit_price', 'subtotal')
                    ->withTimestamps();
    }

    public function installments()
    {
        return $this->hasMany(InstallmentSale::class, 'sale_id');
    }
}
