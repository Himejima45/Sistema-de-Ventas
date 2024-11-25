<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Employee']);
        Role::create(['name' => 'Client']);
        User::create([
            'name' => 'Admin',
            'phone' => '04245687955',
            'email' => 'admin@email.com',
            'password' => bcrypt('admin')
        ])->assignRole('Admin');
    }
}
