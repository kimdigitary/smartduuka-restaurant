<template>
    <LoadingComponent :props="loading"/>
    <SmModalCreateComponent :props="addButton"/>

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.variations") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                   id="name" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="price" class="db-field-title required">{{
                                    $t("label.additional_price")
                                }}</label>
                            <input v-on:keypress="numberOnly($event)" v-model="props.form.price"
                                   v-bind:class="errors.price ? 'invalid' : ''" type="text" id="price"
                                   class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.price">{{ errors.price[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="item_attribute" class="db-field-title">{{ $t("label.attribute") }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="item_attribute"
                                        v-bind:class="errors.item_attribute_id ? 'invalid' : ''"
                                        v-model="props.form.item_attribute_id" :options="itemAttributes" label-by="name"
                                        value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                        placeholder="--" search-placeholder="--"/>
                            <small class="db-field-alert" v-if="errors.item_attribute_id">{{
                                    errors.item_attribute_id[0]
                                }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                               type="radio" class="custom-radio-field"/>
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status"
                                               type="radio" id="inactive" class="custom-radio-field"/>
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-scroll" v-if="isStockable===AskEnum.NO">
                            <div class="form-col-12">
                                <div class="rounded-lg border border-amber-100">
                                    <div class="row p-5">
                                        <div class="form-col-12 ">
                                            <label class="db-field-title">
                                                Add Ingredients
                                            </label>
                                            <div class="relative w-full h-12">
                                                <button type="button"
                                                        class="lab-line-qrcode absolute top-1/2 -translate-y-1/2 left-4 z-10 cursor-pointer"></button>
                                                <vue-select class="h-full pr-4 pl-11" v-model="ingredientId"
                                                            :options="ingredients"
                                                            label-by="name" value-by="id" :closeOnSelect="true"
                                                            :searchable="true"
                                                            :clearOnClose="true"
                                                            :placeholder="$t('label.select_one')"
                                                            search-placeholder="--"
                                                            @update:modelValue="selectIngredient($event)"
                                                />
                                            </div>
                                            <small class="db-field-alert"
                                                   v-if="errors.products">{{ errors.products[0] }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-col-12" v-if="isStockable===AskEnum.NO">
                                <label class="db-field-title">Ingredients</label>
                                <div class="db-table-responsive border rounded-md">
                                    <table class="db-table">
                                        <thead class="db-table-head border-t-0">
                                        <tr class="db-table-head-tr">
                                            <th class="db-table-head-th">Name
                                            </th>
                                            <th class="db-table-head-th">
                                                Buying Price
                                            </th>
                                            <th class="db-table-head-th flex flex-col">
                                                Consumption <span>Qty</span>
                                            </th>
                                            <th class="db-table-head-th">
                                                Cost Price
                                            </th>
                                            <th class="db-table-head-th">
                                                {{ $t("label.actions") }}
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="db-table-body">
                                        <tr v-for="(item, index) of datatable" :key="index" class="db-table-body-tr">
                                            <td class="db-table-body-td font-medium">
                                                {{ item.name }}
                                            </td>
                                            <td class="db-table-body-td">
                                                <input v-on:keypress="onlyNumber($event)" @keyup="updateQuantity(index)"
                                                       v-model="item.buying_price" @click=" $event.target.select()"
                                                       type="number"
                                                       min="0" class="db-field-control">
                                            </td>
                                            <td class="db-table-body-td">
                                                <input v-on:keypress="onlyNumber($event)" @keyup="updateQuantity(index)"
                                                       v-model="item.quantity" @click=" $event.target.select()"
                                                       type="number"
                                                       min="0" class="db-field-control">
                                            </td>
                                            <td class="db-table-body-td">
                                                {{ floatFormat(item.total) }}
                                            </td>
                                            <td class="db-table-body-td">
                                                <SmIconDeleteComponent @click.prevent="removeProduct(index)"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="db-table-body-td" colspan="2">Total cost</th>
                                            <th class="db-table-body-td"></th>

                                            <th class="db-table-body-td">
                                                {{ floatFormat(totalPrice) }}
                                            </th>
                                            <th class="db-table-body-td"></th>
                                        </tr>
                                        <tr>
                                            <th class="db-table-body-td text-primary text-xl" colspan="2">Overall cost
                                            </th>
                                            <th class="db-table-body-td"></th>
                                            <th class="db-table-body-td text-xl text-primary">
                                                <input v-on:keypress="onlyNumber($event) "
                                                       v-model="this.overallCost"
                                                       type="number"
                                                       min="0" class="db-field-control">
                                            </th>
                                            <th class="db-table-body-td"></th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-col-12">
                            <label for="caution" class="db-field-title">{{ $t("label.caution") }}</label>
                            <textarea v-model="props.form.caution" v-bind:class="errors.caution ? 'invalid' : ''"
                                      id="caution" class="db-field-control"></textarea>
                            <small class="db-field-alert" v-if="errors.caution">{{ errors.caution[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
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
import statusEnum from "../../../../enums/modules/statusEnum";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent.vue";
import AskEnum from "../../../../enums/modules/askEnum";
import {isProxy, toRaw} from "vue";

export default {
    name: "ItemVariationCreateComponent",
    components: {SmIconDeleteComponent, SmModalCreateComponent, LoadingComponent},
    props: ["props", "isStockable"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            ingredientId: null,
            finalItem: null,
            datatable: [],
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            errors: {},
        };
    },
    computed: {
        AskEnum() {
            return AskEnum
        },
        item: function () {
            return this.$store.getters['item/show'];
        },
        addButton: function () {
            return {title: this.$t('button.add_variation')};
        },
        itemAttributes: function () {
            return this.$store.getters['itemAttribute/lists'];
        },
        ingredients: function () {
            return this.$store.getters['ingredient/lists'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists']
        },
        totalPrice: function () {
            const total = this.datatable.reduce((sum, item) => {
                return sum + +item.total;
            }, 0);
            this.overallCost = total;
            return total;
        },
    },
    watch: {
        item: {
            handler(item) {
                if (item) {
                    this.datatable = []
                    if (isProxy(item)) {
                        const rawItem = toRaw(item);
                        rawItem?.ingredients?.forEach(ingredient => {
                            let item = {
                                name: ingredient.name,
                                quantity: ingredient.pivot.quantity,
                                buying_price: ingredient.pivot.buying_price,
                                discount: 0,
                                ingredient_id: ingredient.pivot.ingredient_id,
                                tax: 0,
                                total_tax: 0,
                                total_tax_rate: 0,
                                total_discount: 0,
                                subtotal: ingredient.pivot.quantity * ingredient.pivot.buying_price,
                                total: (+(ingredient.pivot.quantity * ingredient.pivot.buying_price) + (+0) - (+0)).toFixed(2),
                            }
                            this.datatable.push(item);
                        })
                    }
                }
            },
            immediate: true,
        },
        'props.form.ingredients': {
            handler(ingredients) {
                if (Array.isArray(ingredients)) {
                    this.datatable = []
                    console.log(ingredients)
                    ingredients.forEach(ingredient => {
                        let item = {
                            name: ingredient.name,
                            quantity: ingredient.pivot.quantity,
                            buying_price: ingredient.pivot.buying_price,
                            discount: 0,
                            ingredient_id: ingredient.pivot.ingredient_id,
                            tax: 0,
                            total_tax: 0,
                            total_tax_rate: 0,
                            total_discount: 0,
                            subtotal: ingredient.pivot.quantity * ingredient.pivot.buying_price,
                            total: (+(ingredient.pivot.quantity * ingredient.pivot.buying_price) + (+0) - (+0)).toFixed(2),
                        }
                        this.datatable.push(item);
                    });
                }
            },
            immediate: true,
        },
    },

    mounted() {
        this.$store.dispatch("itemAttribute/lists", {
            paginate: 0,
            order_column: 'id',
            order_type: 'desc',
            status: statusEnum.ACTIVE
        });
        this.ingredientsList();
    },
    methods: {
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        removeProduct: function (productIndex) {
            this.datatable.splice(productIndex, 1);
        },
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        ingredientsList: function () {
            this.$store.dispatch('ingredient/lists').then(res => {
            }).catch((err) => {
            });
        },
        updateQuantity: function (i) {
            const tax = this.datatable[i].tax > 0 ? this.datatable[i].tax : 0;
            this.datatable[i].total_tax = tax * this.datatable[i].quantity;
            this.datatable[i].total_discount = this.datatable[i].discount * this.datatable[i].quantity;
            this.datatable[i].subtotal = (Number(this.datatable[i].quantity) * Number(this.datatable[i].buying_price)).toFixed(2);
            this.datatable[i].total = (+(this.datatable[i].quantity * this.datatable[i].buying_price) + (+this.datatable[i].total_tax) - (+this.datatable[i].total_discount)).toFixed(2);
        },
        floatFormat: function (num) {
            return appService.floatFormat(num, this.setting.site_digit_after_decimal_point);
        },
        ingredientCheck: function () {
            let ingredientExists = null;
            let oldQuantity = null;
            if (this.selectedIngredient.mode === 'edit') {
                ingredientExists = this.datatable[this.dataTableIndex];
            } else {
                if (this.datatable?.length > 0) {
                    ingredientExists = this.datatable.find(p =>
                        p.ingredient_id === this.selectedIngredient.ingredient_id
                    );
                }
            }

            if (ingredientExists) {
                oldQuantity = this.selectedIngredient.mode === 'edit' ? 0 : ingredientExists.quantity;
            }

            let tax = 0;
            let total_tax = 0;
            let total_tax_rate = 0;
            let totalDiscount = 0;

            total_tax = 0

            let finalItem = {
                mode: this.selectedIngredient.mode,
                name: this.selectedIngredient.name,
                quantity: this.selectedIngredient.quantity + oldQuantity,
                buying_price: +this.selectedIngredient.buying_price,
                discount: this.selectedIngredient.discount,
                ingredient_id: this.selectedIngredient.ingredient_id,
                tax: 0,
                total_tax: 0,
                total_tax_rate: 0,
                total_discount: 0,
                subtotal: this.selectedIngredient.quantity * this.selectedIngredient.buying_price,
                total: (+(this.selectedIngredient.quantity * this.selectedIngredient.buying_price) + (+total_tax) - (+totalDiscount)).toFixed(2),
            }
            this.finalItem = finalItem;

            if (!ingredientExists) {
                this.datatable.push(finalItem);
            } else {
                ingredientExists.quantity = finalItem.quantity;
                ingredientExists.buying_price = finalItem.buying_price;
                ingredientExists.discount = finalItem.discount;
                ingredientExists.tax = finalItem.tax;
                ingredientExists.total_tax = finalItem.total_tax;
                ingredientExists.total_discount = finalItem.total_discount;
                ingredientExists.subtotal = finalItem.subtotal;
                ingredientExists.total = finalItem.total;
            }
        },
        selectIngredient: function (id) {
            const ingredient = this.ingredients.find(ingredient => ingredient.id === id);
            if (ingredient) {
                this.selectedIngredient = {
                    name: ingredient.name,
                    quantity: 1,
                    buying_price: ingredient.buying_price,
                    discount: 0,
                    ingredient_id: ingredient.id,
                    mode: 'add'
                }
                this.ingredientCheck();
            }
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("itemVariation/reset").then().catch();
            this.errors = {};
            // this.datatable = [];
            this.$props.props.form = {
                name: "",
                price: null,
                item_attribute_id: null,
                caution: "",
                status: statusEnum.ACTIVE,
            };
        },
        save: function () {
            try {
                const tempId = this.$store.getters["itemVariation/temp"].temp_id;
                this.loading.isActive = true;
                this.props.form.ingredients = JSON.stringify(this.datatable);
                this.props.form.overall_cost = this.overallCost;
                this.props.form.ingredient_item_id = this.props.id;
                this.$store.dispatch("itemVariation/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("label.variation")
                    );
                    this.props.form = {
                        name: "",
                        price: null,
                        item_attribute_id: null,
                        caution: "",
                        status: statusEnum.ACTIVE,
                    };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    }
};
</script>
