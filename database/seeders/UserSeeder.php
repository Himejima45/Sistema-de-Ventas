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
            'name'=> 'Admin',
            'phone'=> '04245687955',
            'email'=> 'admin@email.com',
            'profile'=> 'ADMIN',
            'status'=> 'ACTIVE',
            'password'=> bcrypt('admin')
        ]);
    }   
}
