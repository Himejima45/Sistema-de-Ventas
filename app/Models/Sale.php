<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['total', 'items', 'cash', 'change', 'bs', 'status', 'client_id', 'user_id', 'currency_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function products()
    {
        return $this->hasMany(SaleDetails::class, 'sale_id', 'id');
    }

    public function getTotalProducts()
    {
        $products = $this->products;
        $total = 0;

        foreach ($products as $p) {
            $total += $p->quantity;
        }
        return $total;
    }
}
