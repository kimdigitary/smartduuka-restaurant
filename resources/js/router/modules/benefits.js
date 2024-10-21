import ExpenseCategoryComponent from "../../components/admin/ExpenseCategory/ExpenseCategoryComponent.vue";
import expenseCateogryListComponent from "../../components/admin/ExpenseCategory/ExpenseCateogryListComponent.vue";
import ExpenseCategoryShowComponent from "../../components/admin/ExpenseCategory/ExpenseCategoryShowComponent.vue";
import RoyaltyComponent from "../../components/admin/Royalty/RoyaltyComponent.vue";
import RoyaltyListComponent from "../../components/admin/Royalty/RoyaltyListComponent.vue";
import RoyaltyShowComponent from "../../components/admin/Royalty/RoyaltyShowComponent.vue";
import RoyaltyBenefitsListComponent from "../../components/admin/Royalty/benefits/RoyaltyBenefitsListComponent.vue";
import RoyaltyBenefitsShowComponent from "../../components/admin/Royalty/benefits/RoyaltyBenefitsShowComponent.vue";
export default [
    {
        path: '/admin/benefits',
        component: RoyaltyComponent,
        name: 'admin.benefits',
        redirect: {name: 'admin.benefits'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'benefits',
            breadcrumb: 'benefits'
        },
        children: [
            {
                path: '',
                component: RoyaltyBenefitsListComponent,
                name: 'admin.benefits',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'benefits',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: RoyaltyBenefitsShowComponent,
                name: "admin.benefits.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "benefits",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
