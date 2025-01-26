<?php

namespace App\Models;

use App\Observers\GlobalModelObserver;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
  protected static function boot()
  {
    parent::boot();

    // Register the global observer
    static::observe(GlobalModelObserver::class);
  }
}
