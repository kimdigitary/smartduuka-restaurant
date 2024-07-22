<template>
    <LoadingComponent :props="loading"/>
    <div class="col-12">
        <!--        <div class="db-card">-->
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t('menu.pos_orders') }}</h3>
            <div class="col-12">
                <div class="db-card p-4 my-4 col-3" v-for="order in orders" :key="order">
                    <div class="flex flex-wrap gap-y-5 items-end justify-between">
                        <div>
                            <div class="flex flex-wrap items-start gap-y-2 gap-x-6 mb-5">
                                <p class="text-2xl font-medium">
                                    {{ $t('label.order_id') }}:
                                    <span class="text-heading">
                                #{{ order.order_serial_no }}
                            </span>
                                </p>
                                <div class="flex items-center gap-2 mt-1.5">
                            <span
                                :class="'text-xs capitalize h-5 leading-5 px-2 rounded-3xl text-[#FB4E4E] bg-[#FFDADA]' + statusClass(order.payment_status)">
                                {{ enums.paymentStatusEnumArray[order.payment_status] }}
                            </span>
                                    <span
                                        :class="'text-xs capitalize px-2 rounded-3xl ' + orderStatusClass(order.status)">
                                {{ enums.orderStatusEnumArray[order.status] }}
                            </span>
                                </div>
                            </div>
                            <ul class="flex flex-col gap-2">
                                <li class="flex items-center gap-2">
                                    <i class="lab lab-calendar-line lab-font-size-16"></i>
                                    <span class="text-xs">{{ order.order_datetime }}</span>
                                </li>
                                <li class="text-xs">
                                    {{ $t('label.payment_type') }}:
                                    <span class="text-heading">
                                {{ $t('label.cash') }}
                            </span>
                                </li>
                                <li class="text-xs">
                                    {{ $t('label.order_type') }}:
                                    <span class="text-heading">
                                {{ this.$t("label.pos") }}
                            </span>
                                </li>
                                <li class="text-xs">{{
                                        $t('label.delivery_time')
                                    }}:
                                    <span class="text-heading">
                                {{ order.delivery_date }}
                            </span>
                                </li>
                                <li class="text-xs" v-if="order.token">{{
                                        $t('label.token_no')
                                    }}:
                                    <span class="text-heading">
                                #{{ order.token }}
                            </span>
                                </li>
                            </ul>
                            <div>
                                <div class="flex gap-2 p-2 items-center" v-for="orderItem in order.orderItems">
                                    <div class="custom-checkbox">
                                        <input type="checkbox" class="custom-checkbox-field" :id="orderItem.id" :value="orderItem.id"
                                               :checked="checkedStatue[orderItem.id]"
                                               @change="enable($event)">
                                        <i class="fa-solid fa-check custom-checkbox-icon"></i>
                                    </div>
                                        <label class="cursor-pointer" :for="orderItem.id">{{ orderItem.quantity }} X {{ orderItem.order_item.name }}</label>

<!--                                    <span class="me-3">{{ orderItem.quantity }} X {{ orderItem.order_item.name }}</span>-->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--        </div>-->
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
import paymentStatusEnum from "../../../enums/modules/paymentStatusEnum";

export default {
    name: "KitchenOrderListComponent",
    components: {
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
            disabledStatue: {},
            checkedStatue: {},
            enums: {
                orderStatusEnum: orderStatusEnum,
                paymentStatusEnum: paymentStatusEnum,
                orderTypeEnum: orderTypeEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PROCESSING]: this.$t("label.processing"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                },
                orderTypeEnumArray: {
                    [orderTypeEnum.POS]: this.$t("label.pos"),
                },
                paymentStatusEnumArray: {
                    [paymentStatusEnum.PAID]: this.$t("label.paid"),
                    [paymentStatusEnum.UNPAID]: this.$t("label.unpaid")
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
                    order_type: orderTypeEnum.POS,
                    user_id: null,
                    status: null,
                    from_date: "",
                    to_date: "",
                }
            }
        }
    },
    mounted() {
        this.list();
        this.$store.dispatch('user/lists', {
            order_column: 'id',
            order_type: 'asc',
            status: statusEnum.ACTIVE
        });
    },
    computed: {
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
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        enable: function (event) {
            if (event.target.checked === true) {

            }
        },
        // edit: function (product) {
        //     this.loading.isActive = true;
        //     appService.sideDrawerShow();
        //     this.$store.dispatch('posOrder/edit', product.id);
        //     this.loading.isActive = false;
        //     this.props.form.name = product.name;
        // },

        edit: function (product) {
            this.loading.isActive = true;
            this.$store.dispatch('posOrder/edit', product.id);
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
