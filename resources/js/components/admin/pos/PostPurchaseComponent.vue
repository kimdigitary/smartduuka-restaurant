<template>
    <div class="row">
        <div class="col-12">
            <BreadcrumbComponent />
        </div>
        <router-view></router-view>
        <div id="purchasePayment" class="modal">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h3 class="modal-title">{{ $t("menu.add_payment") }}</h3>
                    <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
                </div>
                <PosPaymentCreateComponent v-if="show" />
            </div>
        </div>
        <div id="purchasePaymentList" class="modal">
            <div class="modal-dialog max-w-3xl">
                <div class="modal-header border-b-0">
                    <h3 class="modal-title">POS Order payments </h3>
                    <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
                </div>
                <PosPaymentListComponent v-if="show" />
            </div>
        </div>
    </div>
</template>

<script lang="js">

import PosPaymentCreateComponent from "./PosPaymentCreateComponent.vue";
import PurchasePaymentCreateComponent from "../ingredientsStock/purchase/PurchasePaymentCreateComponent.vue";
import PurchasePaymentListComponent from "../ingredientsStock/purchase/PurchasePaymentListComponent.vue";
import BreadcrumbComponent from "../components/BreadcrumbComponent.vue";
import IngredientPurchasePaymentCreateComponent from "../ingredientsStock/purchaseIngredients/IngredientPurchasePaymentCreateComponent.vue";
import IngredientPurchasePaymentListComponent from "../ingredientsStock/purchaseIngredients/IngredientPurchasePaymentListComponent.vue";
import appService from "../../../services/appService";
import PosPaymentListComponent from "./PosPaymentListComponent.vue";

export default {
    name: 'PostPurchaseComponent',
    components: {
        PosPaymentListComponent,
        PosPaymentCreateComponent,
        PurchasePaymentCreateComponent,
        PurchasePaymentListComponent,
        BreadcrumbComponent,
        IngredientPurchasePaymentCreateComponent,
        IngredientPurchasePaymentListComponent
    },
    computed: {
        show: function () {
            return this.$store.getters["purchase/temp"].temp_id;
        }
    },
    methods: {
        reset: function () {
            this.$store.dispatch("purchase/reset");
            appService.modalHide();
        },
    },
}


</script>
