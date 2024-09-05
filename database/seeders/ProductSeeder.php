<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Provider;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'Kit empacadura Gn-125',
                'cost' => 2,
                'price' => 2,
                'barcode' => '150',
                'stock' => 10,
                'min_stock' => '3',
                'category_id' => '1',
                'image' => 'image.png'
            ],
            [
                'name' => 'Cadena tiempo owen',
                'cost' => 2,
                'price' => 5,
                'barcode' => '317',
                'stock' => 6,
                'min_stock' => '2',
                'category_id' => '2',
                'image' => 'img.png'
            ],
            [
                'name' => 'Sin espiche',
                'cost' => 2,
                'price' => 2,
                'barcode' => '96',
                'stock' => 5,
                'min_stock' => '2',
                'category_id' => '3',
                'image' => 'img.png'
            ],
            [
                'name' => 'Terminales',
                'cost' => 1,
                'price' => 2,
                'barcode' => '517',
                'stock' => 10,
                'min_stock' => '2',
                'category_id' => '4',
                'image' => 'img.png'
            ]
        ];

        foreach ($products as $product) {
            $new_product = array_merge($product, ['provider_id' => Provider::first()->id]);
            Product::create($new_product);
        }
    }
}
