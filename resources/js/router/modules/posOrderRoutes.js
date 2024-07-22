import PosOrderComponent from "../../components/admin/posOrders/PosOrderComponent";
import PosOrderListComponent from "../../components/admin/posOrders/PosOrderListComponent";
import PosOrderShowComponent from "../../components/admin/posOrders/PosOrderShowComponent";
import PosOrderEditComponent from "../../components/admin/posOrders/PosOrderEditComponent.vue";
import EditPosComponent from "../../components/admin/pos/EditPosComponent.vue";

export default [
    {
        path: "/admin/pos-orders",
        component: PosOrderComponent,
        name: "admin.pos.orders",
        redirect: { name: "admin.pos.orders.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'pos',
            breadcrumb: 'pos_orders'
        },
        children: [
            {
                path: "",
                component: PosOrderListComponent,
                name: "admin.pos.orders.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "pos",
                    breadcrumb: "",
                },
            },
            {
                path: "show/:id",
                component: PosOrderShowComponent,
                name: "admin.pos.orders.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "pos",
                    breadcrumb: "view",
                },
            },{
                path: "edit/:id",
                component: EditPosComponent,
                name: "admin.pos.orders.edit",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "pos",
                    breadcrumb: "edit",
                },
            }
        ],
    },
];
