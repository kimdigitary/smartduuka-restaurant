<template>
    <LoadingComponent :props="loading"/>
    <div v-if="errors.global" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mt-4 mx-4 rounded relative"
         role="alert">
        <span class="block sm:inline">{{ errors.global[0] }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="close">
            <i class="lab lab-fill-close-circle margin-top-5-px"></i>
        </span>
    </div>
    <div class="modal-body">
        <form @submit.prevent="save">
            <div class="form-row">
                <div class="form-col-12 sm:form-col-6">
                    <label for="amount" class="db-field-title required">{{
                            $t("label.amount")
                        }}</label>
                    <input v-on:keypress="onlyNumber($event)" v-on:keyup="paymentAmount($event)" v-model="form.amount"
                           v-bind:class="errors.amount ? 'invalid' : ''" type="text" id="amount" class="db-field-control"/>
                    <small class="db-field-alert" v-if="errors.amount">{{
                            errors.amount[0]
                        }}</small>
                </div>
                <div class="form-col-12 sm:form-col-6">
                    <label for="reference_no" class="db-field-title">{{
                            $t("label.reference_no")
                        }}</label>
                    <input v-model="form.reference_no" v-bind:class="errors.reference_no ? 'invalid' : ''" type="text"
                           id="name" class="db-field-control"/>
                    <small class="db-field-alert" v-if="errors.reference_no">{{
                            errors.reference_no[0]
                        }}</small>
                </div>


                <div class="form-col-12 sm:form-col-6">
                    <label for="payment_method" class="db-field-title required">{{
                            $t('label.payment_method')
                        }}</label>
                    <vue-select class="db-field-control f-b-custom-select" id="payment_method" v-model="form.payment_method"
                                :options="paymentMethods" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="--" search-placeholder="--"/>
                    <small class="db-field-alert" v-if="errors.payment_method">{{
                            errors.payment_method[0]
                        }}</small>
                </div>
                <div class="form-col-12 sm:form-col-6">
                    <label for="paid" class="db-field-title">Paid</label>
                    <input v-on:keypress="onlyNumber($event)" v-model="form.paid"
                           v-bind:class="errors.paid ? 'invalid' : ''" type="text" id="paid" class="db-field-control"/>
                </div>
                <div class="form-col-12 sm:form-col-6">
                    <label for="change" class="db-field-title ">Change</label>
                    <input v-model="computedChange"
                           v-bind:class="errors.change ? 'invalid' : ''" type="text" id="change" class="db-field-control"/>
                </div>

                <div class="form-col-12">
                    <div class="modal-btns">
                        <button type="button" class="modal-btn-outline modal-close" @click="reset">
                            <i class="lab lab-fill-close-circle"></i>
                            <span>{{ $t("button.close") }}</span>
                        </button>

                        <button type="submit" class="db-btn py-2 text-white bg-primary">
                            <i class="lab lab-fill-save"></i>
                            <span>{{ $t("button.save") }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>

import SmModalCreateComponent from "../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import purchasePaymentMethodEnum from "../../../enums/modules/purchasePaymentMethodEnum";
import appService from "../../../services/appService";
import purchaseTypeEnum from "../../../enums/modules/purchaseTypeEnum";
import PurchaseTypeEnum from "../../../enums/modules/purchaseTypeEnum";
import alertService from "../../../services/alertService";
import ReceiptComponent from "./ReceiptComponent.vue";

export default {
    name: "PosPaymentCreateComponent",
    components: {ReceiptComponent, SmModalCreateComponent, LoadingComponent, Datepicker},
    data() {
        return {
            loading: {
                isActive: false,
            },
            order: {},
            form: {
                purchase_id: "",
                date: "",
                reference_no: "",
                amount: "",
                paid: '',
                payment_method: null,
            },
            enums: {
                purchasePaymentMethodEnum: purchasePaymentMethodEnum,
            },
            file: "",
            errors: {},
            dueAmount: "",
            paymentMethods: [],
        };
    },

    mounted() {
        this.loading.isActive = true;
        this.show();
    },
    computed: {
        computedChange() {
            return this.form.paid - this.form.amount;
        },
    },
    methods: {
        changefile: function (e) {
            this.file = e.target.files[0];
        },
        onlyNumber(e) {
            return appService.onlyNumber(e);
        },
        close: function () {
            this.errors.global = ""
        },
        show: function () {
            if (this.$store.getters["purchase/temp"].temp_id) {
                this.loading.isActive = true;
                this.$store.dispatch("purchase/showPos", this.$store.getters["purchase/temp"].temp_id).then((res) => {
                    this.form.amount = res.data.data.due_payment;
                    this.dueAmount = res.data.data.due_payment;
                    this.paymentMethods = res.data.data.payment_methods;
                    this.order = res.data.data.order;
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                })
            }
        },
        paymentAmount: function (e) {
            if (e.target.value > this.dueAmount) {
                this.form.amount = this.dueAmount;
            }
        },
        reset: function () {
            appService.modalHide();
            this.errors = {};
            this.$store.dispatch("purchase/reset");
            this.form = {
                purchase_id: "",
                date: "",
                reference_no: "",
                amount: "",
                payment_method: null,
            };
            this.dueAmount = "";
            if (this.file) {
                this.file = "";
                this.$refs.fileProperty.value = null;
            }
        },

        save: function () {
            try {
                const tempId = this.$store.getters["purchase/temp"].temp_id;
                const fd = new FormData();
                fd.append("purchase_id", tempId);
                fd.append("date", this.form.date);
                fd.append("reference_no", this.form.reference_no);
                fd.append("paid", this.form.paid);
                fd.append("change", this.computedChange);
                fd.append("amount", this.form.amount);
                fd.append("payment_method", this.form.payment_method);
                fd.append("type", purchaseTypeEnum.INGREDIENT);
                if (this.file) {
                    fd.append("file", this.file);
                }

                this.loading.isActive = true;
                this.$store
                    .dispatch("purchase/addPaymentPos", {
                        form: fd,
                    })
                    .then((res) => {
                        appService.modalHide();
                        this.loading.isActive = false;
                        alertService.successFlip(
                            0,
                            this.$t("menu.add_payment")
                        );
                        this.$store.dispatch("purchase/reset");
                        this.form = {
                            purchase_id: "",
                            date: "",
                            reference_no: "",
                            amount: "",
                            payment_method: null,
                        };
                        this.dueAmount = "";
                        this.errors = {};
                        if (this.file) {
                            this.file = "";
                            this.$refs.fileProperty.value = null;
                        }
                        this.$store.dispatch("purchase/showReceiptModal");
                        this.$store.dispatch('posOrder/show', this.$store.getters["purchase/selectedOrder"].id).then().catch();
                    })
                    .catch((err) => {
                        this.loading.isActive = false;
                        this.errors = err.response.data.errors;
                    });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        saveIngredient: function () {
            try {
                const tempId = this.$store.getters["purchase/temp"].temp_id;
                const fd = new FormData();
                fd.append("purchase_id", tempId);
                fd.append("date", this.form.date);
                fd.append("reference_no", this.form.reference_no);
                fd.append("amount", this.form.amount);
                fd.append("payment_method", this.form.payment_method);
                fd.append("type", PurchaseTypeEnum.INGREDIENT);
                if (this.file) {
                    fd.append("file", this.file);
                }

                this.loading.isActive = true;
                this.$store
                    .dispatch("purchase/addPayment", {
                        form: fd,
                    })
                    .then((res) => {
                        appService.modalHide();
                        this.loading.isActive = false;
                        alertService.successFlip(
                            0,
                            this.$t("menu.add_payment")
                        );
                        this.$store.dispatch("purchase/reset");
                        this.form = {
                            purchase_id: "",
                            date: "",
                            reference_no: "",
                            amount: "",
                            payment_method: null,
                        };
                        this.dueAmount = "";
                        this.errors = {};
                        if (this.file) {
                            this.file = "";
                            this.$refs.fileProperty.value = null;
                        }
                    })
                    .catch((err) => {
                        this.loading.isActive = false;
                        this.errors = err.response.data.errors;
                    });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
