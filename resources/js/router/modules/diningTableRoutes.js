import DiningTableListComponent from "../../components/admin/settings/diningTable/DiningTableListComponent";
import DiningTableComponent from "../../components/admin/settings/diningTable/DiningTableComponent";
import DiningTableShowComponent from "../../components/admin/settings/diningTable/DiningTableShowComponent";

export default [
    {
        path: "/admin/dining-tables",
        component: DiningTableComponent,
        name: "admin.diningTable",
        redirect: { name: "admin.diningTable.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "settings",
            breadcrumb: "dining_tables",
        },
        children: [
            {
                path: "list",
                component: DiningTableListComponent,
                name: "admin.diningTable.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "",
                },
            },
            {
                path: "show/:id",
                component: DiningTableShowComponent,
                name: "admin.diningTable.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "view",
                },
            },
        ],
    },
]
