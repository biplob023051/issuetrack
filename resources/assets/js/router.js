import Vue from 'vue'
import VueRouter from 'vue-router'

import IssueIndex from './views/Issues/IssueIndex.vue'
import Login from './views/Auth/Login.vue'
import Register from './views/Auth/Register.vue'

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	routes: [
		{path: '/', component: IssueIndex},
		{path: '/login', component: Login},
		{path: '/register', component: Register}
	]
})

export default router