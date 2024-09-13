<template>
    <LoadingComponent :props="loading"/>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">Expenses</h3>
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
                    <!--          <ExpenseCreateComponent :props="props" v-if="permissionChecker('products_create')"/>-->
                    <router-link @click="reset" to="expenses/create"
                                 class="db-btn h-[37px] text-white bg-primary">
                        <i class="lab lab-line-add-circle"></i>
                        <span>Add Expense</span>
                    </router-link>
                </div>
            </div>

            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-12 md:col-6 xl:col-6">
                            <label for="searchName" class="db-field-title after:hidden">
                                {{ $t("label.name") }}
                            </label>
                            <input id="searchName" v-model="props.search.name" type="text" class="db-field-control"/>
                        </div>

                        <div class="col-12 sm:col-6 md:col-6 xl:col-6">
                            <label for="searchStartDate" class="db-field-title after:hidden">{{
                                    $t('label.date')
                                }}</label>
                            <DatePickerComponent @update:modelValue="handleDate" inputStyle="filter"
                                                 v-model="modelValue"/>
                        </div>

                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-line-search lab-font-size-16"></i>
                                    <span>{{ $t("button.search") }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-line-cross lab-font-size-22"></i>
                                    <span>{{ $t("button.clear") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="db-table-responsive">
                <table class="db-table stripe" id="print">
                    <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">
                            {{ $t('label.name') }}
                        </th>
                        <th class="db-table-head-th">
                            Amount
                        </th>
                        <th class="db-table-head-th">
                            Paid
                        </th>
                        <th class="db-table-head-th">
                            Balance
                        </th>
                        <th class="db-table-head-th">
                            Status
                        </th>
                        <th class="db-table-head-th">
                            Date
                        </th>
                        <th class="db-table-head-th">
                            Category
                        </th>
                        <th class="db-table-head-th">
                            Action
                        </th>
                        <th class="db-table-head-th hidden-print"
                            v-if="permissionChecker('products_show') || permissionChecker('products_edit') || permissionChecker('products_delete')">
                            {{ $t('label.action') }}
                        </th>
                    </tr>
                    </thead>

                    <tbody class="db-table-body" v-if="items.length > 0">
                    <tr class="db-table-body-tr" v-for="expense in items" :key="expense.id">
                        <td class="db-table-body-td">{{ expense.name }}</td>
                        <td class="db-table-body-td">{{ expense.amount }}</td>
                        <td class="db-table-body-td">{{ expense.paid }}</td>
                        <td class="db-table-body-td">{{ expense.amount - expense.paid }}</td>
                        <td class="db-table-body-td">
                            <span :class="paymentStatusClass(expense)">
                                {{ (expense.amount - expense.paid)===0? 'Paid' : 'Unpaid'}}
                            </span>
                        </td>
                        <td class="db-table-body-td">{{ expense.date }}</td>
                        <td class="db-table-body-td">{{ (expense.category)? expense.category.name:'Purchase' }}</td>
                        <td class="db-table-body-td hidden-print"
                            v-if="permissionChecker('expenses_show') || permissionChecker('expenses_edit') || permissionChecker('expenses_delete')">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmIconViewComponent :link="'admin.expenses.show'" :id="expense.id"
                                                     v-if="permissionChecker('expenses_show')"/>
                                <SmIconEditComponent @click="edit(expense)" :link="'admin.expenses.edit'"
                                                     :id="expense.id"
                                                     v-if="permissionChecker('expenses_edit')"/>
                                <SmIconDeleteComponent @click="destroy(expense.id)"
                                                       v-if="permissionChecker('expenses_delete')"/>
                                <SmAddPaymentComponent @click="addPayment(expense.id)" data-modal="#purchasePayment"
                                                       v-if="permissionChecker('expenses_delete')"/>

                                <!--                                <SmViewPaymentComponent @click="paymentList(expense.id)" data-modal="#purchasePaymentList"-->
                                <!--                                                       v-if="permissionChecker('expenses_delete')"/>-->

                                <!--                                <button type="button" data-modal="#purchasePayment" @click="addPayment(expense.id)"-->
                                <!--                                        class="db-table-action">-->
                                <!--                                    <i class="lab lab-line-card text-blue-500 bg-blue-100"></i>-->
                                <!--                                    <span class="db-tooltip">{{ $t('button.add_payment') }}</span>-->
                                <!--                                </button>-->
                                <!--                                <button type="button" data-modal="#purchasePaymentList" @click="paymentList(expense.id)"-->
                                <!--                                        class="db-table-action">-->
                                <!--                                    <i class="lab lab lab-line-menu text-cyan-500 bg-cyan-100"></i>-->
                                <!--                                    <span class="db-tooltip">{{ $t('button.view_payments') }}</span>-->
                                <!--                                </button>-->

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
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import askEnum from "../../../enums/modules/askEnum";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import activityEnum from "../../../enums/modules/activityEnum";
import DatePickerComponent from "../components/DatePickerComponent.vue";
import purchasePaymentStatusEnum from "../../../enums/modules/purchasePaymentStatusEnum";
import SmIconEditComponent from "../components/buttons/SmIconEditComponent.vue";
import SmAddPaymentComponent from "../components/buttons/SmAddPaymentComponent.vue";
import SmViewPaymentComponent from "../components/buttons/SmViewPaymentComponent.vue";
import ExpenseCreateComponent from "./ExpenseCreateComponent.vue";

export default {
    name: "ExpenseListComponent",
    components: {
        SmViewPaymentComponent,
        SmAddPaymentComponent,
        SmIconEditComponent,
        DatePickerComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        ExpenseCreateComponent,
        LoadingComponent,
        SmIconSidebarModalEditComponent,
        SmIconDeleteComponent,
        SmIconViewComponent,
        FilterComponent,
        ExportComponent,
        PrintComponent,
        ExcelComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                purchasePaymentStatusEnum: purchasePaymentStatusEnum,
                statusEnum: statusEnum,
                askEnum: askEnum,
                activityEnum: activityEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                },
                purchasePaymentStatusEnumArray: {
                    [purchasePaymentStatusEnum.PENDING]: this.$t("label.pending"),
                    [purchasePaymentStatusEnum.PARTIAL_PAID]: this.$t("label.partial_paid"),
                    [purchasePaymentStatusEnum.FULLY_PAID]: this.$t("label.fully_paid"),
                }
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t("menu.products"),
            },
            searchProps: {
                paginate: 0,
                order_column: 'id',
                order_type: 'asc'
            },
            props: {
                form: {
                    name: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    name: "",
                    from_date: "",
                    to_date: "",
                }
            },
            modelValue: null
        }
    },
    computed: {
        items: function () {
            return this.$store.getters['expense/lists'];
        },
        purchases: function () {
            return this.$store.getters['purchase/lists'];
        },
        pagination: function () {
            return this.$store.getters['expense/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['expense/page'];
        },
    },
    mounted() {
        this.list();
        this.$store.dispatch('expense/lists', this.searchProps);
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        reset: function () {
            this.$store.dispatch('expense/reset').then().catch();
        },
        addPayment: function (id) {
            appService.modalShow('#purchasePayment');
            this.loading.isActive = true;
            this.$store.dispatch("expense/payment", id);
            this.loading.isActive = false;
        },
        purchasePaymentStatusClass: function (status) {
            return appService.purchasePaymentStatusClass(status);
        },
        paymentList: function (id) {
            appService.modalShow('#purchasePaymentList');
            this.loading.isActive = true;
            this.$store.dispatch("purchase/payment", id);
            this.loading.isActive = false;
        },
        handleDate: function (e) {
            if (e) {
                this.props.search.from_date = e[0];
                this.props.search.to_date = e[1];
            } else {
                this.props.search.from_date = null;
                this.props.search.to_date = null;
            }
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        paymentStatusClass: function (expense) {
            if ((expense.amount) - (expense.paid) === 0) {
                return "db-table-badge text-green-600 bg-green-100";
            } else {
                return "db-table-badge text-red-600 bg-red-100";
            }
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('expense/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (product) {
            this.loading.isActive = true;
            appService.sideDrawerShow();
            this.$store.dispatch('expense/edit', product.id);
            this.loading.isActive = false;
            this.props.form.name = product.name;
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('expense/destroy', {id: id, search: this.props.search}).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('menu.products'));
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
            this.$store.dispatch("expense/export", this.props.search).then((res) => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = 'ExpenseCategory.xlsx';
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
