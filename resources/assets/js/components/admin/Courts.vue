<template>
    <div class="container">

    </div>
</template>
<script>
    export default {
        data: function () {
            return {
            }
        },
        computed: {
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
        },
        beforeMount() {
            if (!this.isAdmin) {
                this.$router.push('/');
            }
        }
    }
</script>