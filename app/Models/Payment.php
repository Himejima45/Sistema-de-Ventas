<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'currency_id',
        'cash_usd',
        'cash_bs',
        'change_usd',
        'change_bs',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
