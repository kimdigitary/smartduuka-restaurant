import ExpenseCategoryComponent from "../../components/admin/ExpenseCategory/ExpenseCategoryComponent.vue";
import expenseCateogryListComponent from "../../components/admin/ExpenseCategory/ExpenseCateogryListComponent.vue";
import ExpenseCategoryShowComponent from "../../components/admin/ExpenseCategory/ExpenseCategoryShowComponent.vue";


export default [
    {
        path: '/admin/categories',
        component: ExpenseCategoryComponent,
        name: 'admin.categories',
        redirect: {name: 'admin.categories'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'categories',
            breadcrumb: 'categories'
        },
        children: [
            {
                path: '',
                component: expenseCateogryListComponent,
                name: 'admin.categories',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'categories',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: ExpenseCategoryShowComponent,
                name: "admin.categories.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "categories",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
