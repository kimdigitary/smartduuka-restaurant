<template>
    <LoadingComponent :props="loading"/>
    <button type="button" @click="add" data-modal="#IngredientModal" class="db-btn h-[37px] text-white bg-primary">
        <i class="lab lab-add-circle-line"></i>
        <span>Add Ingredients</span>
    </button>

    <div id="IngredientModal" class="modal">
        <div class="modal-dialog w-100">
            <div class="modal-header">
                <h3 class="modal-title">Ingredients</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
            </div>
            <div class="modal-body overflow-x-scroll">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div>
                            <div class="form-col-12">
                                <div class="rounded-lg border border-amber-100">
                                    <div class="row p-5">
                                        <div class="form-col-12 ">
                                            <label class="db-field-title required">
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
                            <div class="form-col-12">
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
                                                        class="db-field-control">
                                            </td>
                                            <td class="db-table-body-td">
                                                <input v-on:keypress="onlyNumber($event)" @keyup="updateQuantity(index)"
                                                       v-model="item.quantity" @click=" $event.target.select()"
                                                       type="number"
                                                        class="db-field-control">
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
                                                        class="db-field-control">
                                            </th>
                                            <th class="db-table-body-td"></th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";
import SmIconSidebarModalEditComponent from "../../components/buttons/SmIconSidebarModalEditComponent.vue";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent.vue";
import AskEnum from "../../../../enums/modules/askEnum";

export default {
    name: "ItemIngredientCreateComponent",
    components: {SmIconDeleteComponent, SmIconSidebarModalEditComponent, LoadingComponent},
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            overallCost: null,
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            errors: {},
            ingredientId: null,
            finalItem: null,
            datatable: [],
        };
    },
    computed: {
        AskEnum() {
            return AskEnum
        },
        addButton: function () {
            return {title: this.$t('button.add_addon')};
        },
        items: function () {
            // return this.$store.getters['item/lists'];
            return this.$store.getters['ingredient/lists'];
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
        totalQuantity: function () {
            return this.datatable.reduce((sum, item) => {
                return sum + +item.quantity;
            }, 0);
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.ingredientsList();
        // this.$store.dispatch('item/lists', {
        this.$store.dispatch('ingredient/lists', {
            paginate: 0,
            order_column: 'id',
            order_type: 'asc',
            status: statusEnum.ACTIVE,
            except: this.props.id
        });
        this.loading.isActive = false;
    },
    methods: {
        add: function () {
            appService.modalShow('#IngredientModal');
        },
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
            appService.modalHide('#IngredientModal');
            this.$store.dispatch("itemIngredients/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                ingredient_item_id: null,
            };
        },

        save: function () {
            try {
                const tempId = this.$store.getters["itemIngredients/temp"].temp_id;
                this.loading.isActive = true;
                this.props.form.ingredients = JSON.stringify(this.datatable);
                this.props.form.overall_cost = this.overallCost;
                this.props.form.ingredient_item_id = this.props.id;
                this.$store.dispatch("itemIngredients/save", this.props).then((res) => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        'Item Ingredient',
                    );
                    this.props.form = {
                        ingredient_item_id: null,
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
