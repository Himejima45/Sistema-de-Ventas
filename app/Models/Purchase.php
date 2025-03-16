<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends BaseModel
{
    use HasFactory;

    protected $fillable = ['cost', 'payed', 'provider', 'status', 'payment_type'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function provider_model()
    {
        return $this->hasOne(Provider::class, 'id', 'provider');
    }
}
