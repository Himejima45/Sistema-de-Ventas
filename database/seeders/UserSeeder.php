<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'phone' => '04245687955',
            'email' => 'admin@email.com',
            'password' => bcrypt('admin')
        ])->assignRole('Admin');
        User::create([
            'name' => 'Empleado',
            'phone' => '04245687956',
            'email' => 'empleado@email.com',
            'password' => bcrypt('empleado')
        ])->assignRole('Employee');
    }
}
