<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                            v-if="loading"></b-icon>
                    <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>
                </div>
                <div v-if="product">
                    <div class="row">
                        <div class="col-auto">
                            <lingallery :iid.sync="currentId" class="image" :width="width" :height="height"
                                        :items="items"/>
                        </div>
                        <div v-if="product" class="col-6">
                            <h2>{{product.name}}</h2>
                            <p>{{product.description}}</p><br>
                            <small>Created: {{product.created_at}}</small><br>
                            <small>Author: {{product.user.name + ' ' + product.user.email}}</small><br>
                            <small>Category: {{product.category.name}}</small><br>
                            <small>Shops:
                                <div v-for="shop in product.shops">
                                    <small><a :href="shop.link">{{shop.name + ' price - ' + shop.pivot.price}}</a></small>
                                    <br></div>
                            </small>

                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <h2>Advantages</h2>
                            <ul v-if="tagsTrue.length > 0">
                                <li v-for="tag in tagsTrue">{{tag.name}}</li>
                            </ul>
                            <span v-else>No Advantages</span>

                        </div>
                        <div class="col-6">
                            <h2>Disadvantages</h2>
                            <ul v-if="tagsFalse.length > 0">
                                <li v-for="tag in tagsFalse">{{tag.name}}</li>
                            </ul>
                            <span v-else>No Disadvantages</span>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-12">
                            <h2>Comments</h2>
                            <div v-if="comments.length > 0">
                                <div v-for="comment in comments">
                                    <p>{{comment.text}}</p>
                                    <small>{{comment.user.name + ' ' + comment.user.email}}</small><br>
                                    <small>{{comment.created_at}}</small>
                                    <hr>
                                </div>
                            </div>
                            <span v-else>No Comments</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Lingallery from 'lingallery'

    export default {
        components: {lingallery: Lingallery},
        name: "ProductShow",
        data() {
            return {
                width: 400,
                height: 300,
                items: null,
                currentId: null,
                loading: false,
                error: null,
                product: null,
                comments: null,
                tagsTrue: [],
                tagsFalse: []
            }
        },
        created() {
            this.fetchProduct(this.$route.params.id);
        },
        methods: {
            foreachTags(tags) {
                tags.forEach((tag) => {
                    if (tag.value) {
                        this.tagsTrue.push(tag);
                    } else if (!tag.value) {
                        this.tagsFalse.push(tag);
                    }
                });
            },
            foreachImage(images) {
                if (images.length > 0) {
                    this.items = [];
                }
                images.forEach((value) => {
                    this.items.push({
                        id: value.id,
                        src: '/storage/product/' + value.name,
                        thumbnail: '/storage/product/' + value.name
                    });
                });
            },
            fetchProduct(id) {
                this.error = this.product = null;
                this.loading = true;
                axios
                    .get('/api/product/' + id)
                    .then(response => {
                        this.loading = false;
                        this.product = response.data;
                        document.title = this.product.name;
                        this.foreachImage(response.data.images);
                        this.foreachTags(response.data.tags);
                        this.comments = response.data.comments;
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
    .image {
        width: 400px;
    }
</style>
