<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::create([
            'nombre' => 'test1',
            'dni' => '77777777',
            'telefono' => '987654321',
            'correo' => 'test1@gmail.com',
            'direccion' => 'av test, falsa esquina test',
            'fecha_nacimiento' => '2005-05-02',
        ]);

        Cliente::create([
            'nombre' => 'test2',
            'dni' => '88888888',
            'telefono' => '987654322',
            'correo' => 'test2@gmail.com',
            'direccion' => 'av test, falsa esquina test',
            'fecha_nacimiento' => '2004-08-03',
        ]);
    }
}
