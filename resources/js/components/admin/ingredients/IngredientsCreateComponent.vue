<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" class="drawer">
        <div class="drawer-header">
            <h3 class="drawer-title">Ingredients</h3>
            <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
        </div>
        <div class="drawer-body">
            <form @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                        <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                            id="name" class="db-field-control">
                        <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="price" class="db-field-title required">Buying Price</label>
                        <input v-model="formattedPrice" v-bind:class="errors.buying_price ? 'invalid' : ''" type="text"
                            id="buying_price" class="db-field-control">
                        <small class="db-field-alert" v-if="errors.buying_price">{{ errors.buying_price[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="price" class="db-field-title required">Units</label>
                        <input v-model="props.form.unit" v-bind:class="errors.unit ? 'invalid' : ''" type="text"
                            id="units" class="db-field-control">
                        <small class="db-field-alert" v-if="errors.unit">{{ errors.unit[0] }}</small>
                    </div>
<!--                    <div class="form-col-12 sm:form-col-6">-->
<!--                        <label for="price" class="db-field-title required">Quantity</label>-->
<!--                        <input v-model="props.form.quantity" v-bind:class="errors.quantity ? 'invalid' : ''" type="number"-->
<!--                            id="quantity" class="db-field-control">-->
<!--                        <small class="db-field-alert" v-if="errors.quantity">{{ errors.quantity[0] }}</small>-->
<!--                    </div>-->
                    <div class="form-col-12 sm:form-col-6">
                        <label for="price" class="db-field-title required">Alert Quantity</label>
                        <input v-model="props.form.alert_quantity" v-bind:class="errors.alert_quantity ? 'invalid' : ''" type="number"
                            id="alert_quantity" class="db-field-control">
                        <small class="db-field-alert" v-if="errors.alert_quantity">{{ errors.alert_quantity[0] }}</small>
                    </div>

                    <div class="col-12">
                        <div class="flex flex-wrap gap-3 mt-4">
                            <button type="submit" class="db-btn py-2 text-white bg-primary">
                                <i class="lab lab-save"></i>
                                <span>{{ $t("label.save") }}</span>
                            </button>
                            <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                <i class="lab lab-close"></i>
                                <span>{{ $t("button.close") }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent";
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "IngredientsCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent },
    props: ['props', 'form', 'errors'],
    data() {
        return {
            buyingPrice: '',
            quantity: '',
            alert_quantity: '',
            loading: {
                isActive: false
            },
            errors: {},
        }
    },
    computed: {
        formattedPrice: {
            get() {
                return this.formatNumber(this.buyingPrice)
            },
            set(value) {
                const numericValue = value.replace(/,/g, '');
                if (!isNaN(numericValue) || numericValue === '') {
                    this.buyingPrice = numericValue;
                    this.$emit('update:form', { ...this.props.form, buying_price: numericValue });
                }
            }
        },
        addButton: function () {
            return { title: "Add Ingredient" };
        },
    },
    mounted() {
    },
    methods: {
        formatNumber(value) {
            if (!value) return '';
            return Number(value).toLocaleString();
        },
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch('ingredient/reset').then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                buying_price: "",
                quantity: "",
                alert_quantity: "",
                unit: "",
            };
        },
        save: function () {
            try {
                const fd = new FormData();
                fd.append('name', this.props.form.name);
                fd.append('buying_price', this.buyingPrice);
                fd.append('quantity', this.props.form.quantity);
                fd.append('quantity_alert', this.props.form.alert_quantity);
                fd.append('unit', this.props.form.unit);
                const tempId = this.$store.getters['ingredient/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('ingredient/save', {
                    form: fd,
                    search: this.props.search
                }).then((res) => {
                    appService.sideDrawerHide();
                    this.loading.isActive = false;
                    alertService.successFlip((tempId === null ? 0 : 1), this.$t('menu.items'));
                    this.props.form = {
                        name: "",
                        buying_price: "",
                        quantity: "",
                        alert_quantity: "",
                        unit: "",
                    };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = {};
                    if (err.response && err.response.data && err.response.data.errors) {
                        this.errors = err.response.data.errors;
                    } else {
                        alertService.error(err.response.data.message);
                    }
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err)
            }
        },
        watch: {
            'props.form.buying_price': {
                immediate: true,
                handler(newValue) {
                    this.buyingPrice = newValue || '';
                }
            }
        },
    }
}
</script>
