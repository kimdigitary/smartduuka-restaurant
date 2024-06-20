<template>
    <LoadingComponent :props="loading"/>
    <div class="col-12">
        <div id="product" class="db-tab-div active">
            <div class="db-card tab-content active" id="information">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.information') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.name") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ expense.name }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Amount</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ expense.amount }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Date</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ expense.date }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Category</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ expense.category?.name }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Payment Method</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                        getName(expense.paymentMethod, this.paymentMethods)
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Reference</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ expense.referenceNo }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Recurring</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ `${expense.isRecurring == 1?'Yes':'No'}`}}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Recurs</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ getName(expense.recurs, this.recurringOptions)}}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Paid</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ expense.paid}}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Paid On</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ expense.paid_on}}</span>
                            </div>
                        </div>
                        <div class="col-12 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-text mt-2 w-full">
                                    <span class="mt-2 db-list-item-title">Notes</span><br/>
                                    <span class="mt-2" v-html="expense.note"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 hidden-print">
                <div class="flex items-center justify-end gap-6">
                    <button v-if="expense.attachment" @click="download" type="button"
                            class="flex items-center justify-center gap-1.5 h-10 px-6 rounded-3xl text-white bg-primary">
                        <i class="lab lab-fill-download"></i>
                        <span class="capitalize text-sm font-medium">Download Attachment</span>
                    </button>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import {quillEditor} from 'vue3-quill'
import Datepicker from "@vuepic/vue-datepicker";
import statusEnum from "../../../enums/modules/statusEnum";
import askEnum from "../../../enums/modules/askEnum";
import activityEnum from "../../../enums/modules/activityEnum";
import targetService from "../../../services/targetService";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import PrintButtonComponent from "../components/buttons/PrintButtonComponent.vue";
import {getName} from "../../../utils/functions";
import {paymentMethods, recurringOptions} from "../../../utils/data";

export default {
    name: "SubscriptionShowComponent",
    components: {
        PrintButtonComponent,
        LoadingComponent,
        quillEditor,
        Datepicker
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            tabMore: false,
            enums: {
                statusEnum: statusEnum,
                askEnum: askEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no"),
                },
                activityEnumArray: {
                    [activityEnum.ENABLE]: this.$t("label.enable"),
                    [activityEnum.DISABLE]: this.$t("label.disable"),
                }
            },
            deleteIndex: 0,
            imageCount: 0,
            defaultImage: null,
            previewImage: null,
            livePreview: null,
            form: {
                add_to_flash_sale: "",
                discount: "1.5",
                offer_start_date: "",
                offer_end_date: "",
            },
            offerError: {},
            paymentMethods: paymentMethods,
            recurringOptions: recurringOptions,
        };
    },
    computed: {
        expense: function () {
            return this.$store.getters["expense/show"];
        },

    },
    mounted() {
        this.loading.isActive = true;
        this.show();
    },
    methods: {
        getName,
        multiTargets: function (event, commonBtnClass, commonDivClass, targetID) {
            targetService.multiTargets(event, commonBtnClass, commonDivClass, targetID);
        },
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        switchImage: function (link, index) {
            this.livePreview = link;
            this.deleteIndex = index;
        },
        show: function () {
            this.$store.dispatch("expense/show", this.$route.params.id).then((res) => {
                this.defaultImage = res.data.data.preview;
                this.previewImage = res.data.data.preview;
                this.livePreview = res.data.data.image;
                this.imageCount = res.data.data.images.length;
                this.form.add_to_flash_sale = res.data.data.add_to_flash_sale;
                this.form.discount = res.data.data.discount_percentage;
                this.form.offer_start_date = res.data.data.offer_start_date;
                this.form.offer_end_date = res.data.data.offer_end_date;
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;
            });
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store.dispatch("product/uploadImage", {
                        id: this.$route.params.id,
                        form: formData
                    }).then((res) => {
                        alertService.success(this.$t("message.image_update"));
                        this.defaultImage = res.data.data.preview;
                        this.previewImage = res.data.data.preview;
                        this.livePreview = res.data.data.image;
                        this.imageCount = res.data.data.images.length;

                        if (this.$refs.imageProperty) {
                            this.$refs.imageProperty.value = null;
                        }
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.errors.image[0]);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
        deleteImage: function () {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("product/deleteImage", {
                        id: this.$route.params.id,
                        index: this.deleteIndex,
                    }).then((res) => {
                        this.show();
                        this.loading.isActive = false;
                        alertService.success(this.$t("message.image_delete"));
                        this.deleteIndex = 0;
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
        offerSsave: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("product/productOffer", {
                    id: this.$route.params.id,
                    form: this.form,
                }).then((res) => {
                    alertService.success(this.$t("message.product_offer"));
                    this.loading.isActive = false;
                    this.offerError = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.offerError = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        }
    },
};
</script>
