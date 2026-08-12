<?php

namespace App;

enum OrderByEnum: string
{
    case VIEWS = 'views';
    case SELLING = 'selling';
    case PRICE = 'price';
    case PRICE_DESC = 'price_desc';
    case NEWEST = 'newest';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
