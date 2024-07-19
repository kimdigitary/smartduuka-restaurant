<template>
    <LoadingComponent :props="loading"/>
    <div class="col-12">
        <form @submit.prevent="save" class="block w-full">
            <div class="form-row">
                <div class="form-col-12 sm:form-col-6">
                    <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                    <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text" id="name"
                           class="db-field-control">
                    <small class="db-field-alert" v-if="errors.name">{{ errors.name }}</small>
                </div>
                <div class="form-col-12 sm:form-col-6">
                    <label for="amount" class="db-field-title required">Total Amount</label>
                    <input v-model="props.form.amount" v-bind:class="errors.amount ? 'invalid' : ''" type="number"
                           id="amount"
                           class="db-field-control">
                    <small class="db-field-alert" v-if="errors.amount">{{ errors.amount }}</small>
                </div>

                <div class="col-12 sm:col-6 md:col-6 xl:col-6">
                    <label for="searchStartDate" class="db-field-title after:hidden required">{{
                            $t('label.date')
                        }}</label>
<!--                    <DatePickerComponent @update:modelValue="handleDate" :range="false" inputStyle="filter"-->
<!--                                         v-model="props.form.date"/>-->
                    <Datepicker hideInputIcon autoApply v-model="props.form.date" :enableTimePicker="false"
                                :is24="false" :monthChangeOnScroll="false" utc="false">
                        <template #am-pm-button="{ toggle, value }">
                            <button @click="toggle">{{ value }}</button>
                        </template>
                    </Datepicker>
                    <small class="db-field-alert" v-if="errors.date">{{ errors.date }}</small>
                </div>
                <div class="form-col-12 sm:form-col-6">
                    <label for="expense_category_id" class="db-field-title required">
                        {{ $t("label.category") }}
                    </label>
                    <vue-select ref="expense_category_id" class="db-field-control f-b-custom-select"
                                id="expense_category_id" v-bind:class="errors.category ? 'invalid' : ''"
                                v-model="props.form.category" :options="categories" label-by="option"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="--"
                                search-placeholder="--"/>

                    <small class="db-field-alert" v-if="errors.category">
                        {{ errors.category }}
                    </small>
                </div>

                <div class="form-col-12">
                    <label class="db-field-title ">{{
                            $t("label.attachments")
                        }}</label>
                    <input @change="changeFile" v-bind:class="errors.file ? 'invalid' : ''" type="file"
                           ref="fileProperty" accept="image/png , image/jpeg, image/jpg , application/pdf "
                           class="db-field-control cursor-pointer" id="image"/>
                    <small class="db-field-alert" v-if="errors.file">{{
                            errors.file
                        }}</small>
                </div>

                <div class="form-col-12">
                    <label for="description" class="db-field-title">Expense Note</label>
                    <div :class="errors.note ? 'invalid textarea-error-box-style' : ''">
                        <quill-editor id="description" v-model:value="props.form.note"
                                      class="!h-40 textarea-border-radius"/>
                    </div>
                    <small class="db-field-alert" v-if="errors.note">
                        {{ errors.note }}
                    </small>
                </div>

