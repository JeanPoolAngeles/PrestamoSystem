<?php

namespace Database\Seeders;

use App\Models\Forma;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Forma::insert([
            [
                'nombre' => 'EFECTIVO',
            ],
            [
                'nombre' => 'YAPE',
            ],
            [
                'nombre' => 'PLIN',
            ],
            [
                'nombre' => 'TARJETA',
            ],
            [
                'nombre' => 'TRANSFERENCIA',
            ],
        ]);
    }
}
