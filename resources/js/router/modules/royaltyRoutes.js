import RoyaltyComponent from "../../components/admin/Royalty/RoyaltyComponent.vue";
import RoyaltyListComponent from "../../components/admin/Royalty/RoyaltyListComponent.vue";
import RoyaltyShowComponent from "../../components/admin/Royalty/RoyaltyShowComponent.vue";

export default [
    {
        path: '/admin/royalty',
        component: RoyaltyComponent,
        name: 'admin.royalty',
        redirect: {name: 'admin.royalty'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'royalty',
            breadcrumb: 'royalty'
        },
        children: [
            {
                path: '',
                component: RoyaltyListComponent,
                name: 'admin.royalty',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'royalty',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: RoyaltyShowComponent,
                name: "admin.royalty.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "royalty",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