<!--                <div class="grid gap-2 grid-cols-1 sm:grid-cols-4 form-col-12">-->
<!--                    <div>-->
<!--                        <label class="db-field-title required" for="yes">Is Recurring</label>-->
<!--                        <div class="db-field-radio-group">-->
<!--                            <div class="db-field-radio">-->
<!--                                <div class="custom-radio">-->
<!--                                    <input type="radio" v-model="props.form.isRecurring" id="yes"-->
<!--                                           :value="1" class="custom-radio-field">-->
<!--                                    <span class="custom-radio-span"></span>-->
<!--                                </div>-->
<!--                                <label for="yes" class="db-field-label">{{ $t('label.yes') }}</label>-->
<!--                            </div>-->
<!--                            <div class="db-field-radio">-->
<!--                                <div class="custom-radio">-->
<!--                                    <input type="radio" class="custom-radio-field" v-model="props.form.isRecurring"-->
<!--                                           id="no" :value="0" checked>-->
<!--                                    <span class="custom-radio-span"></span>-->
<!--                                </div>-->
<!--                                <label for="no" class="db-field-label">{{ $t('label.no') }}</label>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="" v-show="props.form.isRecurring">-->
<!--                        <label for="tax_id" class="db-field-title required">Repeats</label>-->
<!--                        <vue-select ref="tax_id" class="db-field-control f-b-custom-select" id="tax_id"-->
<!--                                    v-bind:class="errors.recurs ? 'invalid' : ''" v-model="props.form.recurs"-->
<!--                                    :options="recurringOptions"-->
<!--                                    label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"-->
<!--                                    :clearOnClose="true"-->
<!--                                    placeholder="&#45;&#45;" search-placeholder="&#45;&#45;" :multiple="false"/>-->
<!--                        <small class="db-field-alert" v-if="errors.recurs">{{ errors.recurs }}</small>-->
<!--                    </div>-->
<!--                    <div class="" v-show="props.form.isRecurring">-->
<!--                        <label for="tax_id" class="db-field-title required">Repetitions</label>-->
<!--                        <input v-model="props.form.repetitions" v-bind:class="errors.repetitions ? 'invalid' : ''"-->
<!--                               type="text" id="name"-->
<!--                               class="db-field-control">-->
<!--                        <small class="" v-if="!errors.repetitions">Leave blank to repeat indefinitely</small>-->
<!--                        <small class="db-field-alert" v-if="errors.repetitions">{{ errors.repetitions }}</small>-->
<!--                    </div>-->
<!--                    <div class="" v-show="props.form.isRecurring">-->
<!--                        <label for="searchStartDate" class="db-field-title after:hidden required">Repeats On</label>-->
<!--&lt;!&ndash;                        <DatePickerComponent @update:modelValue="handleRepeatsDate" :range="false" inputStyle="filter"&ndash;&gt;-->
<!--&lt;!&ndash;                                             v-model="props.form.repeatsOn"/>&ndash;&gt;-->
<!--&lt;!&ndash;                        <small class="db-field-alert" v-if="errors.repeatsOn">{{ errors.repeatsOn }}</small>&ndash;&gt;-->
<!--                        <Datepicker hideInputIcon autoApply v-model="props.form.repeatsOn" :enableTimePicker="false"-->
<!--                                    :is24="false" :monthChangeOnScroll="false" utc="false">-->
<!--                            <template #am-pm-button="{ toggle, value }">-->
<!--                                <button @click="toggle">{{ value }}</button>-->
<!--                            </template>-->
<!--                        </Datepicker>-->
<!--                        <small class="db-field-alert" v-if="errors.date">{{ errors.date }}</small>-->
<!--                    </div>-->
<!--                </div>-->
                <p class="my-5 form-col-12">Add Payment</p>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                    <div>
                        <label for="paymentAmount" class="db-field-title">Amount</label>
                        <input v-model="props.form.paymentAmount"
                               v-bind:class="errors.paymentAmount ? 'invalid' : ''" type="number"
                               id="paymentAmount" class="db-field-control">
                        <small class="db-field-alert" v-if="errors.paymentAmount">
                            {{ errors.paymentAmount }}
                        </small>
                    </div>
                    <div class="">
                        <label for="searchStartDate" class="db-field-title after:hidden">Paid On</label>
