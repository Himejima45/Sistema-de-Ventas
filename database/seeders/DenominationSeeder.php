<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Denomination;

class DenominationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Denomination::create([
            'type'=> 'DOLAR',
            'value'=> '100'
        ]);
        Denomination::create([
            'type'=> 'DOLAR',
            'value'=> '50'
        ]);
        Denomination::create([
            'type'=> 'DOLAR',
            'value'=> '20'
        ]);
        Denomination::create([
            'type'=> 'DOLAR',
            'value'=> '10'
        ]);
        Denomination::create([
            'type'=> 'DOLAR',
            'value'=> '5'
        ]);
        Denomination::create([
            'type'=> 'DOLAR',
            'value'=> '1'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '500'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '200'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '100'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '50'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '20'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '10'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '5'
        ]);
        Denomination::create([
            'type'=> 'BOLIVAR',
            'value'=> '1'
        ]);
        Denomination::create([
            'type'=> 'OTRO',
            'value'=> '0'
        ]);
    }
}
