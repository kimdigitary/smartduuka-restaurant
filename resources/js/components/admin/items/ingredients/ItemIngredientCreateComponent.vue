<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Ingredients</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save" class="block w-full">
                    <div class="db-card mb-6">
                        <div class="db-card-header">
                            <h3 class="db-card-title">{{ $t("menu.purchase") }}</h3>
                        </div>
                        <div class="db-card-body">
                            <div class="row">
                                <div class="form-col-12 sm:form-col-6">
                                    <label class="db-field-title required">{{
                                            $t("label.date")
                                        }}</label>
                                    <Datepicker hideInputIcon autoApply v-model="props.form.date" :enableTimePicker="true" :is24="false" :monthChangeOnScroll="false" utc="false" :input-class-name="errors.date ? 'invalid' : ''">
                                        <template #am-pm-button="{ toggle, value }">
                                            <button @click="toggle">{{ value }}</button>
                                        </template>
                                    </Datepicker>
                                    <small class="db-field-alert" v-if="errors.date">
                                        {{ errors.date[0] }}
                                    </small>
                                </div>
                                <!-- <div class="form-col-12 sm:form-col-6">
                                    <label class="db-field-title ">{{
                                        $t("label.reference_no")
                                    }}</label>
                                    <input name="reference_no" v-model="props.form.reference_no" type="text"
                                        class="db-field-control" />
                                    <small class="db-field-alert" v-if="errors.reference_no">{{ errors.reference_no[0] }}</small>
                                </div> -->
                                <div class="form-col-12 sm:form-col-6">
                                    <label class="db-field-title required">{{
                                            $t("label.status")
                                        }}</label>

                                    <vue-select v-model="props.form.status" class="db-field-control f-b-custom-select"
                                                :options="enums.statusEnumArray" label-by="statusKey" value-by="statusValue"
                                                :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                                search-placeholder="--" />
                                    <small class="db-field-alert" v-if="errors.status">{{ errors.status[0] }}</small>
                                </div>
                                <!-- <div class="form-col-12 sm:form-col-6">
                                    <label class="db-field-title ">{{
                                        $t("label.attachments")
                                    }}</label>
                                    <input @change="changeFile" v-bind:class="errors.file ? 'invalid' : ''" type="file"
                                        ref="fileProperty" accept="image/png , image/jpeg, image/jpg , application/pdf "
                                        class="db-field-control cursor-pointer" id="image" />
                                    <small class="db-field-alert" v-if="errors.file">{{
                                        errors.file[0]
                                    }}</small>
                                </div> -->
                                <div class="form-col-12 sm:form-col-6">
                                    <label class="db-field-title required">{{ $t("label.supplier") }}</label>

                                    <vue-select v-model="props.form.supplier_id" class="db-field-control f-b-custom-select"
                                                :options="suppliers" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                                :clearOnClose="true" placeholder="--" search-placeholder="--" />

                                    <small class="db-field-alert" v-if="errors.supplier_id">{{
                                            errors.supplier_id[0]
                                        }}</small>
                                </div>
                                <div class="form-col-12">
                                    <div class="rounded-lg border border-amber-100">
                                        <h4 class="w-full px-4 py-3 font-medium rounded-t-lg bg-amber-100 text-amber-600">
                                            {{ $t("message.selection_message") }}
                                        </h4>
                                        <div class="row p-5">
                                            <div class="form-col-12 ">
                                                <label class="db-field-title required">{{
                                                        $t("label.add_products")
                                                    }}</label>
                                                <div class="relative w-full h-12">
                                                    <button type="button"
                                                            class="lab-line-qrcode absolute top-1/2 -translate-y-1/2 left-4 z-10 cursor-pointer"></button>
                                                    <vue-select class="h-full pr-4 pl-11" v-model="productId" :options="products"
                                                                label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                                                :clearOnClose="true" :placeholder="$t('label.select_one')"
                                                                search-placeholder="--" @update:modelValue="selectProduct($event)" />
                                                </div>
                                                <small class="db-field-alert" v-if="errors.products">{{
                                                        errors.products[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-col-12">
                                    <label class="db-field-title">{{ $t('label.products') }}</label>
                                    <div class="db-table-responsive border rounded-md">
                                        <table class="db-table">
                                            <thead class="db-table-head border-t-0">
                                            <tr class="db-table-head-tr">
                                                <th class="db-table-head-th">
                                                    {{ $t("label.product") }}
                                                </th>
                                                <th class="db-table-head-th">
                                                    {{ $t("label.unit_cost") }}
                                                </th>
                                                <th class="db-table-head-th">
                                                    {{ $t("label.quantity") }}
                                                </th>
                                                <th class="db-table-head-th">
                                                    {{ $t("label.discount") }}
                                                </th>
                                                <th class="db-table-head-th">
                                                    {{ $t("label.taxes") }}
                                                </th>
                                                <th class="db-table-head-th">
                                                    {{ $t("label.sub_total") }}
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
                                                    <span v-if="item.variation_names">
                                                    ( {{ $t('label.variation') }} : {{ item.variation_names }})
                                                </span>
                                                </td>
                                                <td class="db-table-body-td">
                                                    {{ floatFormat(item.price) }}
                                                </td>
                                                <td class="db-table-body-td">
                                                    <input v-on:keypress="onlyNumber($event)" @keyup="updateQuantity(index)"
                                                           v-model="item.quantity" @click=" $event.target.select()" type="number"
                                                           min="1" class="db-field-control">
                                                </td>
                                                <td class="db-table-body-td">
                                                    {{ Number(item.total_discount) === 0 ? "" : "-" }}
                                                    {{ floatFormat(item.total_discount) }}
                                                </td>
                                                <td class="db-table-body-td">
                                                    {{ floatFormat(item.total_tax) }}
                                                    ({{ floatFormat(item.total_tax_rate) }}%)
                                                </td>
                                                <td class="db-table-body-td">
                                                    {{ floatFormat(item.total) }}
                                                </td>
                                                <td class="db-table-body-td">
                                                    <SmIconSidebarModalEditComponent @click.prevent="editDatatable(index)" />
                                                    <SmIconDeleteComponent @click.prevent="removeProduct(index)" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="db-table-body-td" colspan="2">{{ $t('label.total') }}</th>
                                                <th class="db-table-body-td ">
                                                <span class="pl-3">
                                                    {{ Number.isInteger(totalQuantity) ? totalQuantity : 0 }}
                                                </span>
                                                </th>
                                                <th class="db-table-body-td">
                                                    {{ floatFormat(totalDiscount) }}
                                                </th>
                                                <th class="db-table-body-td">
                                                    {{ floatFormat(totalTax) }}
                                                </th>
                                                <th class="db-table-body-td">
                                                    {{ floatFormat(totalPrice) }}
                                                </th>
                                                <th class="db-table-body-td"></th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <ProductModalComponent :item="selectedProduct" :modal="modal" :productId="this.productId"
                                                       v-on:submitItem="modalSubmit" ref="productModal" />
                            </div>
                            <div class="row pt-5">
                                <!-- <div class="form-col-12">
                                    <div :class="errors.note ? 'invalid textarea-error-box-style' : ''">
                                        <label class="db-field-title">
                                            {{ $t("label.note") }}
                                        </label>
                                        <quill-editor v-model:value="props.form.note" class="!h-40 textarea-border-radius" />
                                        <small class="db-field-alert" v-if="errors.note">{{ errors.note[0] }}</small>
                                    </div>
                                </div> -->
                                <div class="form-col-12">
                                    <div class="flex flex-wrap gap-3">
                                        <button v-if="permissionChecker('purchase_create')" type="submit"
                                                class="db-btn text-white bg-primary">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span class="tracking-wide">
                                        {{ $t("button.save") }}
                                    </span>
                                        </button>
                                    </div>
                                </div>
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
import Datepicker from "@vuepic/vue-datepicker";
import ProductModalComponent from "../../components/product/ProductModalComponent.vue";
import SmIconSidebarModalEditComponent from "../../components/buttons/SmIconSidebarModalEditComponent.vue";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent.vue";

export default {
    name: "ItemIngredientCreateComponent",
    components: {
        SmIconDeleteComponent,
        SmIconSidebarModalEditComponent,
        ProductModalComponent,
        Datepicker, SmModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
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
        addButton: function () {
            return { title: "Add ingredients" };
        },
        itemAttributes: function () {
            return this.$store.getters['itemAttribute/lists'];
        },
        setting: function() {
            return this.$store.getters['frontendSetting/lists']
        },
    },
    mounted() {
        this.$store.dispatch("itemAttribute/lists", {
            paginate: 0,
            order_column: 'id',
            order_type: 'desc',
            status: statusEnum.ACTIVE
        });
    },
    methods: {
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("itemVariation/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                price: null,
                item_attribute_id: null,
                caution: "",
                status: statusEnum.ACTIVE,
            };
        },
        floatFormat: function(num) {
            return appService.floatFormat(num, this.setting.site_digit_after_decimal_point);
        },
        save: function () {
            try {
                const tempId = this.$store.getters["itemVariation/temp"].temp_id;
                this.loading.isActive = true;
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
