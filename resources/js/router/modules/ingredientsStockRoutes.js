import IngredientsStockSettingsComponent
    from "../../components/admin/ingredientsStock/IngredientsStockSettingsComponent.vue";
import IngredientsListComponent from "../../components/admin/ingredients/IngredientsListComponent.vue";
import SupplierComponent from "../../components/admin/ingredientsStock/Supplier/SupplierComponent.vue";
import SupplierListComponent from "../../components/admin/ingredientsStock/Supplier/SupplierListComponent.vue";
import SupplierShowComponent from "../../components/admin/ingredientsStock/Supplier/SupplierShowComponent.vue";
import StockListComponent from "../../components/admin/stock/StockListComponent.vue";
import StockCreateOrEditComponent from "../../components/admin/stock/StockCreateAndEditComponent.vue";

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
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "ingredients_purchasing",
                },
            },
            {
                path: 'stock/create',
                component: StockCreateOrEditComponent,
                name: 'admin.ingredients_and_stock.stock.create',
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
                name: 'admin.ingredients_and_stock.stock.edit',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'stock_edit',
                    breadcrumb: 'edit'
                }
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
