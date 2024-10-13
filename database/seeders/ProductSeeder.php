<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Provider;
use Nette\Utils\Random;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::count();
        $products = [
            [
                'name' => 'Pistón',
                'cost' => Random::generate('2', '0-9'),
                'price' => Random::generate('2', '0-9'),
                'barcode' => Random::generate('3', '0-9'),
                'stock' => Random::generate('2', '0-9'),
                'min_stock' => Random::generate('1', '0-9'),
                'warranty' => Random::generate('2', '0-9'),
                'category_id' => random_int(1, $categories),
                'image' => 'Pistón.jpeg',
            ],
            [
                'name' => 'Anillos',
                'cost' => Random::generate('2', '0-9'),
                'price' => Random::generate('2', '0-9'),
                'barcode' => Random::generate('3', '0-9'),
                'stock' => Random::generate('2', '0-9'),
                'min_stock' => Random::generate('1', '0-9'),
                'warranty' => Random::generate('2', '0-9'),
                'category_id' => random_int(1, $categories),
                'image' => 'Anillos.jpeg',
            ],
            [
                'name' => 'Bujía',
                'cost' => Random::generate('2', '0-9'),
                'price' => Random::generate('2', '0-9'),
                'barcode' => Random::generate('3', '0-9'),
                'stock' => Random::generate('2', '0-9'),
                'min_stock' => Random::generate('1', '0-9'),
                'warranty' => Random::generate('2', '0-9'),
                'category_id' => random_int(1, $categories),
                'image' => 'BujíaD8.jpeg',
            ],
            [
                'name' => 'CDI Jaguar',
                'cost' => Random::generate('2', '0-9'),
                'price' => Random::generate('2', '0-9'),
                'barcode' => Random::generate('3', '0-9'),
                'stock' => Random::generate('2', '0-9'),
                'min_stock' => Random::generate('1', '0-9'),
                'warranty' => Random::generate('2', '0-9'),
                'image' => 'CDIjaguar.jpeg',
                'category_id' => random_int(1, $categories),
            ],
            [
                'name' => 'Retrovisor Beta',
                'cost' => Random::generate('2', '0-9'),
                'price' => Random::generate('2', '0-9'),
                'barcode' => Random::generate('3', '0-9'),
                'stock' => Random::generate('2', '0-9'),
                'min_stock' => Random::generate('1', '0-9'),
                'warranty' => Random::generate('2', '0-9'),
                'category_id' => random_int(1, $categories),
                'image' => 'RetrovisorBera.jpeg',
            ],
            [
                'name' => 'Socate de Faro',
                'cost' => Random::generate('2', '0-9'),
                'price' => Random::generate('2', '0-9'),
                'barcode' => Random::generate('3', '0-9'),
                'stock' => Random::generate('2', '0-9'),
                'min_stock' => Random::generate('1', '0-9'),
                'warranty' => Random::generate('2', '0-9'),
                'category_id' => random_int(1, $categories),
                'image' => 'SocateDeFaro.jpeg',
            ],
        ];

        foreach ($products as $product) {
            $new_product = array_merge($product, ['provider_id' => Provider::first()->id]);
            Product::create($new_product);
        }
    }
}
