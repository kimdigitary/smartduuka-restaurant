<template>
    <LoadingComponent :props="loading"/>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t('menu.pos_orders') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage"/>
                    <FilterComponent/>
                    <div class="dropdown-group">
                        <ExportComponent/>
                        <div class="dropdown-list db-card-filter-dropdown-list">
                            <PrintComponent :props="printObj"/>
                            <ExcelComponent :method="xls"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="order_serial_no" class="db-field-title after:hidden">Order ID</label>
                            <input id="order_serial_no" v-model="props.search.order_serial_no" type="text" class="db-field-control"/>
                        </div>
                        <!--                        <div class="col-12 sm:col-6 md:col-4 xl:col-3 z-50">-->
                        <!--                            <label for="dining_table_id" class="db-field-title after:hidden">Table</label>-->
                        <!--                            <vue-select class="db-field-control f-b-custom-select" id="dining_table_id"-->
                        <!--                                        v-model="props.search.dining_table_id" :options="diningTables" label-by="name"-->
                        <!--                                        value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="&#45;&#45;"-->
                        <!--                                        search-placeholder="&#45;&#45;"/>-->
                        <!--                        </div>-->

                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-search-line lab-font-size-16"></i>
                                    <span>{{ $t("button.search") }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-cross-line-2 lab-font-size-22"></i>
                                    <span>{{ $t("button.clear") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="db-table-responsive">
                <table class="db-table stripe" id="print" :dir="direction">
                    <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t('label.order_id') }}</th>
                        <th class="db-table-head-th">{{ $t('label.customer') }}</th>
                        <th class="db-table-head-th">Table</th>
                        <th class="db-table-head-th">Created By</th>
                        <th class="db-table-head-th">{{ $t('label.amount') }}</th>
                        <th class="db-table-head-th">Payment Status</th>
                        <th class="db-table-head-th">{{ $t('label.date') }}</th>
                        <th class="db-table-head-th">{{ $t('label.status') }}</th>
                        <th class="db-table-head-th hidden-print" v-if="permissionChecker('pos-orders')">{{
                                $t('label.action')
                            }}
                        </th>
                    </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="orders.length > 0">
                    <tr class="db-table-body-tr" v-for="order in orders" :key="order">
                        <td class="db-table-body-td">{{ order.order_serial_no }}</td>
                        <td class="db-table-body-td">{{ order.customer.name }}</td>
                        <td class="db-table-body-td">{{ order.dining_table ? order.dining_table.name : 'NA' }}</td>
                        <td class="db-table-body-td">{{ order.created_by ? order.created_by.name : 'NA' }}</td>
                        <td class="db-table-body-td">{{ order.total_amount_price_currency }}</td>
                        <td class="db-table-body-td">{{ enums.paymentStatusEnumArray[order.payment_status] }}</td>
                        <td class="db-table-body-td">{{ order.order_datetime }}</td>
                        <td class="db-table-body-td">
                                <span :class="orderStatusClass(order.status)">
                                    {{ enums.orderStatusEnumArray[order.status] }}
                                </span>
                        </td>
                        <td class="db-table-body-td hidden-print" v-if="permissionChecker('pos-orders')">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmIconViewComponent :link="'admin.pos.orders.show'" :id="order.id"
                                                     v-if="permissionChecker('pos-orders')"/>
                                <SmIconEditComponent @click="edit(order)" :link="'admin.pos.orders.edit'" :id="order.id"
                                                     v-if="permissionChecker('pos_orders_edit')"/>
                                <SmIconDeleteComponent @click="destroy(order.id)"
                                                       v-if="permissionChecker('pos_orders_delete')"/>
                                <SmAddPaymentComponent @click="addPayment(order)" data-modal="#purchasePayment"
                                                       v-if="permissionChecker('payments_create')"/>
                                <smViewPaymentComponent @click="paymentList(order.id)" data-modal="#purchasePaymentList"
                                                        v-if="permissionChecker('payments_show')"/>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
                <PaginationSMBox :pagination="pagination" :method="list"/>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <PaginationTextComponent :props="{ page: paginationPage }"/>
                    <PaginationBox :pagination="pagination" :method="list"/>
                </div>
            </div>
        </div>
    </div>
    <PostPurchaseComponent/>
    <div v-if="order && Object.keys(order).length > 0">
        <ReceiptComponent :order="order"/>
    </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import {ref} from 'vue';
import {endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths} from 'date-fns';
import statusEnum from "../../../enums/modules/statusEnum";
import displayModeEnum from "../../../enums/modules/displayModeEnum";
import SmIconEditComponent from "../components/buttons/SmIconEditComponent.vue";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent.vue";
import ItemCreateComponent from "../items/ItemCreateComponent.vue";
import PosOrderEditComponent from "./PosOrderEditComponent.vue";
import paymentStatusEnum from "../../../enums/modules/paymentStatusEnum";
import {TimerEnums} from "../../../enums/timerEnums.ts";
import ReceiptComponent from "../pos/ReceiptComponent.vue";
import SmAddPaymentComponent from "../components/buttons/SmAddPaymentComponent.vue";
import SmViewPaymentComponent from "../components/buttons/SmViewPaymentComponent.vue";
import purchaseTypeEnum from "../../../enums/modules/purchaseTypeEnum";
import IngredientPurchaseComponent from "../ingredientsStock/purchaseIngredients/IngredientPurchaseComponent.vue";
import PostPurchaseComponent from "../pos/PostPurchaseComponent.vue";
import PosOrderInvoiceComponent from "./PosOrderInvoiceComponent.vue";
import projectEnum from "../../../enums/modules/projectEnum";
import {mapState} from "vuex";

export default {
    name: "PosOrderListComponent",
    components: {
        PosOrderInvoiceComponent,
        PostPurchaseComponent,
        IngredientPurchaseComponent,
        SmViewPaymentComponent, SmAddPaymentComponent,
        ReceiptComponent,
        PosOrderEditComponent,
        ItemCreateComponent,
        SmIconSidebarModalEditComponent,
        SmIconEditComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        LoadingComponent,
        SmIconDeleteComponent,
        SmIconViewComponent,
        FilterComponent,
        ExportComponent,
        PrintComponent,
        ExcelComponent,
        Datepicker
    },
    setup() {
        const date = ref();
        const presetRanges = ref([
            {label: 'Today', range: [new Date(), new Date()]},
            {label: 'This month', range: [startOfMonth(new Date()), endOfMonth(new Date())]},
            {
                label: 'Last month',
                range: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
            },
            {label: 'This year', range: [startOfYear(new Date()), endOfYear(new Date())]},
            {
                label: 'This year (slot)',
                range: [startOfYear(new Date()), endOfYear(new Date())],
                slot: 'yearly',
            },
        ]);

        return {
            date,
            presetRanges,
        }
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            order: {},
            timer: null,
            interval: TimerEnums.INTERVAL,
            enums: {
                orderStatusEnum: orderStatusEnum,
                paymentStatusEnum: paymentStatusEnum,
                orderTypeEnum: orderTypeEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.pending"),
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PROCESSING]: this.$t("label.processing"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                    [orderStatusEnum.PREPARED]: this.$t("label.prepared"),
                },
                orderTypeEnumArray: {
                    [orderTypeEnum.POS]: this.$t("label.pos"),
                },
                paymentStatusEnumArray: {
                    [paymentStatusEnum.PAID]: this.$t("label.paid"),
                    [paymentStatusEnum.UNPAID]: this.$t("label.unpaid"),
                    [paymentStatusEnum.PARTIALLY_PAID]: this.$t("label.partially_paid")
                },
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t("menu.online_orders"),
            },
            props: {
                form: {
                    date: null,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_by: "desc",
                    order_serial_no: "",
                    dining_table_id: "",
                    order_type: orderTypeEnum.POS,
                    user_id: null,
                    status: null,
                    from_date: "",
                    to_date: "",
                }
            },
            diningTableProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                }
            },
        }
    },
    mounted() {
        this.list();
        this.loading.isActive = true;
        this.startPolling();
        this.props.search.page = 1;
        this.$store.dispatch('user/lists', {
            order_column: 'id',
            order_type: 'asc',
            status: statusEnum.ACTIVE
        });
        this.$store.dispatch("user/diningTableList", this.diningTableProps.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    beforeRouteLeave(to, from, next) {
        if (this.timer) {
            clearInterval(this.timer);
        }
        next();
    },
    beforeDestroy() {
        if (this.timer) {
            clearInterval(this.timer);
        }
    },
    computed: {
        projectEnum() {
            return projectEnum
        },
        orders: function () {
            return this.$store.getters['posOrder/lists'];
        },
        customers: function () {
            return this.$store.getters['user/lists'];
        },
        pagination: function () {
            return this.$store.getters['posOrder/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['posOrder/page'];
        },
        direction: function () {
            return this.$store.getters['frontendLanguage/show'].display_mode === displayModeEnum.RTL ? 'rtl' : 'ltr';
        },
        diningTables: function () {
            return this.$store.getters['user/diningTable'];
        },
        ...mapState({
            showReceiptModal: state => state.purchase.showReceiptModal,
        })
    },
    watch: {
        showReceiptModal(newVal, oldVal) {
            if (newVal === true) {
                appService.modalShow('#receiptModal');
            }
        },
        order(newOrder) {
            if (Object.keys(newOrder).length > 0 && this.showReceiptModal) {
                appService.modalShow('#receiptModal');
            }
        },
    },
    methods: {

        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        paymentList: function (id) {
            appService.modalShow('#purchasePaymentList');
            this.loading.isActive = true;
            this.$store.dispatch("purchase/payment", {id, type: purchaseTypeEnum.POS});
            this.loading.isActive = false;
        },
        addPayment: function (order) {
            this.$store.dispatch("purchase/showReceiptModal", false);
            appService.modalShow('#purchasePayment');
            this.loading.isActive = true;
            this.order = order;
            this.$store.dispatch("purchase/payment", {id: order.id, type: purchaseTypeEnum.POS});
            this.$store.dispatch("purchase/setSelectedOrder", order);
            this.loading.isActive = false;
        },
        diningTableList: function () {
            this.$store.dispatch("user/diningTableList", this.props.search).then().catch();
        },
        startPolling() {
            this.timer = setInterval(() => {
                this.polling()
            }, 5000)
        },
        polling: function () {
            this.$store.dispatch('posOrder/lists', this.props.search).then(res => {
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },

        edit: function (order) {
            this.loading.isActive = true;
            this.$store.dispatch('posCart/resetCart').then(() => {
                this.$store.dispatch('posCart/lists', order.order_items).then(() => {
                    this.$store.dispatch('posOrder/edit', order.id).then(() => {
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });
                }).catch((err) => {
                    this.loading.isActive = false;
                });

                this.loading.isActive = false;
            }).catch();
            // this.$store.dispatch('posOrder/edit', order.id);
            this.loading.isActive = false;
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        search: function () {
            this.list();
        },
        handleDate: function (e) {
            if (e) {
                this.props.search.from_date = e[0];
                this.props.search.to_date = e[1];
            } else {
                this.props.form.date = null;
                this.props.search.from_date = null;
                this.props.search.to_date = null;
            }
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.order_by = "desc";
            this.props.search.order_serial_no = "";
            this.props.search.status = null;
            this.props.search.excepts = orderTypeEnum.DELIVERY + '|' + orderTypeEnum.TAKEAWAY;
            this.props.search.from_date = "";
            this.props.search.to_date = "";
            this.props.search.user_id = null;
            this.props.form.date = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('posOrder/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('posOrder/destroy', {id: id, search: this.props.search}).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('menu.pos_orders'));
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    })
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            })
        },
        xls: function () {
            this.loading.isActive = true;
            this.$store.dispatch("posOrder/export", this.props.search).then((res) => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.pos_orders");
                link.click();
                URL.revokeObjectURL(link.href);
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        },
    }
}
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
