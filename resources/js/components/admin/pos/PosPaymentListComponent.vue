<template>
    <LoadingComponent :props="loading"/>
    <div class="modal-body p-0">
        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <!--                    <th class="db-table-head-th">{{ $t("label.date") }}</th>-->
                    <th class="db-table-head-th">{{ $t("label.reference_no") }}</th>
                    <th class="db-table-head-th">{{ $t("label.amount") }}</th>
                    <th class="db-table-head-th">{{ $t("label.payment_method") }}</th>
                    <th class="db-table-head-th">
                        {{ $t("label.action") }}
                    </th>
                </tr>
                </thead>
                <tbody class="db-table-body" v-if="purchasePaymentList.length > 0">
                <tr class="db-table-body-tr" v-for="purchasePayment in purchasePaymentList" :key="purchasePayment">
                    <!--                    <td class="db-table-body-td">-->
                    <!--                        {{ purchasePayment.converted_date }}-->
                    <!--                    </td>-->
                    <td class="db-table-body-td">
                        {{ purchasePayment.reference_no }}
                    </td>
                    <td class="db-table-body-td">
                        {{ purchasePayment.amount }} <span v-if="purchasePayment.file">
                                <button class="db-table-action" @click="download(purchasePayment.id)" type="button">
                                    <i class="lab lab-line-link-square text-blue-500"></i>
                                    <span class="db-tooltip">{{ $t('label.file') }}</span>
                                </button>
                            </span>

                    </td>
                    <td class="db-table-body-td">
                        {{ purchasePayment.payment_method }}
                    </td>
                    <td class="db-table-body-td">
                        <SmIconDeleteComponent @click="destroy(purchasePayment.id)"
                                               v-if="permissionChecker('purchase_delete')"/>
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
    <div class="modal-footer">
        <div class="modal-btns">
            <button type="button" class="modal-btn-outline modal-close" @click="reset">
                <i class="lab lab-fill-close-circle"></i>
                <span>{{ $t("button.close") }}</span>
            </button>
        </div>
    </div>
</template>
<script>
import PaginationSMBox from "../components/pagination/PaginationSMBox.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import SmModalCreateComponent from "../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent.vue";
import purchasePaymentMethodEnum from "../../../enums/modules/purchasePaymentMethodEnum";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";

export default {
    name: "PosPaymentListComponent",
    components: {PaginationSMBox, PaginationBox, PaginationTextComponent, SmModalCreateComponent, LoadingComponent, Datepicker, SmIconDeleteComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                purchasePaymentMethodEnum: purchasePaymentMethodEnum,
                purchasePaymentMethodEnumArray: {
                    [purchasePaymentMethodEnum.CASH]: this.$t("label.cash"),
                    [purchasePaymentMethodEnum.CHEQUE]: this.$t("label.cheque"),
                    [purchasePaymentMethodEnum.MOBILE_MONEY]: 'Mobile Money',
                    [purchasePaymentMethodEnum.BANK_TRANSFER]: 'Bank Transfer',
                },
            },
            search: {
                paginate: 1,
                page: 1,
                per_page: 10,
                order_column: 'id',
                order_type: 'desc',
            },
            file: "",
            errors: {},
        };
    },
    computed: {
        purchasePaymentList: function () {
            return this.$store.getters['purchase/viewPayment'];
        },
        pagination: function () {
            return this.$store.getters['purchase/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['purchase/page'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.list();
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        changefile: function (e) {
            this.file = e.target.files[0];
        },
        close: function () {
            this.errors.global = ""
        },

        list: function (page = 1) {
            this.loading.isActive = true;
            this.search.page = page;
            this.$store
                .dispatch("purchase/viewPosPayment", this.search)
                .then((res) => {
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },
        reset: function () {
            this.$store.dispatch("purchase/reset");
            appService.modalHide('#purchasePaymentList');
        },

        download: function (purchasePaymentId) {
            if (purchasePaymentId) {
                this.loading.isActive = true;
                this.$store.dispatch("purchase/paymentDownload", purchasePaymentId).then((res) => {
                    this.loading.isActive = false;

                    let fileType = "";
                    if (res.data.type) {
                        let type = res.data.type;
                        type = type.split("/");
                        fileType = type[1];
                    }

                    if (res.data.size > 0) {
                        const url = window.URL.createObjectURL(
                            new Blob([res.data])
                        );
                        const link = document.createElement("a");
                        link.href = url;
                        link.download =
                            "" + this.$t("menu.purchase_payments") + "." + fileType;
                        link.click();
                        URL.revokeObjectURL(link.href);
                    } else {
                        alertService.info(this.$t("menu.purchase_payments") + " " + this.$t('message.attachment_not_found'));
                    }

                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        },

        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("purchase/paymentDestroy", {
                        purchase_id: this.$store.getters["purchase/temp"].temp_id,
                        id: id,
                        search: this.search,
                    }).then((res) => {
                        appService.modalHide('#purchasePaymentList');
                        this.$store.dispatch("purchase/reset");
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t("menu.purchase_payments"));
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
};
</script>
