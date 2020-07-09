<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex">
                        <b-icon icon="three-dots" animation="cylon" class="mx-auto" font-scale="4"
                                v-if="loading"></b-icon>
                        <b-alert variant="danger" v-if="error" show>{{error}}</b-alert>
                        <b-alert variant="success" v-if="success" show>{{success}}</b-alert>
                    </div>
                    <b-form @submit="onSubmit" v-if="show">
                        <b-form-group
                            id="input-group-1"
                            label="Email address:"
                            label-for="input-1"
                        >
                            <b-form-input
                                id="input-1"
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="Enter email"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group id="input-group-2" label="Your Password:" label-for="input-2">
                            <b-form-input
                                id="input-2"
                                type="password"
                                v-model="form.password"
                                required
                            ></b-form-input>
                        </b-form-group>

                        <b-button type="submit" variant="primary">Log in</b-button>
                    </b-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "Login",
        data() {
            return {
                loading: false,
                success: false,
                error: false,
                form: {
                    email: '',
                    password: ''
                },
                show: true
            }
        },
        methods: {
            login(){
                this.$parent.$parent.authenticate();
            },
            onSubmit(evt) {
                evt.preventDefault();
                this.show = false;
                this.error = false;
                this.loading = true;
                axios.post('/api/authenticate', this.form).then(response => {
                    this.loading = false;
                    if (response.data.token) {
                        this.$session.set('token', response.data.token);
                        this.success = 'You are logged in';
                        this.login();
                    }
                }).catch(error => {
                    console.log(error);
                    this.loading = false;
                    this.show = true;
                    this.error = error.response.data.message || error.message;
                });
            },
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Login';
                }
            },
        }
    }
</script>

<style scoped>

</style>
