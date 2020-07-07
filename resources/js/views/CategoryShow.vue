<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <h2 v-if="category">{{category.name}}</h2>

                    <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                            v-if="loading"></b-icon>
                    <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>
                </div>
                <div class="card-columns">
                    <div class="card" v-for="product in products">
                        <img class="card-img-top" v-if="product.imageFirst" :src="'/storage/product/'+product.imageFirst.name">
                        <img class="card-img-top" v-else :src="'/product/not-image.png'">
                        <div class="card-body">
                            <h5 class="card-title"><router-link :to="{name: 'productShow', params:{id: product.id}}">{{product.name}}</router-link></h5>
                            <p class="card-text">{{product.description}}</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Author: {{product.user.name}}</small><br>
                            <small class="text-muted">Category: {{product.category.name}}</small><br>
                            <small class="text-muted">Time Creating: {{product.create_at}}</small>
                        </div>
                    </div>
                </div>
                <b-pagination
                    v-if="paginate"
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    first-text="First"
                    prev-text="Prev"
                    next-text="Next"
                    last-text="Last"
                ></b-pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "CategoryShow",
        data() {
            return {
                rows: null,
                perPage: null,
                currentPage: null,
                paginate: null,
                loading: false,
                category: null,
                error: null,
                products: null,
                page: 1
            };
        },
        created() {
            this.page = this.$route.query.page;
            this.fetchCategory(this.$route.params.id);
        },
        methods: {
            fetchCategory(id) {
                this.error = this.category = null;
                this.loading = true;
                axios
                    .get('/api/category/' + id)
                    .then(response => {
                        this.loading = false;
                        this.category = response.data;
                        document.title = this.category.name;
                        this.fetchProducts();
                    }).catch(error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            },
            fetchProducts(page = this.page) {
                this.error = this.products = null;
                this.loading = true;
                axios
                    .get('/api/products/category/' + this.category.id, {params: {page: page}})
                    .then(response => {
                        this.loading = false;
                        this.products = response.data.data;
                        this.rows = response.data.total;
                        this.perPage = response.data.per_page;
                        this.currentPage = response.data.current_page;
                        this.paginate = response.data.prev_page_url || response.data.next_page_url;
                        this.page = page;
                        this.$router.push({query: {page: page}}).catch(()=>{});
                    }).catch(error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            }
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'category';
                }
            },
            currentPage: {
                handler: function(page) {
                    if(page != this.page){
                        this.fetchProducts(page);
                    }
                }
            }
        }
    }
</script>

<style>

</style>
