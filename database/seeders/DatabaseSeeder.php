<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Administrador', 'reference' => 'admin']);
        Role::create(['name' => 'Empleado', 'reference' => 'employee']);
        Role::create(['name' => 'Cliente', 'reference' => 'client']);

        $this->call(CategorySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClientSeeder::class);
    }
}
