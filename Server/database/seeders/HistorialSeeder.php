<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorialSeeder extends Seeder
{
    public function run(): void
    {
        Historial::create([
            'usuario_id' => 1,
            'solicitud_id' => 1,
            'tipo_evento' => 'solicitud_creada',
            'detalle' => 'La solicitud fue creada por el usuario',
            'fecha' => now(),
        ]);
    }
}