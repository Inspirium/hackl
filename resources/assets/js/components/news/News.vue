<template>
    <div>
        <template v-if="$route.params.slug">

            <router-view></router-view>
        </template>
        <template v-else>


        <div class="container">
            <div class="row mb-3">
                <div class="col nopadding">
                    <div class="header-image">
                        <div class="gradient"></div>
                        <div class="heading-image">{{ title }}</div>
                    </div>
                </div>
            </div>
            <router-link to="/club/news/vijesti/single" class="article">
                <div class="article_date">1.5.2018.</div>
                <div class="article_title">Uvođenje aplikacije za rezervaciju terena i upravljanje članovima</div>
            </router-link>

        </div>





        </template>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {}
        },
        computed: {
            title() {
                if (this.$route.params.category) {
                    return 'Kategorija'
                }
                return 'Novosti'
            }
        },
        methods: {}
    }
</script>