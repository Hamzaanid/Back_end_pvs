require('./bootstrap');

import Vue from 'vue'
import Vuetify from "../plugins/vuetify"
import VueRouter from 'vue-router'
import App from './vue/app'

Vue.use(VueRouter)
//Vue.use(store)

import routes from './routes'
import store from './store'



const router = new VueRouter({
    mode:'history',
    routes
})

const app = new Vue({
    vuetify:Vuetify,
    el:'#app',
    router,
    store,
    components:{
        App
    }
})
