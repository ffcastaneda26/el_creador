<?php

namespace App\Enums;

enum UnitType: string
{

    case Longitud = 'Longitud';
    case Area = 'Área';
    case Masa = 'Masa';
    case Volumen = 'Volumen';
    case Tiempo = 'Tiempo';
    case Temperatura = 'Temperatura';
    case Luminosidad = 'Luminosidad';
    case Corriente = 'Corriente';
    case Voltaje = 'Voltaje';
    case Potencia = 'Potencia';
    case Presión = 'Presión';
}
