<?php

namespace App\DataProvider;

use App\Entity\Order;

class OrderDataProvider
{
    public static function getStatusList()
    {
        return [
            Order::STATUS_CREATED => 'Created',
            Order::STATUS_PROCESSED => 'Processing',
            Order::STATUS_COMPLECTED => 'Complected',
            Order::STATUS_DELIVERED => 'Delivered',
            Order::STATUS_DENIED => 'Denied',
        ];
    }
}
