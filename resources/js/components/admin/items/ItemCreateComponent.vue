<template>
    <LoadingComponent :props="loading"/>
    <SmSidebarModalCreateComponent :props="addButton"/>

    <div id="sidebar" class="drawer" style="width: fit-content">
        <div class="drawer-header">
            <h3 class="drawer-title">{{ $t("menu.items") }}</h3>
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
                        <label for="price" class="db-field-title required">{{ $t('label.price') }}</label>
                        <input v-model="props.form.price" v-bind:class="errors.price ? 'invalid' : ''" type="text"
                               id="price" class="db-field-control">
                        <small class="db-field-alert" v-if="errors.price">{{ errors.price[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="item_category_id" class="db-field-title required">{{ $t("label.category") }}</label>
                        <vue-select class="db-field-control f-b-custom-select" id="item_category_id"
                                    v-bind:class="errors.item_category_id ? 'invalid' : ''"
                                    v-model="props.form.item_category_id" :options="itemCategories" label-by="name"
                                    value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                    placeholder="--"
                                    search-placeholder="--"/>
                        <small class="db-field-alert" v-if="errors.item_category_id">{{
                                errors.item_category_id[0]
                            }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="tax_id" class="db-field-title">{{ $t("label.tax") }} ({{
                                $t("label.including")
                            }})</label>
                        <vue-select class="db-field-control f-b-custom-select" id="tax_id"
                                    v-bind:class="errors.tax_id ? 'invalid' : ''" v-model="props.form.tax_id"
                                    :options="taxes"
                                    label-by="code" value-by="id" :closeOnSelect="true" :searchable="true"
                                    :clearOnClose="true"
                                    placeholder="--" search-placeholder="--"/>
                        <small class="db-field-alert" v-if="errors.tax_id">{{ errors.tax_id[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title">{{ $t("label.image") }}</label>
                        <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''" id="image" type="file"
                               class="db-field-control" ref="imageProperty" accept="image/png, image/jpeg, image/jpg">
                        <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="veg">{{ $t("label.item_type") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" v-model="props.form.item_type" id="veg"
                                           :value="enums.itemTypeEnum.VEG" class="custom-radio-field">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="veg" class="db-field-label">Food</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" class="custom-radio-field" v-model="props.form.item_type"
                                           id="nonVeg" :value="enums.itemTypeEnum.NON_VEG">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="nonVeg" class="db-field-label">Beverage</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="yes">{{ $t("label.is_featured") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" v-model="props.form.is_featured" id="yes"
                                           :value="enums.askEnum.YES" class="custom-radio-field">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="yes" class="db-field-label">{{ $t('label.yes') }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" class="custom-radio-field" v-model="props.form.is_featured"
                                           id="no" :value="enums.askEnum.NO">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="no" class="db-field-label">{{ $t('label.no') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title">{{ $t("label.status") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" v-model="props.form.status" id="active"
                                           :value="enums.statusEnum.ACTIVE" class="custom-radio-field">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="active" class="db-field-label">{{ $t('label.active') }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" class="custom-radio-field" v-model="props.form.status"
                                           id="inactive" :value="enums.statusEnum.INACTIVE">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="inactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="yes">Is Stockable</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" v-model="props.form.is_stockable" id="yes_is_stockable"
                                           :value="enums.askEnum.YES" class="custom-radio-field">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="yes_is_stockable" class="db-field-label">{{ $t('label.yes') }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input type="radio" class="custom-radio-field" v-model="props.form.is_stockable"
                                           id="no_is_stockable" :value="enums.askEnum.NO">
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="no_is_stockable" class="db-field-label">{{ $t('label.no') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-col-12 sm:form-col-6" v-if="props.form.is_stockable===AskEnum.YES">
                        <label for="buying_price" class="db-field-title required">Buying price</label>
                        <input v-model="props.form.buying_price" v-bind:class="errors.buying_price ? 'invalid' : ''"
                               type="text"
                               id="buying_price" class="db-field-control">
                        <small class="db-field-alert" v-if="errors.buying_price">{{ errors.buying_price[0] }}</small>
                    </div>
                    <div v-if="props.form.is_stockable===AskEnum.NO">
                        <div class="form-col-12">
                            <div class="rounded-lg border border-amber-100">
                                <div class="row p-5">
                                    <div class="form-col-12 ">
                                        <label class="db-field-title ">
                                            Add Ingredients (Optional)
                                        </label>
                                        <div class="relative w-full h-12">
                                            <button type="button"
                                                    class="lab-line-qrcode absolute top-1/2 -translate-y-1/2 left-4 z-10 cursor-pointer"></button>
                                            <vue-select class="h-full pr-4 pl-11" v-model="ingredientId"
                                                        :options="ingredients"
                                                        label-by="name" value-by="id" :closeOnSelect="true"
                                                        :searchable="true"
                                                        :clearOnClose="true" :placeholder="$t('label.select_one')"
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
                                                   min="1" class="db-field-control">
                                        </td>
                                        <td class="db-table-body-td">
                                            <input v-on:keypress="onlyNumber($event)" @keyup="updateQuantity(index)"
                                                   v-model="item.quantity" @click=" $event.target.select()"
                                                   type="number"
                                                   min="1" class="db-field-control">
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
                                        <th class="db-table-body-td text-primary text-xl" colspan="2">Overall cost</th>
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
                        <label for="caution" class="db-field-title">{{ $t("label.caution") }}</label>
                        <textarea v-model="props.form.caution" v-bind:class="errors.caution ? 'invalid' : ''"
                                  id="caution" rows="2" class="db-field-control"></textarea>
                        <small class="db-field-alert" v-if="errors.caution">{{
                                errors.caution[0]
                            }}</small>
                    </div>

                    <div class="form-col-12">
                        <label for="description" class="db-field-title">{{ $t("label.description") }}</label>
                        <textarea v-model="props.form.description" v-bind:class="errors.description ? 'invalid' : ''"
                                  id="description" class="db-field-control"></textarea>
                        <small class="db-field-alert" v-if="errors.description">{{
                                errors.description[0]
                            }}</small>
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
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import askEnum from "../../../enums/modules/askEnum";
import AskEnum from "../../../enums/modules/askEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent.vue";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent.vue";
import {isProxy, toRaw} from "vue";

export default {
    name: "ItemCreateComponent",
    components: {
        SmIconDeleteComponent,
        SmIconSidebarModalEditComponent,
        SmSidebarModalCreateComponent,
        LoadingComponent
    },
    props: ['props', 'form', 'errors', 'item'],
    data() {
        return {
            internalPrice: '',
            internalBuyingPrice: '',
            loading: {
                isActive: false
            },
            ingredients: [],
            data: this.props,
            ingredientId: null,
            finalItem: null,
            overallCost: null,
            datatable: [],
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
            selectedIngredient: null,
        }
    },
    computed: {
        AskEnum() {
            return AskEnum
        },
        ingredients: function () {
            return this.$store.getters['ingredient/lists'];
        },
        formattedPrice: {
            get() {
                return this.formatNumber(this.internalPrice)
            },
            set(value) {
                const numericValue = value.replace(/,/g, '');
                if (!isNaN(numericValue) || numericValue === '') {
                    this.internalPrice = numericValue;
                    this.$emit('update:form', {...this.props.form, price: numericValue});
                }
            }
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
        totalDiscount: function () {
            return this.datatable.reduce((sum, item) => {
                return sum + +item.total_discount;
            }, 0);
        },
        totalTax: function () {
            return this.datatable.reduce((sum, item) => {
                return sum + +item.total_tax;
            }, 0);
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists']
        },
        formattedBuyingPrice: {
            get() {
                return this.formatNumber(this.internalBuyingPrice)
            },
            set(value) {
                const numericValue = value.replace(/,/g, '');
                if (!isNaN(numericValue) || numericValue === '') {
                    this.internalBuyingPrice = numericValue;
                    this.$emit('update:form', {...this.props.form, buying_price: numericValue});
                }
            }
        },
        addButton: function () {
            return {title: this.$t('button.add_item')};
        },
        itemCategories: function () {
            return this.$store.getters['itemCategory/lists'];
        },
        taxes: function () {
            return this.$store.getters['tax/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.ingredientsList();
        this.$store.dispatch('itemCategory/lists', {
            order_column: 'sort',
            order_type: 'asc',
            status: statusEnum.ACTIVE
        });
        this.$store.dispatch('tax/lists', {
            order_column: 'id',
            order_type: 'asc'
        });
        this.loading.isActive = false;
    },
    methods: {
        formatNumber(value) {
            if (!value) return '';
            return Number(value).toLocaleString();
        },
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        ingredientsList: function () {
            this.$store.dispatch('ingredient/lists').then(res => {
            }).catch((err) => {
            });
        },
        removeProduct: function (productIndex) {
            this.datatable.splice(productIndex, 1);
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
        changeImage: function (e) {
            this.image = e.target.files[0];
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
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch('item/reset').then().catch();
            this.errors = {};
            this.datatable = [];
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
                fd.append('name', this.props.form.name);
                fd.append('price', this.props.form.price);
                fd.append('item_category_id', this.props.form.item_category_id == null ? '' : this.props.form.item_category_id);
                fd.append('tax_id', this.props.form.tax_id == null ? '' : this.props.form.tax_id);
                fd.append('item_type', this.props.form.item_type);
                fd.append('is_featured', this.props.form.is_featured);
                if (this.props.form.is_stockable === AskEnum.YES) {
                    fd.append('buying_price', this.props.form.buying_price);
                }
                fd.append('is_stockable', this.props.form.is_stockable);
                fd.append('description', this.props.form.description);
                fd.append('caution', this.props.form.caution);
                fd.append('order', 1);
                fd.append('status', this.props.form.status);
                fd.append('ingredients', JSON.stringify(this.datatable));
                fd.append('overall_cost', this.overallCost);
                if (this.image) {
                    fd.append('image', this.image);
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
        },
        updateIngredients(value) {
            if (this.props && this.props.form && this.props.form.ingredients) {
                this.ingredients = this.props.form.ingredients;
                this.datatable = [];
                if (isProxy(this.ingredients)) {
                    toRaw(this.ingredients).forEach((ingredient) => {
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
        }
    },
    watch: {
        props: {
            deep: true,
            immediate: true,
            handler(newValue, oldValue) {
                this.updateIngredients(newValue);
            }
        },
        'props.form.price': {
            immediate: true,
            handler(newValue) {
                this.internalPrice = newValue || '';
            }
        },
    },
}
</script>
