<template>
  <LoadingComponent :props="loading" />

  <div class="db-card">
    <div class="db-card-header flex justify-between items-center">
      <h3 class="db-card-title">{{ tenant.name }} Details</h3>
      <button class="btn btn-primary" @click="$router.go(-1)">Back</button>
    </div>
    <div class="db-card-body">
      <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <!-- Tenant Information -->
        <div class="space-y-4">
          <!-- Name -->
          <div class="flex items-center">
            <span class="font-semibold text-gray-700 w-32">Name:</span>
            <p class="text-gray-900">{{ tenant.name }}</p>
          </div>

          <!-- Email -->
          <div class="flex items-center">
            <span class="font-semibold text-gray-700 w-32">Email:</span>
            <p class="text-gray-900">{{ tenant.email }}</p>
          </div>

          <!-- Username -->
          <div class="flex items-center">
            <span class="font-semibold text-gray-700 w-32">Username:</span>
            <p class="text-gray-900">{{ tenant.username }}</p>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import statusEnum from "../../../../enums/modules/statusEnum";
import appService from "../../../../services/appService";

export default {
  name: "TenantShowComponent",
  components: {
    LoadingComponent,
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
    };
  },
  computed: {
    tenant: function () {
      return this.$store.getters["tenant/show"];
    },
  },
  mounted() {
    this.loading.isActive = true;
    this.$store
      .dispatch("tenant/show", this.$route.params.id)
      .then((res) => {
        this.loading.isActive = false;
      })
      .catch((error) => {
        this.loading.isActive = false;
      });
  },
  methods: {
    statusClass: function (status) {
      return appService.statusClass(status);
    },
  },
};
</script>
