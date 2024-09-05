<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'last_name', 'document', 'phone', 'address'];

    public function Sale()
    {
        $this->hasMany(Sale::class);
    }
}
