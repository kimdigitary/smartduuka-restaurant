<template>
  <aside class="db-sidebar mt-5">
    <div class="db-sidebar-header">
      <button class="fa-solid fa-xmark xmark-btn close-db-menu"></button>
    </div>
    <!--        {{ menus }}-->
    <nav class="db-sidebar-nav">
      <ul v-if="userRole === roleEnum.SUPER_ADMIN">
        <li class="db-sidebar-nav-item">
          <router-link
            :to="{ name: 'super.admin.dashboard' }"
            class="db-sidebar-nav-menu"
          >
            <i class="lab lab-dashboard text-sm"></i>
            <span class="text-base flex-auto"> Dashboard</span>
          </router-link>
        </li>

        <li class="db-sidebar-nav-item">
          <router-link
            :to="{ name: 'admin.tenant' }"
            class="db-sidebar-nav-menu"
          >
            <i class="lab lab-company text-sm"></i>
            <span class="text-base flex-auto"> Tenants</span>
          </router-link>
        </li>

        <li class="db-sidebar-nav-item">
          <router-link
            :to="{ name: 'admin.settings' }"
            class="db-sidebar-nav-menu"
          >
            <i class="lab lab-settings text-sm"></i>
            <span class="text-base flex-auto"> Setting</span>
          </router-link>
        </li>
      </ul>

      <div v-else>
        <ul
          class="db-sidebar-nav-list"
          v-if="menus.length > 0"
          v-for="menu in menus"
          :key="menu"
        >
          <li class="db-sidebar-nav-item" v-if="menu.url === '#'">
            <a href="javascript:void(0);" class="db-sidebar-nav-title">
              {{ menu.name }}
            </a>
          </li>

          <li class="db-sidebar-nav-item" v-else>
            <router-link :to="'/admin/' + menu.url" class="db-sidebar-nav-menu">
              <i class="text-sm" :class="menu.icon"></i>
              <span class="text-base flex-auto"> {{ menu.name }}</span>
            </router-link>
          </li>

          <li
            class="db-sidebar-nav-item"
            v-if="menu.children"
            v-for="children in menu.children"
          >
            <router-link
              :to="'/admin/' + children.url"
              class="db-sidebar-nav-menu"
            >
              <i class="text-sm" :class="children.icon"></i>
              <!--                        <span class="text-base flex-auto">{{ $t('menu.' + children.language) }}</span>-->
              <span class="text-base flex-auto">{{ children.name }}</span>
            </router-link>
          </li>
        </ul>
      </div>
    </nav>
  </aside>
</template>

<script>
// import roleEnum from '../../enums/modules/roleEnum.js';
import roleEnum from "../../../enums/modules/roleEnum.js";

export default {
  name: "BackendMenuComponent",
  data: function () {
    const vuex = JSON.parse(localStorage.getItem("vuex"));

    return {
      activeParentId: 1,
      activeChildId: 0,
      userRole: vuex.auth.authInfo.role_id, // Set the user role from localStorage
    };
  },
  computed: {
    setting: function () {
      return this.$store.getters["frontendSetting/lists"];
    },
    menus: function () {
      return this.$store.getters.authMenu;
    },
    roleEnum() {
      return roleEnum;
    },
  },
};
</script>
