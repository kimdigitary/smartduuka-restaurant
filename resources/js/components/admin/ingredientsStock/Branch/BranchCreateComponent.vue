<template>
    <LoadingComponent :props="loading"/>
    <SmModalCreateComponent :props="addButton"/>

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.branches") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{
                                    $t("label.name")
                                }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                   id="name" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.name">{{
                                    errors.name[0]
                                }}</small>
                        </div>

                        <!--                        <div class="form-col-12 sm:form-col-6">-->
                        <!--                            <label for="state" class="db-field-title required">{{ $t("label.region") }}</label>-->
                        <!--                            <input v-model="props.form.state" v-bind:class="errors.state ? 'invalid' : ''" type="text"-->
                        <!--                                   id="state" class="db-field-control"/>-->
                        <!--                            <small class="db-field-alert" v-if="errors.state">{{-->
                        <!--                                    errors.state[0]-->
                        <!--                                }}</small>-->

                        <!--                                                        <vue-select class="db-field-control f-b-custom-select" id="state"-->
                        <!--                                                                    v-bind:class="errors.state ? 'is-invalid' : ''"-->
                        <!--                                                                    v-model="props.form.state"-->
                        <!--                                                                    :options="regions" label-by="label" value-by="name"-->
                        <!--                                                                    :closeOnSelect="true"-->
                        <!--                                                                    :searchable="true" :clearOnClose="true" placeholder="&#45;&#45;"-->
                        <!--                                                                    search-placeholder="&#45;&#45;"/>-->
                        <!--                            <small class="db-field-alert" v-if="errors.state">{{-->
                        <!--                                    errors.state[0]-->
                        <!--                                }}</small>-->
                        <!--                        </div>-->

                        <div class="form-col-12 sm:form-col-6">
                            <label for="expense_category_id" class="db-field-title required">
                                {{ $t("label.category") }}
                            </label>
                            <vue-select ref="expense_category_id" class="db-field-control f-b-custom-select"
                                        id="expense_category_id" :class="errors.state ? 'invalid' : ''"
                                        v-model="props.form.state" :options="['Central Region', 'Eastern Region', 'Northern Region', 'Western Region']"  :closeOnSelect="true" :clearOnClose="true"
                                        placeholder="--select--"
                                        search-placeholder="--select--"/>

                            <small class="db-field-alert" v-if="errors.state">
                                {{ errors.state }}
                            </small>
                        </div>


                        <div class="form-col-12 sm:form-col-6">
                            <label for="email" class="db-field-title">{{
                                    $t("label.email")
                                }}</label>
                            <input v-model="props.form.email" v-bind:class="errors.email ? 'invalid' : ''" type="email"
                                   id="email" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.email">{{
                                    errors.email[0]
                                }}</small>
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label for="phone" class="db-field-title">{{
                                    $t("label.phone")
                                }}</label>
                            <input v-model="props.form.phone" v-bind:class="errors.phone ? 'invalid' : ''" type="text"
                                   id="phone" class="db-field-control"/>
                            <small class="db-field-alert" v-if="errors.phone">{{
                                    errors.phone[0]
                                }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                               type="radio" class="custom-radio-field"/>
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status"
                                               type="radio" id="inactive" class="custom-radio-field"/>
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12">
                            <label for="address" class="db-field-title required">{{ $t("label.address") }}</label>
                            <textarea v-model="props.form.address" v-bind:class="errors.address ? 'invalid' : ''"
                                      id="address" class="db-field-control"></textarea>
                            <small class="db-field-alert" v-if="errors.address">{{ errors.address[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="branchMap" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.address") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                        @click="mapReset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 map-height">
                            <MapComponent v-if="isMap"
                                          :location="{ lat: props.form.latitude, lng: props.form.longitude }"
                                          :position="location"/>
                        </div>

                        <div class="form-col-12">
                            <label for="apartment" class="db-field-title font-medium text-sm my-0">
                                {{ address }}
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import statusEnum from "../../../../enums/modules/statusEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import MapComponent from "../../../admin/components/MapComponent";

export default {
    name: "BranchCreateComponent",
    components: {SmModalCreateComponent, LoadingComponent, MapComponent},
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            isMap: false,
            address: "",
            errors: {},
            regions: [
                {id: 1, name: 'Central Region', label: 'Central Region'},
                {id: 2, name: 'Eastern Region', label: 'Eastern Region'},
                {id: 3, name: 'Northern Region', label: 'Northern Region'},
                {id: 4, name: 'Western Region', label: 'Western Region'}]
        };
    },
    computed: {
        addButton: function () {
            return {title: this.$t('button.add_branch')};
        },
    },
    mounted() {
        this.regions.forEach(region => {
            console.log(region);
        });
    },
    methods: {
        add: function () {
            appService.modalShow('#branchMap');
        },
        location: function (e) {
            this.address = e.address;
            this.props.form.latitude = e.location.lat;
            this.props.form.longitude = e.location.lng;
            this.props.form.zip_code = e.other.zipCode;
            this.props.form.city = e.other.city;
            this.props.form.state = e.other.state;
            this.props.form.address = e.address;
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("branch/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                email: "",
                phone: "",
                latitude: "",
                longitude: "",
                city: "",
                state: "",
                zip_code: "",
                address: "",
                status: statusEnum.ACTIVE,
            };
        },
        mapReset: function () {
            appService.modalHide('#branchMap');
        },


        save: function () {
            try {
                const tempId = this.$store.getters["branch/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("branch/save", this.props).then((res) => {
                    this.loading.isActive = false;
                    appService.modalHide();
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.branches")
                    );
                    this.props.form = {
                        name: "",
                        email: "",
                        phone: "",
                        latitude: "",
                        longitude: "",
                        city: "",
                        state: "",
                        zip_code: "",
                        address: "",
                        status: statusEnum.ACTIVE,
                    };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
