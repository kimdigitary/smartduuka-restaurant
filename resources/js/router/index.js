import {createRouter, createWebHistory} from "vue-router";
import ENV from '../config/env';
import appService from "../services/appService";
import DashboardComponent from "../components/admin/dashboard/DashboardComponent";
import SuperAdminDashboardComponent from "../components/admin/dashboard/SuperAdminDashboardComponent";
import NotFoundComponent from "../components/frontend/otherPage/NotFoundComponent";
import ExceptionComponent from "../components/frontend/otherPage/ExceptionComponent";
import store from "../store";
import authRoutes from "./modules/authRoutes";
import offerRoutes from "./modules/offerRoutes";
import itemRoutes from "./modules/itemRoutes";
import customerRoutes from "./modules/customerRoutes";
import administratorRoutes from "./modules/administratorRoutes";
import employeeRoutes from "./modules/employeeRoutes";
import salesReportRoutes from "./modules/salesReportRoutes";
import itemsReportRoutes from "./modules/itemsReportRoutes";
import posRoutes from "./modules/posRoutes";
import profileRoutes from "./modules/profileRoutes";
import posOrderRoutes from "./modules/posOrderRoutes";
import transactionRoutes from "./modules/transactionRoutes";
import creditBalanceReportRoutes from "./modules/creditBalanceReportRoutes";
import tableOrderRoutes from "./modules/tableOrderRoutes";
import adminTableOrderRoutes from "./modules/adminTableOrderRoutes";
import diningTableRoutes from "./modules/diningTableRoutes";
import SubscriptionListComponent from "../components/admin/Subscription/SubscriptionListComponent.vue";
import SubscriptionCreateComponent from "../components/admin/Subscription/SubscriptionCreateComponent.vue";
import expensesRoutes from "./modules/expensesRoutes";
import categoriesRoutes from "./modules/categoriesRoutes";
import kitchenOrderRoutes from "./modules/kitchenOrderRoutes";
import ingredientsRoutes from "./modules/ingredientsRoutes";
import ingredientsStockRoutes from "./modules/ingredientsStockRoutes";
import purchaseRoutes from "./modules/purchaseRoutes";
import {purchaseIngredientsRoutes} from "./modules/purchaseIngredientsRoutes";
import {settingRoutes} from "./modules/settingRoutes";


const baseRoutes = [
    {
        path: "/",
        redirect: {name: "auth.login"},
        name: "root"
    },
    {
        path: "/:pathMatch(.*)*",
        name: "route.notFound",
        component: NotFoundComponent,
        meta: {
            isFrontend: true,
        },
    },
    {
        path: "/exception",
        name: "route.exception",
        component: ExceptionComponent,
    },
    {
        path: "/admin/dashboard",
        component: DashboardComponent,
        name: "admin.dashboard",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "dashboard",
        },
    },
    {
        path: "/admin/super/dashboard",
        component: SuperAdminDashboardComponent,
        name: "super.admin.dashboard",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "dashboard",
        },
    },
    {
        path: "/admin/subscriptions",
        component: SubscriptionListComponent,
        name: "admin.subscriptions",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "dashboard",
        },
    },
    {
        path: "/admin/subscriptions/create",
        component: SubscriptionCreateComponent,
        name: "admin.subscriptions.create",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "dashboard",
        },
    },
];

const routes = baseRoutes.concat(
    authRoutes,
    settingRoutes,
    offerRoutes,
    itemRoutes,
    customerRoutes,
    administratorRoutes,
    employeeRoutes,
    salesReportRoutes,
    itemsReportRoutes,
    profileRoutes,
    posRoutes,
    posOrderRoutes,
    transactionRoutes,
    creditBalanceReportRoutes,
    tableOrderRoutes,
    // purchaseRoutes,
    adminTableOrderRoutes,
    // diningTableRoutes,
    purchaseIngredientsRoutes,
    expensesRoutes, categoriesRoutes,
    kitchenOrderRoutes,
    ingredientsRoutes,
    ingredientsStockRoutes
);

const permission = store.getters.authPermission;
appService.recursiveRouter(routes, permission);

const API_URL = ENV.API_URL;
const router = createRouter({
    linkActiveClass: "active",
    mode: 'history',
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.auth === true) {
        if (!store.getters.authStatus) {
            next({name: "auth.login"});
        } else {
            if (to.meta.isFrontend === false) {
                if (to.meta.access === false) {
                    next({
                        name: "route.exception",
                    });
                } else {
                    next();
                }
            } else {
                next();
            }
        }
    } else if (to.name === "auth.login" && store.getters.authStatus) {
        next({name: "admin.dashboard"});
    } else {
        next();
    }
});
export default router;
