<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::create([
            'name' => 'Distribuidora RR',
            'address' => 'Calle Colón',
            'phone' => '04125612112',
            'rif' => '1020102011',
            'document' => 'J'
        ]);
    }
}
