<template>
    <ItemIngredientCreateComponent :props="addonProps" />
    <br><br>
    <div class="db-card" v-if="ingredients.length > 0">
        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.name") }}</th>
                        <th class="db-table-head-th"> Buying Price</th>
                        <th class="db-table-head-th">Quantity</th>
                        <th class="db-table-head-th">Units</th>
                        <th class="db-table-head-th hidden-print"
                            v-if="permissionChecker('ingredients_delete') || permissionChecker('ingredients_edit') || permissionChecker('ingredients_show')">
                            {{ $t('label.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="ingredients.length > 0">
                    <tr class="db-table-body-tr" v-for="ingredient in ingredients" :key="ingredient">
                        <td class="db-table-body-td">
                            {{ ingredient.name }}<br>
                        </td>
                        <td class="db-table-body-td">
                            {{ ingredient.pivot.buying_price }}
                        </td>
                        <td class="db-table-body-td">
                            {{ ingredient.pivot.quantity }}
                        </td>
                        <td class="db-table-body-td">{{ ingredient.unit }}</td>
                        <td class="db-table-body-td">
                            <span :class="statusClass(ingredient.status)">
                                {{ enums.statusEnumArray[ingredient.status] }}
                            </span>
                        </td>
                        <td class="db-table-body-td">
                            <SmIconDeleteComponent @click="destroy(ingredient.id)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import SmSidebarModalCreateComponent from "../../components/buttons/SmSidebarModalCreateComponent";
import alertService from "../../../../services/alertService";
import statusEnum from "../../../../enums/modules/statusEnum";
import appService from "../../../../services/appService";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent";
import SmIconModalEditComponent from "../../components/buttons/SmIconModalEditComponent";
import ItemIngredientCreateComponent from "./ItemIngredientCreateComponent.vue";

export default {
    name: "ItemIngredientListComponent",
    components: {
        ItemIngredientCreateComponent, SmSidebarModalCreateComponent, SmIconModalEditComponent, SmIconDeleteComponent
    },
    props: {
        item: { type: Number },
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            addonProps: {
                id: this.item,
                form: {
                    ingredient_item_id: null,
                },
                search: {
                    id: this.item,
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'desc',
                }
            },
        }
    },
    mounted() {
        this.list();
    },
    computed: {
        ingredients: function () {
            return this.$store.getters['itemIngredients/lists'];
        }
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch("itemIngredients/lists", this.addonProps.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('itemIngredients/destroy', { item: this.item, id: id, search: this.addonProps.search }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('label.ingredient'));
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
            });
        }
    }
}
</script>
