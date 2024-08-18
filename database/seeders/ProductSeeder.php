<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name'=> 'Kit empacadura Gn-125',
            'cost'=> 2,
            'price'=> 2,
            'barcode'=> '150',
            'stock'=> 10,
            'alerts'=> '3',
            'category_id'=> '1',
            'image'=> 'image.png'
        ]);
        Product::create([
            'name'=> 'Cadena tiempo owen',
            'cost'=> 2,
            'price'=> 5,
            'barcode'=> '317',
            'stock'=> 6,
            'alerts'=> '2',
            'category_id'=> '2',
            'image'=> 'img.png'
        ]);
        Product::create([
            'name'=> 'Sin espiche',
            'cost'=> 2,
            'price'=> 2,
            'barcode'=> '96',
            'stock'=> 5,
            'alerts'=> '2',
            'category_id'=> '3',
            'image'=> 'img.png'
        ]);
        Product::create([
            'name'=> 'Terminales',
            'cost'=> 1,
            'price'=> 2,
            'barcode'=> '517',
            'stock'=> 10,
            'alerts'=> '2',
            'category_id'=> '4',
            'image'=> 'img.png'
        ]);
    }
}
