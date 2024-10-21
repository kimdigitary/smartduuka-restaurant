<template>

    <div class="my-3">
        <BreadcrumbComponent/>
    </div>

    <router-view></router-view>

</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent.vue";

export default {
    name: "RoyaltyComponent",
    components: {BreadcrumbComponent}
}
</script>

<style scoped></style>
