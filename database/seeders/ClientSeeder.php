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
        $clients = [
            [
                'name' => 'Pablo',
                'last_name' => 'Diaz',
                'document' => '11456879',
                'phone' => '04168857552',
                'address' => 'La Victoria/La mora'
            ],
            [
                'name' => 'Aranza',
                'last_name' => 'Perez',
                'document' => '19486179',
                'phone' => '04241556324',
                'address' => 'La Victoria/Las mercedes'
            ],
            [
                'name' => 'Ingrid',
                'last_name' => 'Zamora',
                'document' => '28996331',
                'phone' => '04261220665',
                'address' => 'Cagua/Centro'
            ],
            [
                'name' => 'Jesus',
                'last_name' => 'Bonaire',
                'document' => '8444333',
                'phone' => '04141777554',
                'address' => 'La Victoria/La mora'
            ]
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
