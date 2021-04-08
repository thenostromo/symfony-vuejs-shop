<?php

namespace App\DataProvider;

class ProductDataProvider
{
    public static function getSizeList()
    {
        return [
            's' => 'Small',
            'm' => 'Medium',
            'l' => 'Large',
            'el' => 'Extra Large',
        ];
    }
}
