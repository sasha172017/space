<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4" v-if="loading"></b-icon>
                    <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>
                </div>
                <div class="card-columns">
                    <div class="card" v-for="product in products">
                        <img class="card-img-top" v-if="product.imageFirst" :src="'/storage/product/'+product.imageFirst.name">
                        <img class="card-img-top" v-else :src="'/product/not-image.png'">
                        <div class="card-body">
                            <h5 class="card-title">{{product.name}}</h5>
                            <p class="card-text">{{product.description}}</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Author: {{product.user.name}}</small><br>
                            <small class="text-muted">Category: {{product.category.name}}</small><br>
                            <small class="text-muted">Time Creating: {{product.create_at}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "Home",
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Home';
                }
            },
        },
        data() {
            return {
                loading: false,
                products: null,
                error: null,
            };
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.error = this.products = null;
                this.loading = true;
                axios
                    .get('/api/products')
                    .then(response => {
                        this.loading = false;
                        this.products = response.data;
                    }).catch(error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            }
        }
    }
</script>

<style scoped>

</style>
