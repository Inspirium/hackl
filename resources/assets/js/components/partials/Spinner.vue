<template>
    <div class="spinner-box" v-if="show">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Spinner",
        computed: {
            show() {
                return this.$store.state.spinner;
            }
        }
    }
</script>

<style scoped>

</style>