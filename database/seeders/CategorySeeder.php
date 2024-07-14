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
        Category::create([
            'name'=> 'Motor',
            'image'=> "{{ asset('assets/img/image.jpg') }}"
        ]);
        Category::create([
            'name'=> 'Cadenas',
            'image'=> "{{ asset('assets/img/image.jpg') }}"
        ]);
        Category::create([
            'name'=> 'Tripas',
            'image'=> "{{ asset('assets/img/image.jpg') }}"
        ]);
        Category::create([
            'name'=> 'Electrico',
            'image'=> "{{ asset('assets/img/image.jpg') }}"
        ]);
    }
}
