import KitchenOrderComponent from "../../components/admin/kitchenOrders/KitchenOrderComponent.vue";
import KitchenOrderListComponent from "../../components/admin/kitchenOrders/KitchenOrderListComponent.vue";

export default [
    {
        path: "/admin/kitchen-orders",
        component: KitchenOrderComponent,
        name: "admin.kitchen.orders",
        redirect: { name: "admin.kitchen.orders.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'pos',
            breadcrumb: 'kitchen_orders'
        },
        children: [
            {
                path: "",
                component: KitchenOrderListComponent,
                name: "admin.pos.orders.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "pos",
                    breadcrumb: "",
                },
            },
        ],
    },
];
