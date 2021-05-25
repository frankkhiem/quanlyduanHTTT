/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// import vue router
import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// import axios
window.axios = require('axios');

// xay dung cac route frontend
import Index from './components/index.vue';
import Profile from './components/Profile.vue';
import Products from './components/Products.vue';
import AllProducts from './components/AllProducts.vue';
import SearchProducts from './components/SearchProducts.vue';
import ProductsInCategory from './components/ProductsInCategory.vue';
import Product from './components/Product.vue';
import Order from './components/Order.vue';
import ListOrders from './components/ListOrders.vue';
import OrderDetail from './components/OrderDetail.vue';

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Index,
        },
        {
            path: '/home',
            component: Index,
        },
        {
            path: '/profile',
            component: Profile,
        },
        {
            path: '/products',
            component: Products,
            children: [
                {
                    path: '',
                    component: AllProducts,
                    name: 'AllProducts',
                },
                {
                    path: 'search/:keyword',
                    component: SearchProducts,
                    name: 'SearchProducts',
                    props: true,
                },
                {
                    path: 'category/:category_id',
                    component: ProductsInCategory,
                    props: true,
                },
            ]
        },
        {
            path: '/product/:id',
            component: Product,
            props: true,
        },
        {
            path: '/order/product/:id',
            component: Order,
            props: true,
        },
        {
            path: '/listorders',
            component: ListOrders,
            name: 'ListOrders',
        },
        {
            path: '/order/detail/:id',
            component: OrderDetail,
            props: true,
        },
    ]
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Truyền sự kiện kèm dữ liệu giữa các component đã được build
Vue.prototype.eventBus = new Vue();

const app = new Vue({
    el: '#app',
    router,
});
