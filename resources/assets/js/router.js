import Vue from 'vue'
import VueRouter from 'vue-router'

import IssueIndex from './views/Issues/IssueIndex.vue'
import Login from './views/Auth/Login.vue'
import Register from './views/Auth/Register.vue'
import ProductForm from './views/Products/Form.vue'
import ProductShow from './views/Products/Show.vue'
import ProductIndex from './views/Products/Index.vue'


Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	routes: [
		{path: '/', component: IssueIndex},
		{path: '/login', component: Login},
		{path: '/register', component: Register},
		{path: '/products', component: ProductIndex},
		{path: '/products/create', component: ProductForm, meta:{ mode: 'create' }},
		{path: '/products/:id/edit', component: ProductForm, meta:{ mode: 'edit' }},
		{path: '/products/:id', component: ProductShow},
	]
})

export default router