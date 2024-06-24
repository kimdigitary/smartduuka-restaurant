<template>
    <LoadingComponent :props="loading"/>
    <div class="col-12">
        <form @submit.prevent="save" class="block w-full">
            <div class="grid grid-cols-2 gap-10">
                <div>
                    <h2 class="text-xl font-bold text-center my-5"> Select Plan</h2>
                    <div class="grid grid-cols-2 gap-2">
                        <div v-for="(plan,index) in this.packages" :key="plan.id">
                            <div
                                @click="setSelected(index)"
                                :class="['block max-w-sm border border-gray-200 rounded-lg shadow cursor-pointer',plan.selected?'bg-shopperz-yellow':'bg-white']">
                                <div class="w-full bg-[#FF8C4B] py-3 rounded-t-lg text-white text-center">
                                    {{ plan.name }}
                                </div>
                                <div class="my-8">
                                    <p class="text-3xl font-extrabold text-center my-5">UGX {{ plan.amount }}</p>
                                    <p class="text-gray-700 text-center">Pay each month</p>
                                    <p class="text-gray-700 text-center">Free startup month</p>
                                    <p class="text-gray-700 text-center">Access to all features</p>
                                    <p class="text-gray-700 text-center">24/7 Support</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-3/4">
                    <h2 class="text-xl font-bold text-center my-2"> Summary</h2>
                    <div class="grid grid-cols-2 gap-2 space-y-2">
                        <p class="font-bold">Sub Total Due:</p>
                        <p>UGX {{ this.selected.amount }}</p>
                        <p class="font-bold">Starting on:</p>
                        <p>{{ this.selected.startsOn }}</p>
                        <p class="font-bold">Ending on:</p>
                        <p>{{ this.selected.endsOn }}</p>
                        <p class="font-bold">Number of months/years in access period:</p>
                        <p>{{ this.selected.months }}</p>
                        <p class="font-bold">Transaction Charge:</p>
                        <p>{{ this.selected.charges }}</p>
                        <p class="font-bold">Total Amount:</p>
                        <p>{{ this.selected.total }}</p>
                    </div>
                    <div>
                        <p> NB: Cancel anytime visiting the subscription page in You Account.</p>
                        <p> By clicking "Pay Now you agree took Terme and are enrolling in automatic pre / of stampton
                            fee of Hax -|- Plus opplample feet starting on date.</p>
                    </div>
                    <button type="submit" class="db-btn py-2 text-white bg-primary w-full">
                        <i class="lab lab-fill-save"></i>
                        <span>PAY NOW</span>
                    </button>
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

import activityEnum from "../../../enums/modules/activityEnum";
import askEnum from "../../../enums/modules/askEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import isRecurringEnum from "../../../enums/modules/isRecuringEnum";

import {addMonths, format} from "date-fns";

export default {
    name: "ExpenseCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent,},
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
            packages: [],
            cardBg: "bg-white",
            selected: {}
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
        this.packages = [
            {id: 1, name: 'Monthly', duration: 1, selected: false, amount: '50,000'},
            {id: 2, name: 'Quarterly', duration: 3, selected: false, amount: '145,000'},
            {id: 3, name: 'Half Yearly', duration: 6, selected: false, amount: '285,000'},
            {id: 4, name: 'Yearly', duration: 12, selected: false, amount: '550,000'},
        ]
        this.loading.isActive = true;
        this.expenseInfo();

        this.$store.dispatch('expenseCategory/depthTrees', {}).then((res) => {
            this.categories = res.data.data;
            console.log(this.categories)
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });

        this.loading.isActive = true;
        this.$store.dispatch('expenseCategory/lists', {
            order_column: 'id',
            order_type: 'asc'
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
                this.$store.dispatch('subscriptions/edit', this.$route.params.id).then((res) => {
                    this.getExpense(res.data.data);
                })
            }
        },
        setSelected: function (index) {
            this.packages.forEach((item, i) => {
                item.selected = i === index;
            })
            this.selected = this.packages[index];
            this.selected.startsOn = format(new Date(), "dd/MM/yyyy");
            const months = this.selected.duration
            const endsOn = addMonths(new Date(), parseInt(months));
            this.selected.months = months;
            this.selected.charges = '0';
            this.selected.total = this.selected.amount;
            this.selected.endsOn = format(endsOn, "dd/MM/yyyy");
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
            this.$store.dispatch('subscriptions/reset').then().catch();
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
            console.log('called')
            try {
                const tempId = this.$store.getters['subscriptions/temp'].temp_id;
                this.loading.isActive = true;
                const data = new FormData();
                Object.entries(this.selected).forEach(([key, value]) => {
                    data.append(key, value);
                });
                this.$store.dispatch('subscriptions/save', {
                    form: data,
                    search: this.props.search
                }).then((res) => {
                    appService.sideDrawerHide();
                    this.loading.isActive = false;
                    alertService.successFlip((tempId === null ? 0 : 1), "Subscription");
                    this.$router.push({name: 'admin.subscriptions'});
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
