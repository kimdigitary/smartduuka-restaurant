<template>
    <router-link class="db-table-action view" :to="{ name: this.$props.link, params: { id: this.$props.id } }">
        <i class="fa fa-money-check-alt"></i>
        <span class="db-tooltip">View Payment</span>
    </router-link>
</template>

<script>
export default {
    name: "SmViewPaymentComponent",
    props: {
        link: String,
        id: Number
    }
}
</script>
