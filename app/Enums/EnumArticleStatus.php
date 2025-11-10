<?php

namespace App\Enums;

enum EnumArticleStatus: string
{
    case Published = 'published';
    case Draft = 'draft';
    case Scheduled = 'scheduled';

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
            self::Published->value => 'Publicado',
            self::Draft->value => 'Borrador',
            self::Scheduled->value => 'Programado',
        ];
    }
}

