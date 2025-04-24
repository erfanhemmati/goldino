<?php

namespace App\Enums;

enum OrderStatus: string
{
    case OPEN = 'OPEN';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}