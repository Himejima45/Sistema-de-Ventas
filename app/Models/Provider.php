<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone', 'rif', 'document'];
}
