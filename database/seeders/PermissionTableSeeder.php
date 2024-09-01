<?php

namespace Database\Seeders;

use App\Libraries\AppLibrary;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'title'      => 'Dashboard',
                'name'       => 'dashboard',
                'guard_name' => 'sanctum',
                'url'        => 'dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Items',
                'name'       => 'items',
                'guard_name' => 'sanctum',
                'url'        => 'items',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Items Create',
                        'name'       => 'items_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'items/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Items Edit',
                        'name'       => 'items_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'items/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Items Delete',
                        'name'       => 'items_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'items/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Items Show',
                        'name'       => 'items_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'items/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Ingredients',
                'name'       => 'ingredients',
                'guard_name' => 'sanctum',
                'url'        => 'ingredients',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Ingredients Create',
                        'name'       => 'ingredients_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'ingredients/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Ingredients Edit',
                        'name'       => 'ingredients_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'ingredients/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Ingredients Delete',
                        'name'       => 'ingredients_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'ingredients/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Ingredients Show',
                        'name'       => 'ingredients_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'ingredients/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Dining Tables',
                'name'       => 'dining-tables',
                'guard_name' => 'sanctum',
                'url'        => 'dining-tables',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Dining Tables Create',
                        'name'       => 'dining_tables_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-table/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Dining Tables Edit',
                        'name'       => 'dining_tables_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-table/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Dining Tables Delete',
                        'name'       => 'dining_tables_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-tables/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Dining Tables Show',
                        'name'       => 'dining_tables_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-tables/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'POS',
                'name'       => 'pos',
                'guard_name' => 'sanctum',
                'url'        => 'pos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'POS Orders',
                'name'       => 'pos-orders',
                'guard_name' => 'sanctum',
                'url'        => 'pos-orders',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'POS Orders Create',
                        'name'       => 'pos_orders_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-table/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'POS Orders Edit',
                        'name'       => 'pos_orders_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-table/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'POS Orders Delete',
                        'name'       => 'pos_orders_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-tables/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'POS Orders Show',
                        'name'       => 'pos_orders_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-tables/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], [
                        'title'      => 'POS Orders Cancel',
                        'name'       => 'pos_orders_cancel',
                        'guard_name' => 'sanctum',
                        'url'        => 'dining-tables/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Expenses',
                'name'       => 'expenses',
                'guard_name' => 'sanctum',
                'url'        => 'expenses',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Expenses Create',
                        'name'       => 'expenses_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'expenses/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Expenses Edit',
                        'name'       => 'expenses_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'expenses/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Expenses Delete',
                        'name'       => 'expenses_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'expenses/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Expenses Show',
                        'name'       => 'expenses_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'expenses/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Categories',
                'name'       => 'categories',
                'guard_name' => 'sanctum',
                'url'        => 'categories',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Categories Create',
                        'name'       => 'categories_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'categories/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Categories Edit',
                        'name'       => 'categories_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'categories/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Categories Delete',
                        'name'       => 'categories_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'categories/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Categories Show',
                        'name'       => 'categories_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'categories/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],

            [
                'title'      => 'Table Orders',
                'name'       => 'table-orders',
                'guard_name' => 'sanctum',
                'url'        => 'table-orders',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Table Order Create',
                        'name'       => 'table_order_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'table-orders/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Table Order Edit',
                        'name'       => 'table_order_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'table-orders/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Table Order Delete',
                        'name'       => 'table_order_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'table-orders/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Table Order Show',
                        'name'       => 'table_order_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'table-orders/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Offers',
                'name'       => 'offers',
                'guard_name' => 'sanctum',
                'url'        => 'offers',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Offers Create',
                        'name'       => 'offers_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'offers/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Offers Edit',
                        'name'       => 'offers_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'offers/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Offers Delete',
                        'name'       => 'offers_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'offers/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Offers Show',
                        'name'       => 'offers_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'offers/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Administrators',
                'name'       => 'administrators',
                'guard_name' => 'sanctum',
                'url'        => 'administrators',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Administrators Create',
                        'name'       => 'administrators_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'administrators/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Administrators Edit',
                        'name'       => 'administrators_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'administrators/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Administrators Delete',
                        'name'       => 'administrators_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'administrators/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Administrators Show',
                        'name'       => 'administrators_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'administrators/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Delivery Boys',
                'name'       => 'delivery-boys',
                'guard_name' => 'sanctum',
                'url'        => 'delivery-boys',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Delivery Boys Create',
                        'name'       => 'delivery-boys_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'delivery-boys/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Delivery Boys Edit',
                        'name'       => 'delivery-boys_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'delivery-boys/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Delivery Boys Delete',
                        'name'       => 'delivery-boys_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'delivery-boys/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Delivery Boys Show',
                        'name'       => 'delivery-boys_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'delivery-boys/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Customers',
                'name'       => 'customers',
                'guard_name' => 'sanctum',
                'url'        => 'customers',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Customers Create',
                        'name'       => 'customers_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'customers/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Customers Edit',
                        'name'       => 'customers_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'customers/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Customers Delete',
                        'name'       => 'customers_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'customers/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Customers Show',
                        'name'       => 'customers_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'customers/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Employees',
                'name'       => 'employees',
                'guard_name' => 'sanctum',
                'url'        => 'employees',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Employees Create',
                        'name'       => 'employees_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'employees/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Employees Edit',
                        'name'       => 'employees_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'employees/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Employees Delete',
                        'name'       => 'employees_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'employees/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Employees Show',
                        'name'       => 'employees_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'employees/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Branches',
                'name'       => 'branches',
                'guard_name' => 'sanctum',
                'url'        => 'branches',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Branches Create',
                        'name'       => 'branches_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'branches/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Branches Edit',
                        'name'       => 'branches_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'branches/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Branches Delete',
                        'name'       => 'branches_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'branches/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Branches Show',
                        'name'       => 'branches_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'branches/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Product Stocking',
                'name'       => 'purchase',
                'guard_name' => 'sanctum',
                'url'        => 'purchase',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Stocking Create',
                        'name'       => 'purchase_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'purchase/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Stocking Edit',
                        'name'       => 'purchase_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'purchase/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Stocking Delete',
                        'name'       => 'purchase_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'purchase/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Stocking Show',
                        'name'       => 'purchase_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'purchase/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Stock',
                'name'       => 'itemStock',
                'guard_name' => 'sanctum',
                'url'        => 'itemStock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Transactions',
                'name'       => 'transactions',
                'guard_name' => 'sanctum',
                'url'        => 'transactions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Sales Report',
                'name'       => 'sales-report',
                'guard_name' => 'sanctum',
                'url'        => 'sales-report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Items Report',
                'name'       => 'items-report',
                'guard_name' => 'sanctum',
                'url'        => 'items-report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Credit Balance Report',
                'name'       => 'credit-balance-report',
                'guard_name' => 'sanctum',
                'url'        => 'credit-balance-report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Settings',
                'name'       => 'settings',
                'guard_name' => 'sanctum',
                'url'        => 'settings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Ingredients & Items',
                'name'       => 'ingredients_and_stock',
                'guard_name' => 'sanctum',
                'url'        => 'ingredients_and_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Kitchen Orders',
                'name'       => 'kitchen-orders',
                'guard_name' => 'sanctum',
                'url'        => 'kitchen-orders',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Kitchen Orders',
                        'name'       => 'kitchen_orders',
                        'guard_name' => 'sanctum',
                        'url'        => 'kitchen-orders',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
        ];

        $permissions = AppLibrary::associativeToNumericArrayBuilder($permissions);
        Permission::insert($permissions);
    }
}
