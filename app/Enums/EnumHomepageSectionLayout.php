<?php

namespace App\Enums;

enum EnumHomepageSectionLayout: string
{
    case Grid = 'grid';
    case Carousel = 'carousel';
    case Magazine = 'magazine';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::Grid->value => 'Grid (Rejilla de 6 artículos)',
            self::Carousel->value => 'Carrusel (Deslizante horizontal)',
            self::Magazine->value => 'Magazine (1 grande + 4 pequeños)',
        ];
    }
}

