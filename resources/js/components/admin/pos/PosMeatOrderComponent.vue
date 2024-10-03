<template>
    <div id="meatModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header hidden-print">
                <h3 class="drawer-title">Meat Order</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="addToCart">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-12">
                            <label for="adults" class="db-field-title required">Adults</label>
                            <input v-model="props.form.adults" v-bind:class="errors.adults ? 'invalid' : ''" type="text"
                                   id="adults" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.adults">{{
                                    errors.adults[0]
                                }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-12">
                            <label for="five_to_nine" class="db-field-title required">5 to 9</label>
                            <input v-model="props.form.five_to_nine" v-bind:class="errors.five_to_nine ? 'invalid' : ''" type="text"
                                   id="five_to_nine" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.five_to_nine">{{
                                    errors.five_to_nine[0]
                                }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-12">
                            <label for="less_than_five" class="db-field-title required">Below 5</label>
                            <input v-model="props.form.less_than_five" v-bind:class="errors.less_than_five ? 'invalid' : ''" type="text"
                                   id="less_than_five" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.less_than_five">{{
                                    errors.less_than_five[0]
                                }}</small>
                        </div>

                        <div class="form-col-12">
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
    </div>
</template>

<script>
import appService from "../../../services/appService";
import statusEnum from "../../../enums/modules/statusEnum";
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";

export default {
    name: "PosMeatOrderComponent",
    components: {
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            itemArrays: [],
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            props: {
                form: {
                    adults: "",
                    five_to_nine: "",
                    less_than_five: "",
                }
            },
            errors: {},
        }
    },
    mounted() {
    },
    methods: {
        reset: function () {
            appService.modalHide();
            this.errors = {};
            this.props.form = {
                adults: "",
                five_to_nine: "",
                less_than_five: "",
            };
        },
        addToCart: function () {
            const order = [{name: 'Adults', quantity: this.props.form.adults, price: 120000}, {
                quantity: this.props.form.five_to_nine,
                name: 'Five to Nine', price: 60000
            }, {quantity: this.props.form.less_than_five, name: 'Less than Five', price: 0}];

            order.forEach((item) => {
                if (item) {
                    this.itemArrays.push({
                        name: item.name,
                        image: '',
                        item_id: 1,
                        quantity: item.quantity,
                        discount: 0,
                        price: item.price * item.quantity,
                        currency_price: item.price * item.quantity,
                        convert_price: item.price * item.quantity,
                        item_variations: undefined,
                        item_extras: [],
                        item_variation_total: 0,
                        item_extra_total: 0,
                        instruction: this.props.form.instruction,
                    });
                }
            })

            if (this.itemArrays.length > 0) {
                this.$store.dispatch("posCart/lists", this.itemArrays).then((res) => {
                    this.item = null;
                    this.temp.name = "";
                    this.temp.image = "";
                    this.temp.item_id = 0;
                    this.temp.quantity = 0;
                    this.temp.discount = 0;
                    this.temp.currency_price = 0;
                    this.temp.convert_price = 0;
                    this.temp.item_variations = {
                        variations: {},
                        names: {}
                    };
                    this.temp.item_extras = {
                        extras: [],
                        names: []
                    };
                    this.temp.item_variation_total = 0;
                    this.temp.item_extra_total = 0;
                    this.temp.total_price = 0;
                    this.temp.instruction = "";
                    this.addons = {};
                    this.itemArrays = [];
                    alertService.success(this.$t('message.add_to_cart'));
                    appService.modalHide('#item-variation-modal');
                }).catch();
            }
        },

        save: function () {
            try {
                this.loading.isActive = true;
                this.$store
                    .dispatch("posOrder/saveCustomer", this.props)
                    .then((res) => {
                        appService.sideDrawerHide();
                        this.loading.isActive = false;
                        alertService.successFlip(0,
                            this.$t("menu.customers")
                        );
                        this.props.form = {
                            name: "",
                            email: "",
                            phone: "",
                            status: statusEnum.ACTIVE,
                            country_code: this.country_code,
                        };
                        this.errors = {};
                        this.$emit('onCustomverCreate', res.data.data.id);
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
}
</script>
