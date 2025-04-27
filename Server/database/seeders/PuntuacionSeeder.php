<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuntuacionSeeder extends Seeder
{
    public function run(): void
    {
        Puntuacion::create([
            'solicitud_id' => 1,
            'usuario_id' => 1,
            'recolector_id' => 1,
            'puntuacion' => 5,
            'comentario' => 'Excelente servicio',
            'estado' => 'activo',
        ]);
    }
}