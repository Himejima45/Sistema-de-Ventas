<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
  protected $fillable = [
    'name',
    'reference',
    'guard_name',
    'is_active',
  ];

  protected $casts = [
    'is_active' => 'boolean',
  ];

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }
}