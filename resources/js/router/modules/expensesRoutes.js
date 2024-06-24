import ExpenseComponent from "../../components/admin/expenses/ExpenseComponent.vue";
import expensetListComponent from "../../components/admin/expenses/ExpensetListComponent.vue";
import ExpenseShowComponent from "../../components/admin/expenses/ExpenseShowComponent.vue";
import ExpenseCreateComponent from "../../components/admin/expenses/ExpenseCreateComponent.vue";

export default [
    {
        path: '/admin/expenses',
        component: ExpenseComponent,
        name: 'admin.expenses',
        redirect: {name: 'admin.expenses.list'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'expenses',
            breadcrumb: 'expenses'
        },
        children: [
            {
                path: 'create',
                component: ExpenseCreateComponent,
                name: 'admin.expenses.create',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'expenses',
                    breadcrumb: 'create'
                }
            },
            {
                path: '',
                component: expensetListComponent,
                name: 'admin.expenses.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'expenses',
                    breadcrumb: ''
                },
            },
            {
                path: 'edit/:id',
                component: ExpenseCreateComponent,
                name: 'admin.expenses.edit',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'purchase_edit',
                    breadcrumb: 'edit'
                }
            },
            {
                path: "show/:id",
                component: ExpenseShowComponent,
                name: "admin.expenses.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "expenses",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
