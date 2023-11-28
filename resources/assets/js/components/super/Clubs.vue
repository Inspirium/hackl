<template>
    <div class="container">

    </div>
</template>

<script>
    export default {
        name: "Clubs",
        data() {
            return {
                clubs: []
            }
        },
        mounted() {
            this.axios.get('super/clubs')
                .then((res) => {
                    this.clubs = res.data;
                })
        },
        beforeMount() {
            if (this.$store.state.user.id !== 1) {
                this.$router.push('/');
            }
        }
    }
</script>