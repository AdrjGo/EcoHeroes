<?php

namespace App\Enums;

enum TipoResiduo: string
{
    case ORGANICO = 'organico';
    case INORGANICO_RECICLABLE = 'inorganico_reciclable';
    case INORGANICO_NO_RECICLABLE = 'inorganico_no_reciclable';
    case PELIGROSO = 'peligroso';
    case SANITARIO = 'sanitario';
    case ELECTRONICO = 'electronico';
    case CONSTRUCCION = 'construccion';
    case NUCLEAR = 'nuclear';

    public function descripcion(): string
    {
        return match($this) {
            self::ORGANICO => 'Residuos orgánicos: Restos de alimentos, hojas secas, cáscaras',
            self::INORGANICO_RECICLABLE => 'Residuos inorgánicos reciclables: Plásticos, vidrio, papel, cartón, metales',
            self::INORGANICO_NO_RECICLABLE => 'Residuos inorgánicos no reciclables: Cerámicas, escombros, empaques multicapa',
            self::PELIGROSO => 'Residuos peligrosos: Pilas, baterías, productos químicos, aceites',
            self::SANITARIO => 'Residuos sanitarios o biomédicos: Pañales, gasas, jeringas',
            self::ELECTRONICO => 'Residuos electrónicos: Celulares, computadores, electrodomésticos',
            self::CONSTRUCCION => 'Residuos de construcción y demolición: Escombros, ladrillos, concreto, madera',
            self::NUCLEAR => 'Residuos nucleares: Materiales radiactivos',
        };
    }

    
}

