<template>
    <router-link class="db-table-action view db-table-action edit" :to="{ name: this.$props.link, params: { id: this.$props.id } }">
        <i class="fa fa-credit-card"></i>
        <span class="db-tooltip">Add Payment</span>
    </router-link>
</template>

<script>
export default {
    name: "SmAddPaymentComponent",
    props: {
        link: String,
        id: Number
    }
}
</script>
