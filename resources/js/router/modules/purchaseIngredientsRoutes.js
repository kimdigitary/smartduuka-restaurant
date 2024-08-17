import PurchaseComponent from '../../components/admin/purchase/PurchaseComponent'
import IngredientPurchaseListComponent
    from "../../components/admin/purchaseIngredients/IngredientPurchaseListComponent.vue";
import IngredientPurchaseCreateAndEditComponent
    from "../../components/admin/purchaseIngredients/IngredientPurchaseCreateAndEditComponent.vue";
import IngredientPurchaseShowComponent
    from "../../components/admin/purchaseIngredients/IngredientPurchaseShowComponent.vue";

export const purchaseIngredientsRoutes= [
    {
        path:'/admin/ingredients_and_stock/purchase/ingredient',
        component: PurchaseComponent,
        name: 'admin.ingredients_and_stock.purchase.ingredient',
        redirect: {name: 'admin.ingredients_and_stock.purchase.ingredient.list'},
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
                name: 'admin.ingredients_and_stock.purchase.ingredient.list',
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
                name: 'admin.ingredients_and_stock.purchase.ingredient.create',
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
                name: 'admin.ingredients_and_stock.purchase.ingredient.edit',
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
                name: 'admin.ingredients_and_stock.purchase.ingredient.show',
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