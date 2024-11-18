<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['cost', 'payed', 'status', 'payment_type'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
