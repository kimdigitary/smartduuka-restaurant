<?php

namespace App\Enums;

interface OrderType
{
    const DELIVERY = 5;
    const TAKEAWAY = 10;
    const POS = 15;
    const DINING_TABLE = 20;
    const CHEF_BOARD = 1;
    const COMPLETED = 2;
}
