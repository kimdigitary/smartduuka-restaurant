<template>
    <div class="row">
        <div class="col-12">
            <BreadcrumbComponent />
        </div>

        <div class="col-12 md:col-4 xl:col-3">
            <IngredientsStockComponent />
        </div>

        <div class="col-12 md:col-8 xl:col-9">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>


import IngredientsStockComponent from "./IngredientsStockComponent.vue";
import BreadcrumbComponent from "../components/BreadcrumbComponent.vue";

export default {
    name: "IngredientsStockSettingsComponent",
    components: {IngredientsStockComponent, BreadcrumbComponent},
}
</script>
