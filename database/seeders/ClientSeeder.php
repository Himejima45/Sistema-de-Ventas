<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'name'=> 'Pablo',
            'last_name'=> 'Diaz',
            'document'=> '11456879',
            'phone'=> '0416887552',
            'address'=> 'La Victoria/La mora'
        ]);

        Client::create([
            'name'=> 'Aranza',
            'last_name'=> 'Perez',
            'document'=> '19486179',
            'phone'=> '0424556324',
            'address'=> 'La Victoria/Las mercedes'
        ]);

        Client::create([
            'name'=> 'Ingrid',
            'last_name'=> 'Zamora',
            'document'=> '28996331',
            'phone'=> '0426122665',
            'address'=> 'Cagua/Centro'
        ]);

        Client::create([
            'name'=> 'Jesus',
            'last_name'=> 'Bonaire',
            'document'=> '8444333',
            'phone'=> '0414777554',
            'address'=> 'La Victoria/La mora'
        ]);

    }
}
