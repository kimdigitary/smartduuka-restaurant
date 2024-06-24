<template>
    <LoadingComponent :props="loading"/>
    <!--    <SmSidebarModalCreateComponent :props="addButton"/>-->
    <div class="w-1/2 mx-auto">
        <table class="w-full">
            <thead class="bg-primary-light">
            <tr class="h-9">
                <th class="capitalize  font-normal font-rubik text-left pl-3 text-heading"></th>
                <th class="capitalize  font-normal font-rubik text-left px-3 text-heading">
                    {{ $t('label.item') }}
                </th>
                <th class="capitalize  font-normal font-rubik text-left px-3 text-heading">
                    {{ $t('label.qty') }}
                </th>
                <th class="capitalize  font-normal font-rubik text-left px-3 text-heading">
                    {{ $t('label.price') }}
                </th>
            </tr>
            </thead>
            <tbody>

            <tr v-for="(cart, index) in this.cart">
                <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
                    <button @click.prevent="deleteCartItem(index)">
                        <i class="lab lab-trash-line-2 font-fill-danger"></i>
                    </button>
                </td>
                <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]">
                    <h3 class="capitalize  font-rubik text-[#2E2F38]">{{ cart.item_name }}</h3>
                    <p v-if="Object.keys(cart.item_variations).length !== 0">
                            <span v-for="(variation) in cart.item_variations">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading">{{
                                        variation.variation_name
                                    }}:
                                    &nbsp;</span>
                                <span class="capitalize text-[10px] leading-4 font-rubik">{{
                                        variation.name
                                    }}, &nbsp;</span>
                            </span>
                    </p>

                    <ul v-if="cart.item_extras.length > 0 || cart.instruction !== ''">
                        <li v-if="cart.item_extras.length > 0" class="leading-4">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading">
                                    {{ $t('label.extras') }}:
                                </span>
                            <p class="capitalize text-[10px] leading-4 font-rubik">
                                    <span v-for="extra in cart.item_extras">
                                        {{ extra.name }}, &nbsp;
                                    </span>
                            </p>
                        </li>
                        <li v-if="cart.instruction !== ''" class="leading-4">
                                <span class="capitalize text-[10px] leading-4 font-rubik text-heading">
                                    {{ $t('label.instruction') }}:
                                </span>
                            <span class="capitalize text-[10px] leading-4 font-rubik">
                                    {{ cart.instruction }}
                                </span>
                        </li>
                    </ul>
                </td>
                <td class="pl-3 py-3 last:pr-3 border-b border-[#EFF0F6]">
                    <div class="flex items-center indec-group">
                        <button @click.prevent="cartQuantityDecrement(index)"
                                :class="cart.quantity === 1 ? 'fa-trash-can' : 'fa-minus'"
                                class="fa-solid text-[10px] w-[18px] h-[18px] leading-4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-minus"></button>
                        <input v-on:keypress="onlyNumber($event)" v-on:keyup="cartQuantityUp(index, $event)"
                               type="number" :value="cart.quantity"
                               class="text-center w-7  font-semibold text-heading indec-value">
                        <button @click.prevent="cartQuantityIncrement(index)"
                                class="fa-solid fa-plus text-[10px] w-[18px] h-[18px] leading4 text-center rounded-full border transition text-primary border-primary hover:bg-primary hover:text-white indec-plus"></button>
                    </div>
                </td>
                <td class="pl-3 py-3 last:pr-3 align-top border-b border-[#EFF0F6]  font-rubik text-heading">
                    {{
                        currencyFormat(cart.total_convert_price, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position)
                    }}
                </td>
            </tr>
            </tbody>
        </table>
        <button
            class="db-pos-cartBtn w-full h-14 py-4 text-center flex items-center justify-center shadow-xl-top gap-3 bg-primary">
            <i class="lab lab-bag-2 lab-font-size-13 text-white"></i>
            <span class="text-base font-medium font-rubik text-white">
            Update Order {{
                    currencyFormat(totalOrder,
                        setting.site_digit_after_decimal_point, setting.site_default_currency_symbol,
                        setting.site_currency_position)
                }}
        </span>
        </button>
    </div>
