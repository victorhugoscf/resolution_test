<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'price', 'stock'];

    /**
     * Get the sale items associated with the product.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
