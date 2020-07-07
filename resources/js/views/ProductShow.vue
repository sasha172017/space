<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                            v-if="loading"></b-icon>
                    <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "ProductShow",
        data() {
            return {
                loading: false,
                error: null,
                product: null,
            };
        },
        created() {
            this.fetchProduct(this.$route.params.id);
        },
        methods: {
            fetchProduct(id) {
                this.error = this.product = null;
                this.loading = true;
                axios
                    .get('/api/product/' + id)
                    .then(response => {
                        this.loading = false;
                        this.product = response.data;
                        document.title = this.product.name;
                        console.log(response.data);
                    }).catch(error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            },
            fetchProducts() {
            }
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'product';
                }
            }
        }
    }
</script>

<style>

</style>
