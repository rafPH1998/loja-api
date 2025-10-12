<?php

namespace App;

enum OrderByEnum: string
{
    case VIEWS = 'views';
    case SELLING = 'selling';
    case PRICE = 'price';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
