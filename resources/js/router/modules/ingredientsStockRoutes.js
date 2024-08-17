import SiteComponent from "../../components/admin/settings/Site/SiteComponent";
import ItemCategoryListComponent from "../../components/admin/settings/ItemCategory/ItemCateogryListComponent";
import ItemCategoryComponent from "../../components/admin/settings/ItemCategory/ItemCategoryComponent";
import ItemCategoryShowComponent from "../../components/admin/settings/ItemCategory/ItemCategoryShowComponent";
import ItemAttributeComponent from "../../components/admin/settings/ItemAttribute/ItemAttributeComponent";
import ItemAttributeListComponent from "../../components/admin/settings/ItemAttribute/ItemAttributeListComponent";
import BranchComponent from "../../components/admin/settings/Branch/BranchComponent";
import BranchListComponent from "../../components/admin/settings/Branch/BranchListComponent";
import BranchShowComponent from "../../components/admin/settings/Branch/BranchShowComponent";
import TaxComponent from "../../components/admin/settings/Tax/TaxComponent";
import TaxListComponent from "../../components/admin/settings/Tax/TaxListComponent";
import CurrencyComponent from "../../components/admin/settings/Currency/CurrencyComponent";
import CurrencyListComponent from "../../components/admin/settings/Currency/CurrencyListComponent";
import MailComponent from "../../components/admin/settings/Mail/MailComponent";
import PageComponent from "../../components/admin/settings/Page/PageComponent";
import PageListComponent from "../../components/admin/settings/Page/PageListComponent";
import PageShowComponent from "../../components/admin/settings/Page/PageShowComponent";
import OtpComponent from "../../components/admin/settings/Otp/OtpComponent";
import AnalyticComponent from "../../components/admin/settings/analytics/AnalyticComponent";
import AnalyticListComponent from "../../components/admin/settings/analytics/AnalyticListComponent";
import AnalyticShowComponent from "../../components/admin/settings/analytics/AnalyticShowComponent";
import RoleComponent from "../../components/admin/settings/Role/RoleComponent";
import RoleListComponent from "../../components/admin/settings/Role/RoleListComponent";
import RoleShowComponent from "../../components/admin/settings/Role/RoleShowComponent";
import ThemeComponent from "../../components/admin/settings/Theme/ThemeComponent";
import LanguageComponent from "../../components/admin/settings/Language/LanguageComponent";
import LanguageListComponent from "../../components/admin/settings/Language/LanguageListComponent";
import LanguageShowComponent from "../../components/admin/settings/Language/LanguageShowComponent";
import PaymentGatewayComponent from "../../components/admin/settings/PaymentGateway/PaymentGatewayComponent";
import SmsGatewayComponent from "../../components/admin/settings/SmsGateway/SmsGatewayComponent";
import NotificationAlertComponent from "../../components/admin/settings/NotificationAlert/NotificationAlertComponent";
import NotificationComponent from "../../components/admin/settings/Notification/NotificationComponent";
import SubscriptionListComponent from "../../components/admin/Subscription/SubscriptionListComponent.vue";
import SubscriptionCreateComponent from "../../components/admin/Subscription/SubscriptionCreateComponent.vue";
import IngredientsStockSettingsComponent
    from "../../components/admin/ingredientsStock/IngredientsStockSettingsComponent.vue";
import IngredientsListComponent from "../../components/admin/ingredients/IngredientsListComponent.vue";
import SupplierComponent from "../../components/admin/ingredientsStock/Supplier/SupplierComponent.vue";
import SupplierListComponent from "../../components/admin/ingredientsStock/Supplier/SupplierListComponent.vue";
import SupplierShowComponent from "../../components/admin/ingredientsStock/Supplier/SupplierShowComponent.vue";
import StockListComponent from "../../components/admin/stock/StockListComponent.vue";
import StockCreateOrEditComponent from "../../components/admin/stock/StockCreateAndEditComponent.vue";

