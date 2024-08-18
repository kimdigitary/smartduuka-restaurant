
import PurchaseComponent from "../../components/admin/ingredientsStock/purchase/PurchaseComponent.vue";
import PurchaseListComponent from "../../components/admin/ingredientsStock/purchase/PurchaseListComponent.vue";
import PurchaseCreateAndEditComponent
    from "../../components/admin/ingredientsStock/purchase/PurchaseCreateAndEditComponent.vue";
import PurchaseShowComponent from "../../components/admin/ingredientsStock/purchase/PurchaseShowComponent.vue";

export default [
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
    }
]
