<?php

namespace Database\Seeders;

use App\Libraries\AppLibrary;
use App\Models\Menu;
use Illuminate\Database\Seeder;


class MenuTableSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name'       => 'Dashboard',
                'language'   => 'dashboard',
                'url'        => 'dashboard',
                'icon'       => 'lab lab-dashboard',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
//            [
//                'name'       => 'Items',
//                'language'   => 'items',
//                'url'        => 'items',
//                'icon'       => 'lab lab-items',
//                'priority'   => 100,
//                'status'     => 1,
//                'created_at' => now(),
//                'updated_at' => now()
//
//            ],
//
//            [
//                'name'       => 'Ingredients',
//                'language'   => 'ingredients',
//                'url'        => 'ingredients',
//                'icon'       => 'lab lab-items',
//                'priority'   => 100,
//                'status'     => 1,
//                'created_at' => now(),
//                'updated_at' => now()
//
//            ],
//            [
//                'name'       => 'Dining Tables',
//                'language'   => 'dining_tables',
//                'url'        => 'dining-tables',
//                'icon'       => 'lab lab-dining-table',
//                'priority'   => 100,
//                'status'     => 1,
//                'created_at' => now(),
//                'updated_at' => now()
//
//            ],
            [
                'name'       => 'Items & Ingredients',
                'language'   => 'menus',
                'url'        => '#',
                'icon'       => 'lab lab-pos',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Menu Items',
                        'url'        => 'items',
                        'language'   => 'items',
                        'icon'       => 'lab lab-pos',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ],
                    [
                        'name'       => 'Ingredients & Stock',
                        'language'   => 'ingredients_and_stock',
                        'url'        => 'ingredients_and_stock',
                        'icon'       => 'lab lab-pos-orders',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ],
            ],
            [
                'name'       => 'Pos & Orders',
                'language'   => 'pos_and_orders',
                'url'        => '#',
                'icon'       => 'lab lab-pos',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'POS',
                        'url'        => 'pos',
                        'language'   => 'pos',
                        'icon'       => 'lab lab-pos',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ],
                    [
                        'name'       => 'POS Orders',
                        'language'   => 'pos_orders',
                        'url'        => 'pos-orders',
                        'icon'       => 'lab lab-pos-orders',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Table Orders',
                        'language'   => 'table_orders',
                        'url'        => 'table-orders',
                        'icon'       => 'lab lab-reserve-line',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ]
                ],
            ],

            [
                'name'       => 'Kitchen',
                'language'   => 'kitchen',
                'url'        => '#',
                'icon'       => 'lab lab-pos',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Chef Board',
                        'url'        => 'kitchen-orders',
                        'language'   => 'pos',
                        'icon'       => 'lab lab-pos',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ],
                    [
                        'name'       => 'Completed Orders',
                        'url'        => 'kitchen-orders/completed',
                        'language'   => 'pos',
                        'icon'       => 'lab lab-pos',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                ],
            ],
            [
                'name'       => 'Promo',
                'language'   => 'promo',
                'url'        => '#',
                'icon'       => 'lab ',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Offers',
                        'language'   => 'offers',
                        'url'        => 'offers',
                        'icon'       => 'lab lab-offers',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ]
                ]
            ],
            [
                'name'       => 'Expenses & Categories',
                'language'   => 'expenses',
                'url'        => '#',
                'icon'       => 'lab lab-item',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Categories',
                        'language'   => 'categories',
                        'url'        => 'categories',
                        'icon'       => 'lab lab-line-items',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ],
                    [
                        'name'       => 'Expenses',
                        'url'        => 'expenses',
                        'language'   => 'expenses',
                        'icon'       => 'lab lab-line-add-purchase',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                ]
            ],
            [
                'name'       => 'Users',
                'language'   => 'users',
                'url'        => '#',
                'icon'       => 'lab ',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Administrators',
                        'language'   => 'administrators',
                        'url'        => 'administrators',
                        'icon'       => 'lab lab-administrators',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Customers',
                        'language'   => 'customers',
                        'url'        => 'customers',
                        'icon'       => 'lab lab-customers',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Employees',
                        'language'   => 'employees',
                        'url'        => 'employees',
                        'icon'       => 'lab lab-employee',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                ]
            ],
            [
                'name'       => 'Accounts',
                'language'   => 'accounts',
                'url'        => '#',
                'icon'       => 'lab ',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Transactions',
                        'language'   => 'transactions',
                        'url'        => 'transactions',
                        'icon'       => 'lab lab-transactions',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ]
                ]
            ],
            [
                'name'       => 'Reports',
                'language'   => 'reports',
                'url'        => '#',
                'icon'       => 'lab ',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Sales Report',
                        'language'   => 'sales_report',
                        'url'        => 'sales-report',
                        'icon'       => 'lab lab-sales-report',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()

                    ],

                    [
                        'name'       => 'Items Report',
                        'language'   => 'items_report',
                        'url'        => 'items-report',
                        'icon'       => 'lab lab-items-report',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                ]
            ],
            [
                'name'       => 'Setup',
                'language'   => 'setup',
                'url'        => '#',
                'icon'       => 'lab ',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Settings',
                        'language'   => 'settings',
                        'url'        => 'settings',
                        'icon'       => 'lab lab-settings',
                        'priority'   => 100,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ]
        ];
        Menu::insert(AppLibrary::associativeToNumericArrayBuilder($menus));
    }
}