</template>

<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent";
import LoadingComponent from "../components/LoadingComponent";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import askEnum from "../../../enums/modules/askEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import {cleanAmount} from "../../../utils/functions";

export default {
    name: "PosOrderEditComponent",
    components: {SmSidebarModalCreateComponent, LoadingComponent},
    props: ['props'],
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                itemTypeEnum: itemTypeEnum,
                askEnum: askEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                },
                itemTypeEnumArray: {
                    [itemTypeEnum.VEG]: this.$t("label.veg"),
                    [itemTypeEnum.NON_VEG]: this.$t("label.non_veg")
                },
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no")
                }
            },
            image: "",
            errors: {},
            cart: [],
            originalOrder: [],
        }
    },
    computed: {
        addButton: function () {
            return {title: this.$t('button.add_item')};
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        itemCategories: function () {
            return this.$store.getters['itemCategory/lists'];
        },
        taxes: function () {
            return this.$store.getters['tax/lists'];
        },
        totalOrder: function () {
            const total = this.cart.reduce((acc, item) => {
                return acc + item.total_convert_price
            }, 0)
            return total
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('posOrder/show', this.$route.params.id).then(res => {
            this.cart = res.data.data.order_items
            this.originalOrder.id = res.data.data.id
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        cleanAmount,
        changeImage: function (e) {
            this.image = e.target.files[0];
        },
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        cartQuantityUp: function (id, e) {
            if (e.target.value > 0) {
                this.$store.dispatch('posCart/quantity', {id: id, status: e.target.value}).then().catch();
            }
        },
        cartQuantityIncrement: function (id) {
            this.cart[id].quantity = this.cart[id].quantity + 1
            this.cartItemTotal(id)
        },
        cartItemTotal: function (id) {
            let price = parseInt(cleanAmount(this.cart[id].price))
            let item_variation_currency_total = parseInt(cleanAmount(this.cart[id].item_variation_currency_total))
            let item_extra_currency_total = parseInt(cleanAmount(this.cart[id].item_extra_currency_total))
            let total = (price + item_variation_currency_total + item_extra_currency_total) * this.cart[id].quantity
            this.cart[id].total_convert_price = total
        },
        cartQuantityDecrement: function (id) {
            if (this.cart[id].quantity > 1) {
                this.cart[id].quantity = this.cart[id].quantity - 1
                this.cartItemTotal(id)
            } else {
                this.deleteCartItem(id)
            }
            // this.$store.dispatch('posCart/quantity', {id: id, status: "decrement"}).then().catch();
        },
        deleteCartItem: function (id) {
            this.cart.filter((item, index) => {
                if (index === id) {
                    this.cart.splice(index, 1)
                }
            })
        },
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch('item/reset').then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                price: "",
                description: "",
                caution: "",
                is_featured: askEnum.YES,
                item_type: itemTypeEnum.VEG,
                item_category_id: null,
                tax_id: null,
                status: statusEnum.ACTIVE,
            };
            if (this.image) {
                this.image = "";
                this.$refs.imageProperty.value = null;
            }
        },
        save: function () {
            try {
                const fd = new FormData();
                fd.append('id', this.originalOrder.id);
                if (this.image) {
                    fd.append('image', this.image);
                    fd.append('items', this.cart);
                }
                const tempId = this.$store.getters['item/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('item/save', {
                    form: fd,
                    search: this.props.search
                }).then((res) => {
                    appService.sideDrawerHide();
                    this.loading.isActive = false;
                    alertService.successFlip((tempId === null ? 0 : 1), this.$t('menu.items'));
                    this.props.form = {
                        name: "",
                        price: "",
                        description: "",
                        caution: "",
                        is_featured: askEnum.YES,
                        item_type: itemTypeEnum.VEG,
                        item_category_id: null,
                        tax_id: null,
                        status: statusEnum.ACTIVE,
                    };
                    this.image = "";
                    this.errors = {};
                    this.$refs.imageProperty.value = null;
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
        }
    }
}
</script>
