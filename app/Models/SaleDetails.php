<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetails extends BaseModel
{
    use HasFactory;

    protected $fillable = ['price', 'quantity', 'product_id', 'sale_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
