import IngredientsComponent from "../../components/admin/ingredients/IngredientsComponent.vue";
import IngredientsListComponent from "../../components/admin/ingredients/IngredientsListComponent.vue";
import IngredientsShowComponent from "../../components/admin/ingredients/IngredientsShowComponent.vue";

export default [
    {
        path: '/admin/ingredients',
        component: IngredientsComponent,
        name: 'admin.ingredients',
        redirect: {name: 'admin.ingredients'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'ingredients',
            breadcrumb: 'ingredients'
        },
        children: [
            {
                path: '',
                component: IngredientsListComponent,
                name: 'admin.ingredients',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'ingredients',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: IngredientsShowComponent,
                name: "admin.ingredients.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