<!--                        <DatePickerComponent @update:modelValue="handlePaidOnDate" :range="false" inputStyle="filter"-->
<!--                                             v-model="props.form.paidOn"/>-->
                        <Datepicker hideInputIcon autoApply v-model="props.form.paidOn" :enableTimePicker="false"
                                    :is24="false" :monthChangeOnScroll="false" utc="false">
                            <template #am-pm-button="{ toggle, value }">
                                <button @click="toggle">{{ value }}</button>
                            </template>
                        </Datepicker>
                        <small class="db-field-alert" v-if="errors.paidOn">{{ errors.paidOn }}</small>
                    </div>
                    <div class="">
                        <label for="unit" class="db-field-title required">Payment Method</label>
                        <vue-select class="db-field-control f-b-custom-select" id="unit_id"
                                    v-bind:class="errors.paymentMethod ? 'invalid' : ''"
                                    v-model="props.form.paymentMethod" :options="paymentMethods"
                                    label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                    :clearOnClose="true"
                                    placeholder="--" search-placeholder="--"/>
                        <small class="db-field-alert" v-if="errors.paymentMethod">
                            {{ errors.paymentMethod }}
                        </small>
                    </div>
                    <div class=""
                         v-show="props.form.paymentMethod===2 || props.form.paymentMethod===3 || props.form.paymentMethod===4 ">
                        <label for="maximum_purchase_quantity" class="db-field-title required">{{
                                referenceLabel
                            }}</label>
                        <input v-model="props.form.referenceNo"
                               v-bind:class="errors.referenceNo ? 'invalid' : ''" type="text"
                               id="maximum_purchase_quantity" class="db-field-control">

                        <small class="db-field-alert" v-if="errors.referenceNo">
                            {{ errors.referenceNo }}
                        </small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="flex flex-wrap gap-3 mt-4">
                        <button type="submit" class="db-btn py-2 text-white bg-primary">
                            <i class="lab lab-fill-save"></i>
                            <span>{{ $t("label.save") }}</span>
                        </button>
                        <button type="button" class="modal-btn-outline modal-close" @click="reset">
                            <i class="lab lab-fill-close-circle"></i>
                            <span>{{ $t("button.close") }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent";
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import DatePickerComponent from "../components/DatePickerComponent.vue";
import {quillEditor} from "vue3-quill";
import activityEnum from "../../../enums/modules/activityEnum";
import askEnum from "../../../enums/modules/askEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import isRecurringEnum from "../../../enums/modules/isRecuringEnum";
import {paymentMethods, recurringOptions} from "../../../utils/data";
import Datepicker from "@vuepic/vue-datepicker";

export default {
    name: "ExpenseCreateComponent",
    components: {Datepicker, DatePickerComponent, SmSidebarModalCreateComponent, LoadingComponent, quillEditor},
    // props: ['props'],
    data() {
        return {
            tag: "",
            loading: {
                isActive: false
            },
            modal: {
                isShowModal: false
            },
            props: {
                form: {}
            },
            addButton: {
                title: 'Add Expense'
            },
            enums: {
                statusEnum: statusEnum,
                askEnum: isRecurringEnum,
                activityEnum: activityEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                },
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no")
                },
                activityEnumArray: {
                    [activityEnum.ENABLE]: this.$t("label.enable"),
                    [activityEnum.DISABLE]: this.$t("label.disable")
                }
            },
            errors: {},
            productCategories: [],
            categories: [],
            productBrands: [],
            taxes: [],
            barcodes: [],
            recurringOptions: [],
            paymentMethods: [],
        }
    },
    computed: {
        referenceLabel() {
            switch (this.props.form.paymentMethod) {
                case 2:
                    return 'Phone Number'
                case 3:
                    return 'Check No.'
                case 4:
                    return 'Account Number'
            }
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.recurringOptions = recurringOptions
        this.paymentMethods = paymentMethods
        this.expenseInfo();

        this.$store.dispatch('expenseCategory/depthTrees', {
        }).then((res) => {
            this.categories = res.data.data;
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        expenseInfo: function () {
            if (!isNaN(this.$route.params.id)) {
                this.$store.dispatch('expense/edit', this.$route.params.id).then((res) => {
                    this.getExpense(res.data.data);
                })
            }
        },
        getExpense: function (expense) {
            this.props.form.expense = expense.id;
            this.props.form.name = expense.name;
            this.props.form.amount = expense.amount;
            this.props.form.category = expense.category.id;
            this.props.form.paymentAmount = expense.paid;
            this.props.form.isRecurring = expense.isRecurring;
            this.props.form.recurs = expense.recurs;
            this.props.form.repetitions = expense.repetitions;
            this.props.form.repeatsOn = expense.repeats_on;
            this.props.form.paidOn = expense.paid_on;
            this.props.form.paymentMethod = expense.paymentMethod;
            this.props.form.note = expense.note;
            this.props.form.date = expense.date;
            this.props.form.referenceNo = expense.referenceNo;
        },
        reset: function () {
            appService.sideDrawerHide();
            this.$store.dispatch('expense/reset').then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: ""
            };
        },
        handleDate: function (e) {
            if (e) {
                this.props.form.date = e[0];
            }
        },
        handleRepeatsDate: function (e) {
            if (e) {
                this.props.form.repeatsOn = e[0];
            }
        },
        handlePaidOnDate: function (e) {
            if (e) {
                this.props.form.paidOn = e[0];
            }
        },

        save: function () {
            try {
                const tempId = this.$store.getters['expense/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('expense/save', this.props).then((res) => {
                    appService.sideDrawerHide();
                    this.loading.isActive = false;
                    console.log('then', res)
                    alertService.successFlip((tempId === null ? 0 : 1), "Expense Category");
                    // this.props.form = {
                    //   name: ""
                    // }
                    // this.errors = {};
                    // this.reset();
                    this.$router.push({name: 'admin.expenses.list'});
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
            }
        }
    }
}
</script>
