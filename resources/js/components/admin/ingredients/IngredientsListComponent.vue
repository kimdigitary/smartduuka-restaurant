<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t('menu.ingredients') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <FilterComponent />
                    <div class="dropdown-group">
                        <ExportComponent />
                        <div class="dropdown-list db-card-filter-dropdown-list">
                            <PrintComponent :props="printObj" />
                            <ExcelComponent :method="xls" />
                        </div>
                    </div>
                    <IngredientsCreateComponent :props="props" v-if="permissionChecker('ingredients_create')" />
                </div>
            </div>

            <div class="table-filter-div">
                <form class="p-4 sm:p-5 mb-5" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="name" class="db-field-title after:hidden">{{
                                $t("label.name")
                            }}</label>
                            <input id="name" v-model="props.search.name" type="text" class="db-field-control" />
                        </div>

                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-search-line lab-font-size-16"></i>
                                    <span>{{ $t("button.search") }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-cross-line-2 lab-font-size-22"></i>
                                    <span>{{ $t("button.clear") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="db-table-responsive">
                <table class="db-table stripe" id="print" :dir="direction">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">
                                {{ $t('label.name') }}
                            </th>
<!--                            <th class="db-table-head-th">-->
<!--                                Buying Price-->
<!--                            </th>-->
<!--                            <th class="db-table-head-th">-->
<!--                                Quantity-->
<!--                            </th>-->
<!--                            <th class="db-table-head-th">-->
<!--                                Alert Quantity-->
<!--                            </th>-->
                            <th class="db-table-head-th">
                                Units
                            </th>
                            <th class="db-table-head-th hidden-print"
                                v-if="permissionChecker('ingredients_delete') || permissionChecker('ingredients_edit') || permissionChecker('ingredients_show')">
                                {{ $t('label.action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="ingredients.length > 0">
                        <tr class="db-table-body-tr" v-for="ingredient in ingredients" :key="ingredient">
                            <td class="db-table-body-td">{{ ingredient.name }}</td>
<!--                            <td class="db-table-body-td">{{ ingredient.buying_price }}</td>-->
<!--                            <td class="db-table-body-td">{{ ingredient.quantity }}</td>-->
<!--                            <td class="db-table-body-td">{{ ingredient.quantity_alert }}</td>-->
                            <td class="db-table-body-td">{{ ingredient.unit }}</td>
                            <td class="db-table-body-td hidden-print"
                                v-if="permissionChecker('ingredients_show') || permissionChecker('ingredients_edit') || permissionChecker('ingredients_delete')">
                                <div class="flex justify-start ingredients-center sm:ingredients-start sm:justify-start gap-1.5">
<!--                                    <SmIconViewComponent :link="'admin.ingredients.show'" :id="ingredient.id"-->
<!--                                        v-if="permissionChecker('ingredients_show')" />-->
                                    <SmIconSidebarModalEditComponent @click="edit(ingredient)"
                                        v-if="permissionChecker('ingredients_edit')" />
                                    <SmIconDeleteComponent @click="destroy(ingredient.id)"
                                        v-if="permissionChecker('ingredients_delete')" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex ingredients-center justify-between border-t border-gray-200 bg-white px-4 py-6">
                <PaginationSMBox :pagination="pagination" :method="list" />
                <div class="hidden sm:flex sm:flex-1 sm:ingredients-center sm:justify-between">
                    <PaginationTextComponent :props="{ page: paginationPage }" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import statusEnum from "../../../enums/modules/statusEnum";
import askEnum from "../../../enums/modules/askEnum";
import itemTypeEnum from "../../../enums/modules/itemTypeEnum";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ExportComponent from "../components/buttons/export/ExportComponent";
import PrintComponent from "../components/buttons/export/PrintComponent";
import ExcelComponent from "../components/buttons/export/ExcelComponent";
import displayModeEnum from "../../../enums/modules/displayModeEnum";
import IngredientsCreateComponent from "./IngredientsCreateComponent.vue";

export default {
    name: "IngredientsListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        IngredientsCreateComponent,
        LoadingComponent,
        SmIconSidebarModalEditComponent,
        SmIconDeleteComponent,
        SmIconViewComponent,
        FilterComponent,
        ExportComponent,
        PrintComponent,
        ExcelComponent
    },
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
                }
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t("menu.ingredients"),
            },
            taxProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                }
            },
            categoryProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc'
                }
            },
            props: {
                form: {
                    name: "",
                    buying_price: "",
                    quantity: "",
                    quantity_alert: "",
                    unit: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    name: "",
                }
            }
        }
    },
    mounted() {
        this.list();
        this.loading.isActive = true;
        this.props.search.page = 1;
        this.$store.dispatch('itemCategory/lists', this.categoryProps.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        this.$store.dispatch('tax/lists', this.taxProps.search).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    computed: {
        ingredients: function () {
            return this.$store.getters['ingredient/lists'];
        },
        pagination: function () {
            return this.$store.getters['ingredient/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['ingredient/page'];
        },
        itemCategories: function () {
            return this.$store.getters["itemCategory/lists"];
        },
        taxes: function () {
            return this.$store.getters['tax/lists'];
        },
        direction: function () {
            return this.$store.getters['frontendLanguage/show'].display_mode === displayModeEnum.RTL ? 'rtl' : 'ltr';
        },

    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.price = "";
            this.props.search.item_category_id = null;
            this.props.search.status = null;
            this.props.search.tax_id = null;
            this.props.search.item_type = null;
            this.props.search.is_featured = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('ingredient/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (ingredient) {
            appService.sideDrawerShow();
            this.loading.isActive = true;
            this.$store.dispatch('ingredient/edit', ingredient.id);
            this.loading.isActive = false;
            this.props.errors = {};
            this.props.form = {
                name: ingredient.name,
                buying_price: ingredient.buying_price,
                quantity: ingredient.quantity,
                alert_quantity: ingredient.quantity_alert,
                unit: ingredient.unit,
            };
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('ingredient/destroy', { id: id, search: this.props.search }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('menu.ingredients'));
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    })
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            })
        },
        xls: function () {
            this.loading.isActive = true;
            this.$store.dispatch("ingredient/export", this.props.search).then((res) => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.ingredients");
                link.click();
                URL.revokeObjectURL(link.href);
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        },
    }
}
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
