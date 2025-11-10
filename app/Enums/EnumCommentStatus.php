<?php

namespace App\Enums;

enum EnumCommentStatus: string
{
    case Approved = 'approved';
    case Pending = 'pending';
    case Spam = 'spam';

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
            self::Approved->value => 'Aprobado',
            self::Pending->value => 'Pendiente',
            self::Spam->value => 'Correo no deseado',
        ];
    }
}

