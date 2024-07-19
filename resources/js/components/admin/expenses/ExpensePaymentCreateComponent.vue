<template>
  <LoadingComponent :props="loading"/>
  <div v-if="errors.global" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mt-4 mx-4 rounded relative"
       role="alert">
    <span class="block sm:inline">{{ errors.global[0] }}</span>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="close">
            <i class="lab lab-fill-close-circle margin-top-5-px"></i>
        </span>
  </div>
  <div class="modal-body">
    <form @submit.prevent="save">
      <div class="form-row">
        <div class="form-col-12 sm:form-col-6">
          <label for="amount" class="db-field-title required">{{
              $t("label.amount")
            }}</label>
          <input v-on:keypress="onlyNumber($event)" v-on:keyup="paymentAmount($event)" v-model="form.amount"
                 v-bind:class="errors.amount ? 'invalid' : ''" type="text" id="amount" class="db-field-control"/>
          <small class="db-field-alert" v-if="errors.amount">{{ errors.amount }}</small>
        </div>
        <div class="col-12 sm:col-6 md:col-6 xl:col-6">
          <label for="searchStartDate" class="db-field-title after:hidden required">{{
              $t('label.date')
            }}</label>
<!--          <DatePickerComponent @update:modelValue="handleDate" :range="false" inputStyle="filter" v-model="form.date"/>-->
            <Datepicker hideInputIcon autoApply v-model="form.date" :enableTimePicker="false"
                        :is24="false" :monthChangeOnScroll="false" utc="false">
                <template #am-pm-button="{ toggle, value }">
                    <button @click="toggle">{{ value }}</button>
                </template>
            </Datepicker>
          <small class="db-field-alert" v-if="errors.date">{{ errors.date }}</small>
        </div>
        <div class="form-col-12 sm:form-col-6">
          <label for="paymentMethod" class="db-field-title required">Payment Method</label>
          <vue-select class="db-field-control f-b-custom-select" id="paymentMethod" v-model="form.paymentMethod"
                      :options="paymentMethods" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                      placeholder="--" search-placeholder="--"/>
          <small class="db-field-alert" v-if="errors.paymentMethod">{{ errors.paymentMethod }}</small>
        </div>
        <div class="form-col-12 sm:form-col-6" v-show="form.paymentMethod===2 || form.paymentMethod===3 || form.paymentMethod===4 ">
          <label for="maximum_purchase_quantity" class="db-field-title required">{{ referenceLabel }}</label>
          <input v-model="form.referenceNo"
                 v-bind:class="errors.referenceNo ? 'invalid' : ''" type="text"
                 id="maximum_purchase_quantity" class="db-field-control">

          <small class="db-field-alert" v-if="errors.referenceNo">
            {{ errors.referenceNo }}
          </small>
        </div>

        <div class="form-col-12">
          <label for="file" class="db-field-title">
            {{ $t("label.file") }}
          </label>
          <input @change="changefile" v-bind:class="errors.file ? 'invalid' : ''" id="file" type="file"
                 class="db-field-control" ref="fileProperty" accept="file/png, file/jpeg, file/jpg"/>
          <small class="db-field-alert" v-if="errors.file">{{
              errors.file
            }}</small>
        </div>

        <div class="form-col-12">
          <div class="modal-btns">
            <button type="button" class="modal-btn-outline modal-close" @click="reset">
              <i class="lab lab-fill-close-circle"></i>
              <span>{{ $t("button.close") }}</span>
            </button>

            <button type="submit" class="db-btn py-2 text-white bg-primary">
              <i class="lab lab-fill-save"></i>
              <span>{{ $t("button.save") }}</span>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import SmModalCreateComponent from "../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../components/LoadingComponent";
import purchasePaymentMethodEnum from "../../../enums/modules/purchasePaymentMethodEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import DatePickerComponent from "../components/DatePickerComponent.vue";

export default {
  name: "ExpensePaymentCreateComponent",
  components: {DatePickerComponent, SmModalCreateComponent, LoadingComponent, Datepicker},
  data() {
    return {
      loading: {
        isActive: false,
      },
      form: {
        purchase_id: "",
        date: "",
        referenceNo: "",
        amount: "",
        paymentMethod: null,
      },
      enums: {
        purchasePaymentMethodEnum: purchasePaymentMethodEnum,
      },
      file: "",
      errors: {},
      dueAmount: "",
      paymentMethods: [],
    };
  },

  mounted() {
    this.loading.isActive = true;
    this.show();
    this.paymentMethods = [
      {id: 1, name: 'Cash'},
      {id: 2, name: 'Mobile Money'},
      {id: 3, name: 'Check'},
      {id: 4, name: 'Bank Transfer'},
    ]
  },
  computed: {
    referenceLabel() {
      switch (this.form.paymentMethod) {
        case 2:
          return 'Phone Number'
        case 3:
          return 'Check No.'
        case 4:
          return 'Account Number'
      }
    }
  },
  methods: {
    changefile: function (e) {
      this.file = e.target.files[0];
    },
    handleDate: function (e) {
      if (e) {
        this.form.date = e[0];
      }
    },

    onlyNumber(e) {
      return appService.onlyNumber(e);
    },
    close: function () {
      this.errors.global = ""
    },
    show: function () {
      if (this.$store.getters["expense/temp"].temp_id) {
        this.loading.isActive = true;
        this.$store.dispatch("expense/show", this.$store.getters["expense/temp"].temp_id).then((res) => {
          this.dueAmount = res.data.data.amount - res.data.data.paid;
          this.form.amount = this.dueAmount;
          this.loading.isActive = false;
        }).catch((err) => {
          this.loading.isActive = false;
        })
      }

    },
    paymentAmount: function (e) {
      // this.form.amount = this.dueAmount;
      // if (e.target.value > this.dueAmount) {
      //   this.errors.amount = "Amount greater than due amount"
      // }
    },
    reset: function () {
      appService.modalHide();
      this.errors = {};
      this.$store.dispatch("expenses/reset");
      this.form = {
        purchase_id: "",
        date: "",
        referenceNo: "",
        amount: "",
        paymentMethod: null,
      };
      this.dueAmount = "";
      if (this.file) {
        this.file = "";
        this.$refs.fileProperty.value = null;
      }
    },
    save: function () {
      try {
        const tempId = this.$store.getters["expense/temp"].temp_id;
        const fd = new FormData();
        fd.append("expense_id", tempId);
        fd.append("date", this.form.date);
        fd.append("referenceNo", this.form.referenceNo);
        fd.append("amount", this.form.amount);
        fd.append("paymentMethod", this.form.paymentMethod);
        if (this.file) {
          fd.append("file", this.file);
        }

        this.loading.isActive = true;
        this.$store.dispatch("expense/addPayment", {form: fd})
            .then((res) => {
              appService.modalHide();
              this.loading.isActive = false;
              alertService.successFlip(
                  0,
                  this.$t("menu.add_payment")
              );
              this.$store.dispatch("expense/reset");
              this.form = {
                purchase_id: "",
                date: "",
                referenceNo: "",
                amount: "",
                paymentMethod: null,
              };
              this.dueAmount = "";
              this.errors = {};
              if (this.file) {
                this.file = "";
                this.$refs.fileProperty.value = null;
              }
            }).catch(({response}) => {

          this.loading.isActive = false;
          if (response.data) {
            Object.entries(response.data.data).forEach(([key, value]) => {
              this.errors = {[key]: value};
            });
          }
        })
      } catch (err) {
        this.loading.isActive = false;
        alertService.error(err);
      }
    },
  },
};
</script>
