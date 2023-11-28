<template>
    <div class="container">
    Ovo je super admin, go away!
    </div>
</template>

<script>
    export default {
        name: "Index",
        beforeMount() {
            if (this.$store.state.user.id !== 1) {
                this.$router.push('/');
            }
        }
    }
</script>

<style scoped>

</style>