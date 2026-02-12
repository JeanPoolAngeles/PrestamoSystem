<?php

namespace Database\Seeders;

use App\Models\Compania;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Compania::create([
            'nombre' => 'MARKET SYSTEM',
            'correo' => 'market@gmail.com',
            'telefono' => '929146086',
            'RUC' => '  10000000000',
            'direccion' => 'Av San Carlos falso, Huancayo - Peru - falso',
        ]);
    }
}
