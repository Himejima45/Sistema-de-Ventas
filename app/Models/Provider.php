<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone', 'rif', 'document'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getHasProductsAttribute()
    {
        return $this->products()->count() > 0;
    }
}
