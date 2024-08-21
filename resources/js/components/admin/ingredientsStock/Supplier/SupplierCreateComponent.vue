<template>
    <LoadingComponent :props="loading"/>
    <SmModalCreateComponent :props="addButton"/>

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.suppliers") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-12">
                            <label for="name" class="db-field-title required">
                                {{ $t("label.company") }} / {{ $t("label.name") }}
                            </label>
                            <input v-model="props.form.name" type="text" id="name" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.name">{{
                                    errors.name[0]
                                }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-12">
                            <label for="email" class="db-field-title">
                                {{ $t("label.email") }}
                            </label>
                            <input v-model="props.form.email" type="text" id="email" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.email">{{
                                    errors.email[0]
                                }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-12">
                            <label for="phone" class="db-field-title">{{ $t("label.phone") }}</label>
                            <input v-model="props.form.phone" type="text" placeholder="eg. 256701234567" id="phone" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.phone">
                                {{ errors.phone[0] }}
                            </small>
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
        </div>
    </div>
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "SupplierCreateComponent",
    components: {SmModalCreateComponent, LoadingComponent},
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            addButton: {
                title: this.$t("button.add_supplier"),
            },
            image: "",
            errors: {},
            flag: "",
            calling_code: "",
            countries: [],
            worldMapData: [],
        };
    },
    mounted() {
        try {
            this.loading.isActive = true;
            setTimeout(() => {
                this.callCountry();
            }, 300);
            this.$store.dispatch('countryCode/lists');
            this.$store.dispatch('company/lists').then(companyRes => {
                this.$store.dispatch('countryCode/show', companyRes.data.data.company_country_code).then(res => {

                    this.props.form.country_code = res.data.data.calling_code;
                    this.calling_code = res.data.data.calling_code;

                    this.props.flag = res.data.data.flag_emoji;
                    this.flag = res.data.data.flag_emoji;

                    this.loading.isActive = false;

                }).catch((err) => {
                    this.loading.isActive = false;

                });
            }).catch((err) => {
                this.loading.isActive = false;
            });
            this.loading.isActive = false;
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    computed: {
        countryCodes: function () {
            return this.$store.getters['countryCode/lists'];
        }
    },
    methods: {
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        callCountry: function () {
            this.worldMapData = require('city-state-country');
            this.countries = this.worldMapData.getAllCountries();
            this.loading.isActive = false;
        },
        callStates: function (countryName) {
            this.props.states = this.worldMapData.getAllStatesFromCountry(countryName);
            this.props.form.state = null;
            this.props.cities = [];
            this.props.form.city = null;
        },
        callCities: function (stateName) {
            this.props.form.city = null;
            this.props.cities = this.worldMapData.getAllCitiesFromState(stateName);
        },
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("supplier/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                company: "",
                name: "",
                email: "",
                phone: "",
                country_code: this.calling_code,
                country: null,
                state: null,
                city: null,
                zip_code: "",
                address: "",
            };
            this.$props.props.states = [];
            this.$props.props.cities = [];

            this.$props.props.form.country_code = this.calling_code;
            this.$props.props.flag = this.flag;
            if (this.image) {
                this.image = "";
                this.$refs.imageProperty.value = null;
            }
        },

        change: function (e) {
            this.props.flag = e.flag_emoji;
            this.$props.props.form.country_code = e.calling_code;

        },

        save: function () {
            try {
                const fd = new FormData();
                this.props.form.company = 'Supplier';
                fd.append('company', this.props.form.company);
                fd.append('name', this.props.form.name);
                fd.append('email', this.props.form.email);
                fd.append('country_code', this.props.form.country_code);
                fd.append('phone', this.props.form.phone);
                fd.append('country', this.props.form.country === null ? '' : this.props.form.country);
                fd.append('state', this.props.form.state === null ? '' : this.props.form.state);
                fd.append('city', this.props.form.city === null ? '' : this.props.form.city);
                fd.append('zip_code', this.props.form.zip_code);
                fd.append('address', this.props.form.address);
                if (this.image) {
                    fd.append('image', this.image);
                }

                const tempId = this.$store.getters["supplier/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('supplier/save', {
                    form: fd,
                    search: this.props.search
                }).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.suppliers")
                    );
                    this.props.form = {
                        company: "",
                        name: "",
                        email: "",
                        phone: "",
                        country_code: this.calling_code,
                        country: null,
                        state: null,
                        city: null,
                        zip_code: "",
                        address: "",
                    };
                    this.$props.props.flag = this.flag;
                    this.image = "";
                    this.errors = {};
                    this.$refs.imageProperty.value = null;
                }).catch((err) => {
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
