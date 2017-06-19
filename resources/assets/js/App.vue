<template>
	<div>
		<!-- Static navbar -->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<router-link class="navbar-brand" to="/">Sample App</router-link>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<router-link tag="li" to="/" exact activeClass="active"><a>Home</a></router-link>
						<router-link tag="li" to="/products" exact activeClass="active"><a>Products</a></router-link>
						<router-link tag="li" to="/products/create" exact v-if="auth" activeClass="active"><a>Add Product</a></router-link>
						<!-- <router-link tag="li" to="/issues/my_issues" v-if="auth"><a>My Issues</a></router-link>
						<router-link tag="li" to="/issue/create" v-if="auth"><a>Create Issue</a></router-link> -->
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<router-link tag="li" to="/login" v-if="guest" activeClass="active"><a>Login</a></router-link>
						<router-link tag="li" to="/register" v-if="guest" activeClass="active"><a>Register</a></router-link>
						<li  v-if="auth">
							<a style="cursor: pointer" @click.stop="logout">Logout</a>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
		<div class="container">
			<div class="alert alert-success" v-if="flash.success">
			    <strong>Success!</strong> {{ flash.success }}
			</div>
			<div class="alert alert-warning" v-if="flash.warning">
			    <strong>Warning!</strong> {{ flash.warning }}
			</div>
			<div class="alert alert-danger" v-if="flash.error">
			    <strong>Error!</strong> {{ flash.error }}
			</div>
			<!-- <transition name="slide" type="animation" appear>
				<router-view></router-view>
			</transition> -->
			<transition 
				enter-active-class="animated slideInLeft"
				leave-active-class="animated fadeOut"
				mode="out-in"
				appear
			>
				<router-view></router-view>
			</transition>
		</div>
	</div>
</template>
<script>
	import Auth from './helpers/auth'
	import Flash from './helpers/flash'
	import { post, interceptors } from './helpers/api'

	export default {
		created() {
			// global error http handler
			interceptors((err) => {
				if(err.response.status === 401) {
					Auth.remove()
					this.$router.push('/login')
				}

				if(err.response.status === 500) {
					Flash.setError(err.response.statusText)
				}

				if(err.response.status === 404) {
					this.$router.push('/not-found')
				}
			})
			Auth.initialize()
		},
		data() {
			return {
				authState: Auth.state,
				flash: Flash.state
			}
		},
		computed: {
			auth() {
				if(this.authState.api_token) {
					return true
				}
				return false
			},
			guest() {
				return !this.auth
			}
		},
		methods: {
			logout() {
				post('/api/logout')
				    .then((res) => {
				        if(res.data.success) {
				            // remove token
				            Auth.remove()
				            Flash.setSuccess('You have successfully logged out.')
				            this.$router.push('/login')
				        }
				    })
			}
		}
	}
</script>

<style>
	body {
		margin-top: 10px;
	}
	.slide-enter {
		opacity: 0;
	}

	.slide-enter-active {
		animation: slide-in 1s ease-out forwards;
		transition: opacity 0.5s;
	}

	.slide-leave {
		
	}

	.slide-leave-active {
		animation: slide-out 1s ease-out forwards;
		transition: opacity 1s;
		opacity: 0;
	}	

	@keyframes slide-in {
		from {
			transform: translateY(20px);
		}
		to {
			transform: translateY(0);
		}
	}

	@keyframes slide-out {
		from {
			transform: translateY(0);
		}
		to {
			transform: translateY(20px);
		}
	}

</style>