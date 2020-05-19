import Vue from 'vue'
import VueRouter from 'vue-router'
import PortalVue from 'portal-vue'
import './font-awesome'

Vue.use(VueRouter)
Vue.use(PortalVue)

import App from './views/App'
import Server from './views/Server'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'server',
            component: Server,
        },
    ],
});


const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
