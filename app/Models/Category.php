<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','image'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImagenAttribute()
    {
       if(file_exists('storage/categories/' . $this->image))
         return $this->image;
       else
         return 'image.jpg';
    }    

}