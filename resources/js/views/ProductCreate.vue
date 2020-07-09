<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                            v-if="loading"></b-icon>
                    <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>
                </div>
                <div>
                    <b-form @submit="onSubmit" v-if="show">
                        <b-form-group
                            id="input-group-1"
                            label="Product Name:"
                            label-for="input-1"
                        >
                            <b-form-input
                                id="input-1"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Enter name"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group>
                            <label>Upload images: </label>
                            <input type="file" id="file" ref="file" multiple v-on:change="changeImage()"/>
                        </b-form-group>
                        <b-form-group>
                            <b-form-select v-model="form.category.selected"
                                           :options="form.category.options">
                            </b-form-select>
                        </b-form-group>
                        <label>Description:</label>
                        <b-form-textarea
                            v-model="form.description"
                            id="textarea-default"
                            placeholder="Default textarea"
                        ></b-form-textarea>
                        <br>
                        <span>Advantages:</span>
                        <div class="d-flex" v-for="(advantages, index) in form.tags.advantages">
                            <b-form-group>
                                <b-form-input
                                    id="input-1"
                                    v-model="advantages.value"
                                    :key="index"
                                    type="text"
                                    required
                                ></b-form-input>
                            </b-form-group>
                            <b-icon icon="backspace" style="cursor:pointer;margin-left:5px;width: 40px; height: 40px;"
                                    @click="removeAdvantage(index)"></b-icon>
                        </div>
                        <b-col lg="4" class="pb-2">
                            <b-button @click="addAdvantage()" variant="outline-secondary" size="sm">Add Advantage
                            </b-button>
                        </b-col>
                        <span>Disadvantages:</span>
                        <div class="d-flex" v-for="(disadvantages, index) in form.tags.disadvantages">
                            <b-form-group>
                                <b-form-input
                                    id="input-1"
                                    v-model="disadvantages.value"
                                    :key="index"
                                    type="text"
                                    required
                                ></b-form-input>
                            </b-form-group>
                            <b-icon icon="backspace" style="cursor:pointer;margin-left:5px;width: 40px; height: 40px;"
                                    @click="removeDisadvantage(index)"></b-icon>
                        </div>
                        <b-col lg="4" class="pb-2">
                            <b-button @click="addDisadvantage()" variant="outline-secondary" size="sm">Add
                                Disadvantage
                            </b-button>
                        </b-col>

                        <b-form-group label="Stacked radios">
                            <b-form-radio-group
                                v-model="form.shop.selected"
                                :options="form.shop.options"
                                name="radios-stacked"
                                stacked
                            ></b-form-radio-group>
                        </b-form-group>
                        <b-form-select multiple v-model="form.shop.select.selected"
                                       v-if="form.shop.selected == 'select'"
                                       :options="form.shop.select.options"></b-form-select>
                        <div v-if="form.shop.selected == 'new'">
                            <div v-for="(shop, index) in form.shop.new">
                                <b-form-group>
                                    <label>Name:</label>
                                    <b-form-input
                                        id="input-1"
                                        v-model="shop.name"
                                        :key="index"
                                        type="text"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group>
                                    <label>Link:</label>
                                    <b-form-input
                                        id="input-1"
                                        v-model="shop.link"
                                        :key="index"
                                        type="text"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group>
                                    <label>Description:</label>
                                    <b-form-textarea
                                        v-model="shop.description"
                                        id="textarea-default"
                                        placeholder="Default textarea"
                                    ></b-form-textarea>
                                </b-form-group>
                                <a style="cursor: pointer" @click="removeShop(index)">remove shop</a>
                            </div>
                            <b-col lg="4" class="pb-2">
                                <b-button @click="addShop()" variant="outline-secondary" size="sm">Add Shop
                                </b-button>
                            </b-col>
                        </div>
                        <br>
                        <b-button type="submit" variant="primary">Save</b-button>
                    </b-form>
                    <b-card class="mt-3" header="Form Data Result">
                        <pre class="m-0">{{ form }}</pre>
                    </b-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "ProductCreate",
        created() {
        },
        data() {
            return {
                loading: false,
                error: null,
                show: true,
                form: {
                    name: '',
                    images: [],
                    description: '',
                    tags: {
                        advantages: [],
                        disadvantages: [],
                    },
                    category: {
                        selected: null,
                        options: [
                            {value: null, text: 'Select Category'},
                        ]
                    },
                    shop: {
                        selected: 'select',
                        options: [
                            {text: 'Select Shop', value: 'select'},
                            {text: 'Create Shop', value: 'new'},
                        ],
                        select: {
                            selected: null,
                            options: [
                            ]
                        },
                        new: []
                    }
                },
            }
        },
        mounted() {
            this.getShops();
            this.getCategories();
        },
        methods: {
            getCategories(){
                axios.get('/api/category').then(response => {
                    response.data.forEach(category => {
                        this.form.category.options.push({value: category.id, text: category.name});
                    });
                }).catch(error => {
                    this.errorCategories = error.response.data.message || error.message;
                });
            },
            getShops(){
                axios.get('/api/shop').then(response => {
                    response.data.forEach(shop => {
                        this.form.shop.select.options.push({value: shop.id, text: shop.name});
                    });
                }).catch(error => {
                    this.errorCategories = error.response.data.message || error.message;
                });
            },
            removeAdvantage(index) {
                this.form.tags.advantages.splice(index, 1);
            },
            addAdvantage() {
                this.form.tags.advantages.push({value: ''});
            },
            removeDisadvantage(index) {
                this.form.tags.disadvantages.splice(index, 1);
            },
            addDisadvantage() {
                this.form.tags.disadvantages.push({value: ''});
            },
            removeShop(index) {
                this.form.shop.new.splice(index, 1);
            },
            addShop() {
                this.form.shop.new.push({name: '', description: '', link: ''});
            },
            changeImage() {
                this.images = this.$refs.file.files;
            },
            onSubmit(evt) {
                evt.preventDefault();
                axios.post();
                alert(JSON.stringify(this.form))
            },
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Create Product';
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
