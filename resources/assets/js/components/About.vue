<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="text-center my-4">
                    <img class="avatar-l" src="../../images/brand-image.jpg">
                </div>
                <div class="heading-section full-width mt-4">{{ club.name }}</div>

                <div class="form-radio">
                    <legend class=" mt-4 mb-2">Adresa</legend>
                    <div class="heading-bullet">{{ club.address }}</div>
                    <legend class=" mt-4 mb-2">Mjesto</legend>
                    <div class="heading-bullet">{{ club.city }}</div>
                    <template v-for="email in club.email">
                    <legend class=" mt-4 mb-2">Email</legend>
                    <div class="heading-bullet">{{ email }}</div>
                    </template>
                    <template v-for="phone in club.phone">
                    <legend class=" mt-4 mb-2">Telefon</legend>
                    <div class="heading-bullet">{{ phone }}</div>
                    </template>
                    <legend class=" mt-4 mb-2">O klubu</legend>
                    <div class="heading-bullet">{{ club.description }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {}
        },
        computed: {
            club() {
                return this.$store.state.club;
            }
        },
        methods: {}
    }
</script>