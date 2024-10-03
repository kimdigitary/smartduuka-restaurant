<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Payment Method</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-12">
                            <label for="name" class="db-field-title required">Name</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-12">
                            <label for="merchant_code" class="db-field-title">Merchant code / phone number</label>
                            <input v-model="props.form.merchant_code" v-bind:class="errors.merchant_code ? 'invalid' : ''" type="text"
                                id="merchant_code" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.merchant_code">{{ errors.merchant_code[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t('button.close') }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t('button.save') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>

import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../../components/LoadingComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum";
import appService from "../../../../services/appService";
import alertService from "../../../../services/alertService";

export default {
    name: "PaymentMethodCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
    props: ['props'],
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            image: "",
            errors: {},
        }
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_payment_method') };
        },
    },
    methods: {
        reset: function () {
            appService.modalHide();
            this.$store.dispatch('analytic/reset').then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                merchant_code: ''
            }
        },
        save: function () {
            try {
                const tempId = this.$store.getters['analytic/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('analytic/savePaymentMethod', this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip((tempId === null ? 0 : 1), this.$t('menu.payment_method'));
                    this.props.form = {
                        name: "",
                        status: statusEnum.ACTIVE,
                    }
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    if (err.response.data.status !== "undefined" && err.response.data.status === false) {
                        appService.modalHide();
                        alertService.error(err.response.data.message)
                    } else {
                        this.errors = err.response.data.errors;
                    }
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err)
            }
        } ,
    }
}
</script>
