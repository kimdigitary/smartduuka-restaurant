<template>
  <LoadingComponent :props="loading" />

  <div class="db-card db-tab-div active">
    <div class="db-card-header border-none">
      <h3 class="db-card-title">{{ $t("menu.tenants") }}</h3>
      <div class="db-card-filter">
        <TableLimitComponent
          :method="list"
          :search="props.search"
          :page="paginationPage"
        />
        <TenantCreateComponent :props="props" />
      </div>
    </div>

    <div class="db-table-responsive">
      <table class="db-table stripe">
        <thead class="db-table-head">
          <tr class="db-table-head-tr">
            <th class="db-table-head-th">
              {{ $t("label.name") }}
            </th>
            <th class="db-table-head-th">
              {{ $t("label.status") }}
            </th>
            <th class="db-table-head-th">
              {{ $t("label.action") }}
            </th>
          </tr>
        </thead>
        <tbody class="db-table-body" v-if="tenants.length > 0">
          <tr class="db-table-body-tr" v-for="tenant in tenants" :key="tenant">
            <td class="db-table-body-td">
              {{ textShortener(tenant.name) }}
            </td>
            <td class="db-table-body-td">
              <span :class="statusClass(tenant.status)">
                {{ enums.statusEnumArray[tenant.status] }}
              </span>
            </td>
            <td class="db-table-body-td">
              <div
                class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5"
              >
                <SmViewComponent :link="'admin.tenant.show'" :id="tenant.id" />
                <SmModalEditComponent @click="edit(tenant)" />
                <SmDeleteComponent
                  @click="destroy(tenant.id)"
                  v-if="site_default_tenant != tenant.id && tenant.id !== 1"
                />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6"
    >
      <PaginationSMBox :pagination="pagination" :method="list" />
      <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <PaginationTextComponent :props="{ page: paginationPage }" />
        <PaginationBox :pagination="pagination" :method="list" />
      </div>
    </div>
  </div>
</template>
<script>
import LoadingComponent from "../../components/LoadingComponent";
import TenantCreateComponent from "./TenantCreateComponent";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";
import TableLimitComponent from "../../components/TableLimitComponent";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent";
import SmViewComponent from "../../components/buttons/SmViewComponent";
import displayModeEnum from "../../../../enums/modules/displayModeEnum";

export default {
  name: "TenantListComponent",
  components: {
    TableLimitComponent,
    PaginationSMBox,
    PaginationBox,
    PaginationTextComponent,
    TenantCreateComponent,
    LoadingComponent,
    SmDeleteComponent,
    SmModalEditComponent,
    SmViewComponent,
  },
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
      props: {
        form: {
          name: "",
          code: "",
          display_mode: displayModeEnum.LTR,
          status: statusEnum.ACTIVE,
        },
        search: {
          paginate: 1,
          page: 1,
          per_page: 10,
          order_column: "id",
          order_type: "desc",
        },
      },
      site_default_tenant: null,
    };
  },
  mounted() {
    this.list();
    this.siteList();
  },
  computed: {
    tenants: function () {
      return this.$store.getters["tenant/lists"];
    },
    pagination: function () {
      return this.$store.getters["tenant/pagination"];
    },
    paginationPage: function () {
      return this.$store.getters["tenant/page"];
    },
  },
  methods: {
    statusClass: function (status) {
      return appService.statusClass(status);
    },
    textShortener: function (text, number = 30) {
      return appService.textShortener(text, number);
    },
    siteList: function () {
      this.loading.isActive = true;
      this.$store
        .dispatch("site/lists")
        .then((res) => {
          this.site_default_tenant = res.data.data.site_default_tenant;
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    list: function (page = 1) {
      this.loading.isActive = true;
      this.props.search.page = page;
      this.$store
        .dispatch("tenant/lists", this.props.search)
        .then((res) => {
          this.loading.isActive = false;
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
    edit: function (tenant) {
      appService.modalShow();
      this.loading.isActive = true;
      this.$store.dispatch("tenant/edit", tenant.id);
      this.props.form = {
        name: tenant.name,
        phone: tenant.phone,
        email: tenant.email,
        username: tenant.username,
        tagline: tenant.tagline,
        website: tenant.website,
        address: tenant.address,
        status: tenant.status,
      };
      this.loading.isActive = false;
    },
    destroy: function (id) {
      appService
        .destroyConfirmation()
        .then((res) => {
          try {
            this.loading.isActive = true;
            this.$store
              .dispatch("tenant/destroy", {
                id: id,
                search: this.props.search,
              })
              .then((res) => {
                this.loading.isActive = false;
                alertService.successFlip(null, this.$t("menu.tenants"));
              })
              .catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
              });
          } catch (err) {
            this.loading.isActive = false;
            alertService.error(err.response.data.message);
          }
        })
        .catch((err) => {
          this.loading.isActive = false;
        });
    },
  },
};
</script>
