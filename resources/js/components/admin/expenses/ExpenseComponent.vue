<template>
  <div class="row">
    <div class="col-12">
      <BreadcrumbComponent/>
    </div>
    <router-view></router-view>
    <div id="purchasePayment" class="modal">
      <div class="modal-dialog">
        <div class="modal-header">
          <h3 class="modal-title">Add Payment</h3>
          <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                  @click="reset"></button>
        </div>
        <ExpensePaymentCreateComponent v-if="show"/>
      </div>
    </div>
    <div id="purchasePaymentList" class="modal">
      <div class="modal-dialog max-w-3xl">
        <div class="modal-header border-b-0">
          <h3 class="modal-title">Payments</h3>
          <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                  @click="reset"></button>
        </div>
        <ExpensePurchaseListComponent v-if="show"/>
      </div>
    </div>
  </div>
</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent";
import ExpenseCreateComponent from "./ExpenseCreateComponent.vue";
import ExpenseListComponent from "./ExpensetListComponent.vue";
import appService from "../../../services/appService";
import ExpensePaymentCreateComponent from "./ExpensePaymentCreateComponent.vue";
import ExpensePurchasePaymentCreateComponent from "./payments/ExpensePurchasePaymentCreateComponent.vue";
import ExpensePurchaseListComponent from "./payments/ExpensePurchaseListComponent.vue";
import ExpensePurchasePaymentListComponent from "./payments/ExpensePurchasePaymentListComponent.vue";

export default {
  name: "ExpenseComponent",
  components: {
      ExpensePurchaseListComponent,
    ExpensePaymentCreateComponent,
    ExpenseListComponent,
    ExpenseCreateComponent,
      ExpensePurchasePaymentCreateComponent, ExpensePurchasePaymentListComponent,
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
