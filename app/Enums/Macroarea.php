<?php

namespace App\Enums;

enum Macroarea: string
{
    case Africa = 'Africa';
    case IberianPeninsula = 'Iberian peninsula';
    case Germania = 'Germania';
    case DanubianProvinces = 'Danubian provinces';
    case Gallia = 'Gallia';
    case AlpineProvinces = 'Alpine provinces';
    case CorsicaSardiniaSicily = 'Corsica, Sardinia and Sicily';
    case Italy = 'Italy';
    case Britannia = 'Britannia';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
