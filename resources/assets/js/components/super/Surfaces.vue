<template>

</template>

<script>
    export default {
        name: "Surfaces",
        beforeMount() {
            if (this.$store.state.user.id !== 1) {
                this.$router.push('/');
            }
        }
    }
</script>
