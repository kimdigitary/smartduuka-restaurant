<template>
    <LoadingComponent :props="loading"/>

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">Royalty Packages</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage"/>
                <RoyaltyBenefitsCreateComponent :props="props"/>
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <th class="db-table-head-th">Name</th>
                    <th class="db-table-head-th">Description</th>
                    <th class="db-table-head-th">Status</th>
                    <th class="db-table-head-th">Action</th>
                </tr>
                </thead>
                <tbody class="db-table-body" v-if="royaltyPackages?.length > 0">
                <tr class="db-table-body-tr" v-for="royaltyPackage in royaltyPackages" :key="royaltyPackage">
                    <td class="db-table-body-td">{{ royaltyPackage.name }}</td>
                    <td class="db-table-body-td">{{ royaltyPackage.description }}</td>
                    <td class="db-table-body-td">
                            <span :class="statusClass(royaltyPackage.status)">
                                {{ enums.statusEnumArray[royaltyPackage.status] }}
                            </span>
                    </td>
                    <td class="db-table-body-td">
                        <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
<!--                            <SmViewComponent :link="'admin.royalty.show'" :id="royaltyPackage.id"/>-->
                            <SmModalEditComponent @click="edit(royaltyPackage)"/>
                            <SmDeleteComponent @click="destroy(royaltyPackage.id)"/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
            <PaginationSMBox :pagination="pagination" :method="list"/>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <PaginationTextComponent :props="{ page: paginationPage }"/>
                <PaginationBox :pagination="pagination" :method="list"/>
            </div>
        </div>
    </div>
</template>
<script>

import TableLimitComponent from "../../components/TableLimitComponent.vue";
import PaginationSMBox from "../../components/pagination/PaginationSMBox.vue";
import PaginationBox from "../../components/pagination/PaginationBox.vue";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent.vue";
import LoadingComponent from "../../components/LoadingComponent.vue";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent.vue";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent.vue";
import SmViewComponent from "../../components/buttons/SmViewComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum";
import appService from "../../../../services/appService";
import alertService from "../../../../services/alertService";
import RoyaltyCreateComponent from "../RoyaltyCreateComponent.vue";
import RoyaltyBenefitsCreateComponent from "./RoyaltyBenefitsCreateComponent.vue";


export default {
    name: "RoyaltyBenefitsListComponent",
    components: {
        RoyaltyBenefitsCreateComponent,
        RoyaltyCreateComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        SmViewComponent
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
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            props: {
                form: {
                    name: "",
                    description: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                }
            }
        }
    },
    computed: {
        royaltyPackages: function () {
            return this.$store.getters['benefits/lists'];
        },
        pagination: function () {
            return this.$store.getters['benefits/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['benefits/page'];
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('benefits/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (productCategory) {
            appService.modalShow();
            this.loading.isActive = true;
            this.$store.dispatch('benefits/edit', productCategory.id);
            this.props.form = {
                name: productCategory.name,
                parent_id: productCategory.parent_id === 0 ? null : productCategory.parent_id,
                status: productCategory.status,
                description: productCategory.description
            };
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('benefits/destroy', {id: id, search: this.props.search}).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, 'Deleted');
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
        }
    }
}
</script>
