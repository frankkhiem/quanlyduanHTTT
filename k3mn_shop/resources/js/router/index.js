import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Index from '../components/index.vue';
import Profile from '../components/Profile.vue';
import Products from '../components/Products.vue';
import AllProducts from '../components/AllProducts.vue';
import SearchProducts from '../components/SearchProducts.vue';
import ProductsInCategory from '../components/ProductsInCategory.vue';
import Product from '../components/Product.vue';
import Order from '../components/Order.vue';
import ListOrders from '../components/ListOrders.vue';
import OrderDetail from '../components/OrderDetail.vue';
import Cart from '../components/Cart.vue';
import AllComponent from '../components/AllComponent.vue'

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
            path: '*',
            component: AllComponent,
            children: [
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
                {
                    path: '/cart',
                    component: Cart,
                },
            ]
        }
    ]
});


export default router;