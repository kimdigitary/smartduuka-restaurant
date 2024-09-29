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
                        <label for="adults" class="db-field-title required">Adults</label>
                        <input v-model="form.adults" v-bind:class="errors.adults ? 'invalid' : ''" type="text"
                            id="adults" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.adults">{{
                            errors.adults[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="five_to_nine" class="db-field-title required">5-9 Years</label>
                        <input v-model="form.five_to_nine" v-bind:class="errors.five_to_nine ? 'invalid' : ''" type="text"
                            id="five_to_nine" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.five_to_nine">{{
                            errors.five_to_nine[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="less_than_five" class="db-field-title required">Less than 5 Years</label>
                        <input v-model="form.less_than_five" v-bind:class="errors.less_than_five ? 'invalid' : ''"
                            type="text" id="less_than_five" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.less_than_five">{{ errors.less_than_five[0] }}</small>
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
    name: "MeatPricesComponent",
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
                            this.$t("menu.meat_prices")
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
