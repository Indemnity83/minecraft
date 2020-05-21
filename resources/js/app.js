import Vue from 'vue'
import VueRouter from 'vue-router'
import PortalVue from 'portal-vue'
import './font-awesome'

Vue.use(VueRouter);
Vue.use(PortalVue);

import App from './views/App'
import Dashboard from './views/Dashboard'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
        },
    ],
});


const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
