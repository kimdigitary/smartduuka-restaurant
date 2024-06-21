<template>
    <div class="row">
        <div class="col-12">
            <BreadcrumbComponent/>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent";
import appService from "../../../services/appService";
import SubscriptionCreateComponent from "./SubscriptionCreateComponent.vue";
import SubscriptionListComponent from "./SubscriptionListComponent.vue";

export default {
    name: "SubscriptionComponent",
    components: {
        SubscriptionCreateComponent,
        SubscriptionListComponent,
        BreadcrumbComponent
    },
    computed: {
        show: function () {
            return this.$store.getters["expense/temp"].temp_id;
        }
    },
    methods: {
        reset: function () {
            this.$store.dispatch("expense/reset");
            appService.modalHide();
        },
    },
}
</script>

<style scoped></style>
