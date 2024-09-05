<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = ['Motor', 'Cadenas', 'Tripas', 'Eléctrico'];

        foreach ($options as $option) {

            Category::create(['name' => $option]);
        }
    }
}
