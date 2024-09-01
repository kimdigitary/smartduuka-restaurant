import KitchenOrderComponent from "../../components/admin/kitchenOrders/KitchenOrderComponent.vue";
import KitchenOrderListComponent from "../../components/admin/kitchenOrders/KitchenOrderListComponent.vue";
import KitchenCompletedOrderListComponent
    from "../../components/admin/kitchenOrders/KitchenCompletedOrderListComponent.vue";
import PosOrderShowComponent from "../../components/admin/posOrders/PosOrderShowComponent.vue";
import KitchenOrderShowComponent from "../../components/admin/kitchenOrders/KitchenOrderShowComponent.vue";

export default [
    {
        path: "/admin/kitchen-orders",
        component: KitchenOrderComponent,
        name: "admin.kitchen.orders",
        redirect: { name: "admin.kitchen.orders.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'kitchen_orders',
            breadcrumb: 'kitchen_orders'
        },
        children: [
            {
                path: "",
                component: KitchenOrderListComponent,
                name: "admin.kitchen.orders.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "kitchen_orders",
                    breadcrumb: "",
                },
            },
            {
                path: "completed",
                component: KitchenCompletedOrderListComponent,
                name: "admin.completed.kitchen.orders.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "kitchen_orders",
                    breadcrumb: "",
                },
            },
            {
                path: "show/:id",
                component: KitchenOrderShowComponent,
                name: "admin.kitchen.orders.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "kitchen_orders",
                    breadcrumb: "view",
                },
            },
        ],
    },
];
