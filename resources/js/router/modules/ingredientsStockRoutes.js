import IngredientsStockSettingsComponent
    from "../../components/admin/ingredientsStock/IngredientsStockSettingsComponent.vue";
import IngredientsListComponent from "../../components/admin/ingredients/IngredientsListComponent.vue";
import SupplierComponent from "../../components/admin/ingredientsStock/Supplier/SupplierComponent.vue";
import SupplierListComponent from "../../components/admin/ingredientsStock/Supplier/SupplierListComponent.vue";
import SupplierShowComponent from "../../components/admin/ingredientsStock/Supplier/SupplierShowComponent.vue";
import StockListComponent from "../../components/admin/ingredientsStock/itemStock/StockListComponent.vue";
import StockCreateOrEditComponent
    from "../../components/admin/ingredientsStock/itemStock/StockCreateAndEditComponent.vue";
import PurchaseComponent from "../../components/admin/ingredientsStock/purchase/PurchaseComponent.vue";
import PurchaseListComponent from "../../components/admin/ingredientsStock/purchase/PurchaseListComponent.vue";
import PurchaseCreateAndEditComponent
    from "../../components/admin/ingredientsStock/purchase/PurchaseCreateAndEditComponent.vue";
import PurchaseShowComponent from "../../components/admin/ingredientsStock/purchase/PurchaseShowComponent.vue";
import IngredientPurchaseComponent
    from "../../components/admin/ingredientsStock/purchaseIngredients/IngredientPurchaseComponent.vue";
import IngredientPurchaseListComponent
    from "../../components/admin/ingredientsStock/purchaseIngredients/IngredientPurchaseListComponent.vue";
import IngredientPurchaseCreateAndEditComponent
    from "../../components/admin/ingredientsStock/purchaseIngredients/IngredientPurchaseCreateAndEditComponent.vue";
import IngredientPurchaseShowComponent
    from "../../components/admin/ingredientsStock/purchaseIngredients/IngredientPurchaseShowComponent.vue";
import IngredientStockListComponent
    from "../../components/admin/ingredientsStock/IngredientStock/IngredientStockListComponent.vue";

export default [
    {
        path: "/admin/ingredients_and_stock",
        component: IngredientsStockSettingsComponent,
        name: "admin.ingredients_and_stock",
        redirect: { name: "admin.ingredients_and_stock.ingredients" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "ingredients_and_stock",
            breadcrumb: "ingredients_and_stock",
        },
        children: [
            {
                path: "ingredients",
                component: IngredientsListComponent,
                name: "admin.ingredients_and_stock.ingredients",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "Ingredients",
                },
            },
            {
                path: "stock_list",
                component: StockListComponent,
                name: "admin.ingredients_and_stock.stock_list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "stock_create",
                    breadcrumb: "ingredients_purchasing",
                },
            },
            {
                path: "ingredients_stock_list",
                component: IngredientStockListComponent,
                name: "admin.ingredients_and_stock.ingredients.stock_list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "stock_create",
                    breadcrumb: "ingredients_purchasing",
                },
            },
            {
                path: 'itemStock/create',
                component: StockCreateOrEditComponent,
                name: 'admin.ingredients_and_stock.itemStock.create',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'stock_create',
                    breadcrumb: 'create'
                }
            },
            {
                path: 'edit/:id',
                component: StockCreateOrEditComponent,
                name: 'admin.ingredients_and_stock.itemStock.edit',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'stock_edit',
                    breadcrumb: 'edit'
                }
            },
            {
                path:'/admin/ingredients_and_stock/purchase',
                component: PurchaseComponent,
                name: 'admin.ingredients_and_stock.purchase',
                redirect: {name: 'admin.ingredients_and_stock.purchase.list'},
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'purchase',
                    breadcrumb:'purchase'
                },
                children: [
                    {
                        path:'',
                        component: PurchaseListComponent,
                        name: 'admin.ingredients_and_stock.purchase.list',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase',
                            breadcrumb: ''
                        }

                    },
                    {
                        path: 'add',
                        component: PurchaseCreateAndEditComponent,
                        name: 'admin.ingredients_and_stock.purchase.create',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase_create',
                            breadcrumb: 'create'
                        }
                    },
                    {
                        path: 'edit/:id',
                        component: PurchaseCreateAndEditComponent,
                        name: 'admin.ingredients_and_stock.purchase.edit',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase_edit',
                            breadcrumb: 'edit'
                        }
                    },
                    {
                        path: 'show/:id',
                        component: PurchaseShowComponent,
                        name: 'admin.ingredients_and_stock.purchase.show',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase_show',
                            breadcrumb: 'view'
                        }
                    }
                ]
            }, {
                path:'/admin/ingredients_and_stock/ingredients',
                component: IngredientPurchaseComponent,
                name: 'admin.ingredients_and_stock.purchase.ingredients',
                redirect: {name: 'admin.ingredients_and_stock.ingredients.purchase.list'},
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'purchase',
                    breadcrumb:'purchase'
                },
                children: [
                    {
                        path:'',
                        component: IngredientPurchaseListComponent,
                        name: 'admin.ingredients_and_stock.ingredients.purchase.list',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase',
                            breadcrumb: ''
                        }

                    },
                    {
                        path: 'add',
                        component: IngredientPurchaseCreateAndEditComponent,
                        name: 'admin.ingredients_and_stock.ingredients.purchase.create',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase_create',
                            breadcrumb: 'create'
                        }
                    },
                    {
                        path: 'edit/:id',
                        component: IngredientPurchaseCreateAndEditComponent,
                        name: 'admin.ingredients_and_stock.ingredients.purchase.edit',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase_edit',
                            breadcrumb: 'edit'
                        }
                    },
                    {
                        path: 'show/:id',
                        component: IngredientPurchaseShowComponent,
                        name: 'admin.ingredients_and_stock.ingredients.purchase.show',
                        meta: {
                            isFrontend:false,
                            auth:true,
                            permissionUrl: 'purchase_show',
                            breadcrumb: 'view'
                        }
                    }
                ]
            },
            {
                path: "suppliers",
                component: SupplierComponent,
                name: "admin.ingredients_and_stock.supplier",
                redirect: { name: "admin.ingredients_and_stock.supplier.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "suppliers",
                },
                children: [
                    {
                        path: "list",
                        component: SupplierListComponent,
                        name: "admin.ingredients_and_stock.supplier.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: SupplierShowComponent,
                        name: "admin.ingredients_and_stock.supplier.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
        ],
    },
];
