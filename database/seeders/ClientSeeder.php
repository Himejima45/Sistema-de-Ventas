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
        User::create([
            'name' => 'Cliente',
            'last_name' => 'Genérico',
            'document' => '9999999999',
            'phone' => '00000000000',
            'address' => 'N/A',
            'password' => Hash::make('L71d%Y%~BT:mf>pK*RiK'),
            'email' => 'clientegenerico@motopartshm.com'
        ])
            ->assignRole('Cliente');
    }
}
