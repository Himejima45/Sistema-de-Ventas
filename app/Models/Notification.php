<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'read', 'link', 'employee_id'];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