export default [
    {
        path: "/admin/ingredients_and_stock",
        component: IngredientsStockSettingsComponent,
        name: "admin.ingredients_and_stock",
        redirect: { name: "admin.ingredients_and_stock.ingredients" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "ingredients_and_stock",
            breadcrumb: "ingredients_and_stock",
        },
        children: [
            {
                path: "ingredients",
                component: IngredientsListComponent,
                name: "admin.ingredients_and_stock.ingredients",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "Ingredients",
                },
            },
            {
                path: "stock_list",
                component: StockListComponent,
                name: "admin.ingredients_and_stock.stock_list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "ingredients_purchasing",
                },
            },
            {
                path: 'stock/create',
                component: StockCreateOrEditComponent,
                name: 'admin.ingredients_and_stock.stock.create',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'stock_create',
                    breadcrumb: 'create'
                }
            },
            {
                path: 'edit/:id',
                component: StockCreateOrEditComponent,
                name: 'admin.ingredients_and_stock.stock.edit',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'stock_edit',
                    breadcrumb: 'edit'
                }
            },
            {
                path: "suppliers",
                component: SupplierComponent,
                name: "admin.ingredients_and_stock.supplier",
                redirect: { name: "admin.ingredients_and_stock.supplier.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "suppliers",
                },
                children: [
                    {
                        path: "list",
                        component: SupplierListComponent,
                        name: "admin.ingredients_and_stock.supplier.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: SupplierShowComponent,
                        name: "admin.ingredients_and_stock.supplier.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "branches",
                component: BranchComponent,
                name: "admin.ingredients_and_stock.branch",
                redirect: { name: "admin.ingredients_and_stock.branch.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "branches",
                },
                children: [
                    {
                        path: "list",
                        component: BranchListComponent,
                        name: "admin.ingredients_and_stock.branch.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: BranchShowComponent,
                        name: "admin.ingredients_and_stock.branch.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "mail",
                component: MailComponent,
                name: "admin.ingredients_and_stock.mail",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "mail",
                },
            },
            {
                path: "otp",
                component: OtpComponent,
                name: "admin.ingredients_and_stock.otp",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "otp",
                },
            },
            {
                path: "analytics",
                component: AnalyticComponent,
                name: "admin.ingredients_and_stock.analytic",
                redirect: { name: "admin.ingredients_and_stock.analytic.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "analytics",
                },
                children: [
                    {

                        path: "list",
                        component: AnalyticListComponent,
                        name: "admin.ingredients_and_stock.analytic.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: AnalyticShowComponent,
                        name: "admin.ingredients_and_stock.analytic.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ]
            },
            {
                path: "theme",
                component: ThemeComponent,
                name: "admin.ingredients_and_stock.theme",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "theme",
                },
            },
            {
                path: "currencies",
                component: CurrencyComponent,
                name: "admin.ingredients_and_stock.currency",
                redirect: { name: "admin.ingredients_and_stock.currency.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "currencies",
                },
                children: [
                    {
                        path: "list",
                        component: CurrencyListComponent,
                        name: "admin.ingredients_and_stock.currency.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "item-categories",
                component: ItemCategoryComponent,
                name: "admin.ingredients_and_stock.itemCategory",
                redirect: { name: "admin.ingredients_and_stock.itemCategory.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "item_categories",
                },
                children: [
                    {
                        path: "list",
                        component: ItemCategoryListComponent,
                        name: "admin.ingredients_and_stock.itemCategory.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: ItemCategoryShowComponent,
                        name: "admin.ingredients_and_stock.itemCategory.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "item-attributes",
                component: ItemAttributeComponent,
                name: "admin.ingredients_and_stock.itemAttribute",
                redirect: { name: "admin.ingredients_and_stock.itemAttribute.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "item_attributes",
                },
                children: [
                    {
                        path: "list",
                        component: ItemAttributeListComponent,
                        name: "admin.ingredients_and_stock.itemAttribute.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "taxes",
                component: TaxComponent,
                name: "admin.ingredients_and_stock.tax",
                redirect: { name: "admin.ingredients_and_stock.tax.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "taxes",
                },
                children: [
                    {
                        path: "list",
                        component: TaxListComponent,
                        name: "admin.ingredients_and_stock.tax.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "pages",
                component: PageComponent,
                name: "admin.ingredients_and_stock.page",
                redirect: { name: "admin.ingredients_and_stock.page.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "pages",
                },
                children: [
                    {
                        path: "list",
                        component: PageListComponent,
                        name: "admin.ingredients_and_stock.page.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: PageShowComponent,
                        name: "admin.ingredients_and_stock.page.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "role",
                component: RoleComponent,
                name: "admin.ingredients_and_stock.role",
                redirect: { name: "admin.ingredients_and_stock.role.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "role_permissions",
                },
                children: [
                    {
                        path: "list",
                        component: RoleListComponent,
                        name: "admin.ingredients_and_stock.role.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: RoleShowComponent,
                        name: "admin.ingredients_and_stock.role.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "languages",
                component: LanguageComponent,
                name: "admin.ingredients_and_stock.language",
                redirect: { name: "admin.ingredients_and_stock.language.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "languages",
                },
                children: [
                    {
                        path: "list",
                        component: LanguageListComponent,
                        name: "admin.ingredients_and_stock.language.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: LanguageShowComponent,
                        name: "admin.ingredients_and_stock.language.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "ingredients_and_stock",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "sms-gateway",
                component: SmsGatewayComponent,
                name: "admin.ingredients_and_stock.smsGateway",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "sms_gateway",
                },
            },
            {
                path: "payment-gateway",
                component: PaymentGatewayComponent,
                name: "admin.ingredients_and_stock.paymentGateway",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "ingredients_and_stock",
                    breadcrumb: "payment_gateway",
                },
            },
            {
                path: "payments",
                component: SubscriptionListComponent,
                name: "admin.settings.payments",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "license",
                }
            },
            {
                path: "payments/create",
                component: SubscriptionCreateComponent,
                // component: LicenseComponent,
                name: "admin.settings.payments.create",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "license",
                }
            },
            {
                path: "notification-alert",
                component: NotificationAlertComponent,
                name: "admin.settings.notificationAlert",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "notification_alert",
                }
            },
            {
                path: "notification",
                component: NotificationComponent,
                name: "admin.settings.notification",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "notification",

                },
            },
        ],
    },
];
