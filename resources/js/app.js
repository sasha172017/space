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

import Home from './views/site/Home'
import App from './views/App'
import CategoryIndex from './views/CategoryIndex'
import CategoryShow from "./views/CategoryShow"
import ProductShow from "./views/ProductShow"

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
            path: '/category/:id',
            name: 'categoryShow',
            component: CategoryShow
        },
        {
            path: '/product/:id',
            name: 'productShow',
            component: ProductShow
        },
    ],
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
