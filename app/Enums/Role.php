<?php

namespace App\Enums;

interface Role
{
    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const CUSTOMER = 3;
    const WAITER = 4;
    const CHEF = 5;
    const BRANCH_MANAGER = 6;
    const POS_OPERATOR = 7;
    const STUFF = 8;
}
