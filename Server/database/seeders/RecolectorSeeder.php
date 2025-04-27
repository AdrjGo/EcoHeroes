<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecolectorSeeder extends Seeder
{
    public function run(): void
    {
        Recolector::create([
            'nombre' => 'Carlos',
            'apellido' => 'Ramirez',
            'ci' => '12345678',
            'telefono' => '70123456',
            'email' => 'carlos@correo.com',
            'direccion' => 'Av. Las Palmas',
            'licencia' => 'B',
            'estado' => 'activo',
        ]);
    }
}
