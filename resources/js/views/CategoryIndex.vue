<template>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="d-flex">
                    <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                            v-if="loadingCategories"></b-icon>
                    <b-alert variant="danger" v-if="errorCategories" show>{{errorCategories}}</b-alert>
                </div>
                <div class="list-group">
                    <div v-for="category in categories">
                        <node-tree :node="category"></node-tree>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import NodeTree from "./NodeTree"

    export default {
        components: {NodeTree},
        name: "Category",
        created() {
            this.fetchCategories();
        },
        data() {
            return {
                loadingProducts: false,
                loadingCategories: false,
                categories: null,
                errorCategories: null,
            };
        },
        methods: {
            fetchCategories() {
                this.errorCategories = this.categories = null;
                this.loadingCategories = true;
                axios
                    .get('/api/category')
                    .then(response => {
                        this.loadingCategories = false;
                        this.categories = response.data;
                    }).catch(error => {
                    this.loading = false;
                    this.errorCategories = error.response.data.message || error.message;
                });
            }
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Category';
                }
            },
        }
    }
</script>

<style>

</style>
