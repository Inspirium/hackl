<template>

</template>

<script>
    export default {
        name: "ThreadNew",
        mounted() {
            this.axios.get('thread/new/' + this.$route.params.id)
                .then((res) => {
                    this.$router.push(res.data.location);
                })
        }
    }
</script>

<style scoped>

</style>