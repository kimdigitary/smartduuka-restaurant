<template>
    <LoadingComponent :props="loading" />

    <div id="company" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">Meat Prices</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="network" class="db-field-title required">Telecom Network</label>
                        <input v-model="form.network" v-bind:class="errors.network ? 'invalid' : ''" type="text"
                            id="network" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.network">{{
                            errors.network[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="merchant_code" class="db-field-title required">Merchant Code / Phone Number</label>
                        <input v-model="form.merchant_code" v-bind:class="errors.merchant_code ? 'invalid' : ''" type="text"
                            id="merchant_code" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.merchant_code">{{
                            errors.merchant_code[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="merchant_code" class="db-field-title required">Merchant Code / Phone Number</label>
                        <input v-model="form.merchant_code" v-bind:class="errors.merchant_code ? 'invalid' : ''" type="text"
                            id="merchant_code" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.merchant_code">{{
                            errors.merchant_code[0]
                        }}</small>
                    </div>

                    <div class="form-col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-save"></i>
                            <span>{{ $t("button.save") }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import encryptionEnum from "../../../../enums/modules/encryptionEnum";

export default {
    name: "PaymentMethodsComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                encryptionEnum: encryptionEnum,
                encryptionEnumArray: {
                    [encryptionEnum.SSL]: this.$t("label.ssl"),
                    [encryptionEnum.TLS]: this.$t("label.tls"),
                },
            },
            form: {
                adults: "",
                five_to_nine: "",
                less_than_five: "",
            },
            errors: {},
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store
                .dispatch("mail/meatPrice")
                .then((res) => {
                    this.form = {
                        adults: res.data.data.adults,
                        five_to_nine: res.data.data.five_to_nine,
                        less_than_five: res.data.data.less_than_five,
                    };
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    methods: {
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store
                    .dispatch("mail/saveMeatPrices", this.form)
                    .then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            res.config.method === "put" ?? 0,
                            this.$t("menu.payment_methods")
                        );
                        this.errors = {};
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
