<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                            v-if="loading"></b-icon>
                    <b-alert variant="danger" v-if="errorValidate" show>
                        <ul type="none">
                            <li v-for="(errors, index) in errorValidate">{{errors[0]}}</li>
                        </ul>
                    </b-alert>
                    <b-alert variant="danger" v-if="error" show>{{error}}
                    </b-alert>
                    <b-alert variant="success" v-if="success" show>{{success}}</b-alert>
                </div>
                <div>
                    <b-form autocomplete="off" @submit="onSubmit" v-if="show">
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
                        <div v-if="form.shop.selected == 'select'">
                            <b-form-select multiple v-model="form.shop.select.selected"
                                           :options="form.shop.select.options"></b-form-select>
                            <b-form-group v-if="form.shop.select.selected.length > 0">
                                <div v-for="(shop, index) in form.shop.select.selected">
                                    <span>{{shop.name}}</span><br>
                                    <div class="form-inline">
                                        <label> Price product: </label>
                                        <b-form-input
                                            id="input-1"
                                            type="text"
                                            v-model="shop.price"
                                            :key="index"
                                            required
                                        ></b-form-input>
                                        <label> Link product: </label>
                                        <b-form-input
                                            id="input-1"
                                            type="text"
                                            v-model="shop.linkProduct"
                                            :key="index"
                                            required
                                        ></b-form-input>
                                    </div>
                                    <br></div>
                            </b-form-group>
                        </div>
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
                                    <label>Link Shop:</label>
                                    <b-form-input
                                        id="input-1"
                                        v-model="shop.linkShop"
                                        :key="index"
                                        type="text"
                                        required
                                    ></b-form-input>

                                    <label>Link Product:</label>
                                    <b-form-input
                                        id="input-1"
                                        v-model="shop.linkProduct"
                                        :key="index"
                                        type="text"
                                        required
                                    ></b-form-input>

                                    <label>Price:</label>
                                    <b-form-input
                                        id="input-1"
                                        v-model="shop.price"
                                        :key="index"
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
                    <br>
<!--                                        <b-card class="mt-3" header="Form Data Result">-->
<!--                                            <pre class="m-0">{{ form }}</pre>-->
<!--                                        </b-card>-->
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
                success: false,
                loading: false,
                error: null,
                errorValidate: null,
                show: true,
                images: [],
                form: {
                    name: '',
                    description: '',
                    tags: {
                        advantages: [],
                        disadvantages: [],
                    },
                    category: {
                        selected: null,
                        options: []
                    },
                    shop: {
                        selected: 'select',
                        options: [
                            {text: 'Select Shop', value: 'select'},
                            {text: 'Create Shop', value: 'new'},
                        ],
                        select: {
                            selected: [],
                            options: []
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
            getCategories() {
                axios.get('/api/category').then(response => {
                    response.data.forEach(category => {
                        this.form.category.options.push({value: category.id, text: category.name});
                    });
                }).catch(error => {
                    this.errorCategories = error.response.data.message || error.message;
                });
            },
            getShops() {
                axios.get('/api/shop').then(response => {
                    response.data.forEach(shop => {
                        this.form.shop.select.options.push({
                            value: {id: shop.id, name: shop.name, price: ''},
                            text: shop.name
                        });
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
                this.form.shop.new.push({name: '', description: '', linkShop: '', linkProduct: '', price: ''});
            },
            changeImage() {
                this.images = this.$refs.file.files;
            },
            onSubmit(evt) {
                this.error = false;
                this.errorValidate = false;
                this.loading = true;
                this.show = false;
                evt.preventDefault();
                var formData = new FormData();
                for (let i = 0; i < this.images.length; i++) {
                    formData.append('images[]', this.images[i]);
                }
                formData.append('name', this.form.name);
                formData.append('description', this.form.description);
                formData.append('category', this.form.category.selected);
                for (let i = 0; i < this.form.tags.advantages.length; i++) {
                    formData.append('advantages[]', this.form.tags.advantages[i].value);
                }
                for (let i = 0; i < this.form.tags.disadvantages.length; i++) {
                    formData.append('disadvantages[]', this.form.tags.disadvantages[i].value);
                }
                if (this.form.shop.selected == 'select') {
                    for (let i = 0; i < this.form.shop.select.selected.length; i++) {
                        formData.append('shop[' + i + '][id]', this.form.shop.select.selected[i].id);
                        formData.append('shop[' + i + '][name]', this.form.shop.select.selected[i].name);
                        formData.append('shop[' + i + '][price]', this.form.shop.select.selected[i].price);
                        formData.append('shop[' + i + '][linkProduct]', this.form.shop.select.selected[i].linkProduct);
                    }
                } else if (this.form.shop.selected == 'new') {
                    for (let i = 0; i < this.form.shop.new.length; i++) {
                        formData.append('shopNew[' + i + '][name]', this.form.shop.new[i].name);
                        formData.append('shopNew[' + i + '][description]', this.form.shop.new[i].description);
                        formData.append('shopNew[' + i + '][linkShop]', this.form.shop.new[i].linkShop);
                        formData.append('shopNew[' + i + '][linkProduct]', this.form.shop.new[i].linkProduct);
                        formData.append('shopNew[' + i + '][price]', this.form.shop.new[i].price);
                    }
                }
                axios.post('/api/product', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': 'Bearer ' + this.$session.get('token')
                    }
                }).then(response => {
                    this.loading = false;
                    this.success = response.data;
                }).catch(error => {
                    this.show = true;
                    this.loading = false;
                    if (error.response.status == 400) {
                        this.errorValidate = error.response.data.message;
                        console.log(this.errorValidate)
                    } else {
                        this.error = error.response.data.message || error.message;
                    }
                });
            },
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Edit Product';
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
