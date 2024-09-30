<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.tenants") }}</h3>
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
                                id="name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{
                                errors.name[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="phone" class="db-field-title required">{{
                                $t("label.phone")
                            }}</label>
                            <input v-model="props.form.phone" v-bind:class="errors.phone ? 'invalid' : ''" type="text"
                                id="phone" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.phone">{{
                                errors.phone[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="email" class="db-field-title required">{{
                                $t("label.email")
                            }}</label>
                            <input v-model="props.form.email" v-bind:class="errors.email ? 'invalid' : ''" type="email"
                                id="email" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.email">{{
                                errors.email[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="username" class="db-field-title required">{{
                                $t("label.username")
                            }}</label>
                            <input v-model="props.form.username" v-bind:class="errors.username ? 'invalid' : ''" type="text"
                                id="username" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.username">{{
                                errors.username[0]
                            }}</small>
                        </div>

                        <div class="form-col-12">
                            <label for="logo" class="db-field-title required">{{
                                $t("label.logo")
                            }}</label>
                            <input @change="changeImage" v-bind:class="errors.logo ? 'invalid' : ''" id="logo" type="file"
                                class="db-field-control" ref="logoProperty" accept="image/png, image/jpeg, image/jpg" />
                            <small class="db-field-alert" v-if="errors.logo">{{
                                errors.logo[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="tagline" class="db-field-title">Tagline</label>
                            <input v-model="props.form.tagline" v-bind:class="errors.tagline ? 'invalid' : ''" type="text"
                                id="tagline" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.tagline">{{
                                errors.tagline[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="website" class="db-field-title">{{
                                $t("label.website")
                            }}</label>
                            <input v-model="props.form.website" v-bind:class="errors.website ? 'invalid' : ''" type="text"
                                id="website" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.website">{{
                                errors.website[0]
                            }}</small>
                        </div>

                        <div class="form-col-12">
                            <label for="address" class="db-field-title required">{{
                                $t("label.address")
                            }}</label>
                            <textarea v-model="props.form.address" v-bind:class="errors.address ? 'invalid' : ''"
                                id="address" class="db-field-control" rows="4"></textarea>
                            <small class="db-field-alert" v-if="errors.address">{{
                                errors.address[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                            type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status" type="radio"
                                            id="inactive" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
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
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import displayModeEnum from "../../../../enums/modules/displayModeEnum";
import statusEnum from "../../../../enums/modules/statusEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import axios from "axios";

export default {
    name: "TenantCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                displayModeEnum: displayModeEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
            address: "",
            name: "",
            email: "",
            username: "",
            phone: "",
            logo: "",
            tagline: "",
            website: "",
            address: "",
            errors: {}
        };
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_tenant') };
        }
    },

    methods: {
        changeImage: function (e) {
            this.logo = e.target.files[0];
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("tenant/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                phone: "",
                email: "",
                username: "",
                tagline: "",
                website: "",
                address: "",
                status: statusEnum.ACTIVE,
            };
            if (this.logo) {
                this.logo = "";
                this.$refs.imageProperty.value = null;
            }
        },

        save: function () {
            try {
                const fd = new FormData();
                fd.append("name", this.props.form.name ? this.props.form.name : "");
                fd.append("phone", this.props.form.phone ? this.props.form.phone : "");
                fd.append("email", this.props.form.email ? this.props.form.email : "");
                fd.append("username", this.props.form.username ? this.props.form.username : "");
                fd.append("tagline", this.props.form.tagline ? this.props.form.tagline : "");
                fd.append("website", this.props.form.website ? this.props.form.website : "");
                fd.append("address", this.props.form.address ? this.props.form.address : "");
                fd.append("status", this.props.form.status ? this.props.form.status : "");
                if (this.logo) {
                    fd.append("logo", this.logo);
                }

                const tempId = this.$store.getters["tenant/temp"].temp_id;
                this.loading.isActive = true;
                this.$store
                    .dispatch("tenant/save", {
                        form: fd,
                        search: this.props.search,
                    })
                    .then((res) => {
                        appService.modalHide();
                        this.loading.isActive = false;
                        alertService.successFlip(
                            tempId === null ? 0 : 1,
                            this.$t("menu.tenants")
                        );
                        this.props.form = {
                            name: "",
                            phone: "",
                            email: "",
                            username: "",
                            tagline: "",
                            website: "",
                            address: "",
                            status: statusEnum.ACTIVE,
                        };
                        this.logo = "";
                        this.errors = {};
                        this.$refs.imageProperty.value = null;
                    })
                    .catch((err) => {
                        this.loading.isActive = false;
                        this.errors = err.response.data.errors;
                    });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    }
};
</script>
