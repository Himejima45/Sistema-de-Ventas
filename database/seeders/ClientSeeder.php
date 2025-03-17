<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                'name' => 'Cliente Genérico',
                'last_name' => '',
                'document' => '999999999',
                'phone' => '00000000000',
                'address' => 'No aplica',
                'password' => Hash::make('cliente'),
                'email' => 'cliente@email.com'
            ],
            [
                'name' => 'Pablo',
                'last_name' => 'Diaz',
                'document' => '11456879',
                'phone' => '04168857552',
                'address' => 'La Victoria/La mora',
                'password' => Hash::make('cliente'),
                'email' => 'cliente1@email.com'
            ],
            [
                'name' => 'Aranza',
                'last_name' => 'Perez',
                'document' => '19486179',
                'phone' => '04241556324',
                'address' => 'La Victoria/Las mercedes',
                'password' => Hash::make('cliente'),
                'email' => 'cliente2@email.com'
            ],
            [
                'name' => 'Ingrid',
                'last_name' => 'Zamora',
                'document' => '28996331',
                'phone' => '04261220665',
                'address' => 'Cagua/Centro',
                'password' => Hash::make('cliente'),
                'email' => 'client3@email.com'
            ],
            [
                'name' => 'Jesus',
                'last_name' => 'Bonaire',
                'document' => '8444333',
                'phone' => '04141777554',
                'address' => 'La Victoria/La mora',
                'password' => Hash::make('cliente'),
                'email' => 'cliente4@email.com'
            ]
        ];

        foreach ($clients as $client) {
            User::create($client)
                ->assignRole('Client');
        }
    }
}
