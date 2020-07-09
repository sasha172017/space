/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueRouter from 'vue-router'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

Vue.use(VueRouter)
Vue.use(VueSession)

import Home from './views/site/Home'
import App from './views/App'
import CategoryIndex from './views/CategoryIndex'
import CategoryShow from "./views/CategoryShow"
import ProductShow from "./views/ProductShow"
import ProductCreate from "./views/ProductCreate"
import Login from "./views/Login"
import VueSession from 'vue-session'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
        },
        {
            path: '/category',
            name: 'category',
            component: CategoryIndex,
        },
        {
            path: '/product/create',
            name: 'productCreate',
            component: ProductCreate,
        },
        {
            path: '/category/:id',
            name: 'categoryShow',
            component: CategoryShow
        },
        {
            path: '/product/:id',
            name: 'productShow',
            component: ProductShow
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
    ],
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import axios from 'axios'

const app = new Vue({
    el: '#app',
    created(){
        this.authenticate();
    },
    methods: {
        logout(){
            axios.get('/api/logout', { headers: { Authorization:'Bearer ' + this.$session.get('token')}}).then(response => {
                this.isAuthenticate = false;
                this.name = null;
                this.email = null;
                this.role = null;
                this.$session.remove('token');
            }).catch(error => {
                if(error.response.status == 401){
                    this.$session.remove('token');
                }
                this.userError = error.response.data.message || error.message;
            });
        },
        authenticate(){
            if(this.$session.get('token')) {
                this.getUser(this.$session.get('token'));
            }
        },
        getUser(token){
            axios.get('/api/user', { headers: { Authorization:'Bearer ' + token}}).then(response => {
                this.isAuthenticate = true;
                this.name = response.data.name;
                this.email = response.data.email;
                this.role = [];
                response.data.roles.forEach(role => {
                    this.role.push(role.name);
                });
            }).catch(error => {
                if(error.response.status == 401){
                    this.$session.remove('token');
                }
                this.userError = error.response.data.message || error.message;
            });
        }
    },
    data(){
        return {
            isAuthenticate: false,
            name: null,
            email: null,
            role: null,
            userError: false
        }
    },
    components: {App},
    router,
});
