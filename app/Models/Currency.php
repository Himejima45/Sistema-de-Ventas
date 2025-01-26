<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends BaseModel
{
    use HasFactory;

    protected $fillable = ['value', 'last_update'];
}
