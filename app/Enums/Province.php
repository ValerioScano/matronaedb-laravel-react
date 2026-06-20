<?php

namespace App\Enums;

enum Province: string
{
    case MauretaniaTingitana = 'Mauretania tingitana';
    case MauretaniaCasarensis = 'Mauretania casarensis';
    case Numidia = 'Numidia';
    case AfricaProconsularia = 'Africa proconsularia';
    case Baetica = 'Baetica';
    case Lusitania = 'Lusitania';
    case HispaniaTarraconensis = 'Hispania tarraconensis';
    case Aquitania = 'Aquitania';
    case GalliaNarbonensis = 'Gallia narbonensis';
    case GalliaLugdunensis = 'Gallia lugdunensis';
    case Belgica = 'Belgica';
    case BritanniaInferior = 'Britannia inferior';
    case BritanniaSuperior = 'Britannia superior';
    case GermaniaInferior = 'Germania inferior';
    case GermaniaSuperior = 'Germania superior';
    case Raetia = 'Raetia';
    case Noricum = 'Noricum';
    case PannoniaSuperior = 'Pannonia superior';
    case PannoniaInferior = 'Pannonia inferior';
    case Dalmatia = 'Dalmatia';
    case AlpesCottiae = 'Alpes cottiae';
    case AlpesGraiae = 'Alpes graiae';
    case AlpesMarittimae = 'Alpes marittimae';
    case AlpesPoeninae = 'Alpes poeninae';
    case RegioI = 'Regio I';
    case RegioII = 'Regio II';
    case RegioIII = 'Regio III';
    case RegioIV = 'Regio IV';
    case RegioV = 'Regio V';
    case RegioVI = 'Regio VI';
    case RegioVII = 'Regio VII';
    case RegioVIII = 'Regio VIII';
    case RegioIX = 'Regio IX';
    case RegioX = 'Regio X';
    case Sicilia = 'Sicilia';
    case Sardegna = 'Sardegna';
    case Corsica = 'Corsica';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
