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
                <PurchasePaymentCreateComponent v-if="show" />
            </div>
        </div>
        <div id="purchasePaymentList" class="modal">
            <div class="modal-dialog max-w-3xl">
                <div class="modal-header border-b-0">
                    <h3 class="modal-title">{{ $t("menu.purchase_payments") }} </h3>
                    <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
                </div>
                <PurchasePaymentListComponent v-if="show" />
            </div>
        </div>
    </div>
</template>

<script lang="js">
import BreadcrumbComponent from "../../components/BreadcrumbComponent.vue";
import appService from "../../../../services/appService";
import IngredientPurchasePaymentCreateComponent from "./IngredientPurchasePaymentCreateComponent.vue";
import IngredientPurchasePaymentListComponent from "./IngredientPurchasePaymentListComponent.vue";
import PurchasePaymentListComponent from "../purchase/PurchasePaymentListComponent.vue";
import PurchasePaymentCreateComponent from "../purchase/PurchasePaymentCreateComponent.vue";

export default {
    name: 'IngredientPurchaseComponent',
    components: {
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
