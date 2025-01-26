<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
  use HasFactory;

  protected $fillable = ['name', 'barcode', 'cost', 'price', 'stock', 'min_stock', 'image', 'category_id', 'provider_id', "warranty"];


  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function getImage()
  {
    $defaultImage = explode('.', $this->image)[1] === 'jpeg';
    $src = $defaultImage
      ? asset('/assets/products/' . $this->image)
      : asset('storage/products/' . $this->image);

    return $src;
  }

  public function getImagenAttribute()
  {
    $defaultImage = explode('.', $this->image)[1] === 'jpeg';
    return file_exists($defaultImage ? public_path() . '/assets/products' : 'storage/products/' . $this->image) ? $this->image : null;
  }

  public function purchases()
  {
    return $this->belongsToMany(Purchase::class, 'purchase_product')
      ->withPivot('quantity', 'price')
      ->withTimestamps();
  }

  public function provider()
  {
    return $this->belongsTo(Provider::class);
  }
}
