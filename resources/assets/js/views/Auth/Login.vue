<template>
	<div class="row">
		<div class="col-md-6 col-md-offset-3 col-sm-12">
			<form role="form" @submit.prevent="login">
				<div class="form-group">
					<h3>Welcome Back!</h3>
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" v-model="form.email" placeholder="Your email" class="form-control">
					<small class="text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" v-model="form.password" placeholder="Your password" class="form-control" name="password">
					<small class="text-danger" v-if="errors.password">{{ errors.password[0] }}</small>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary" :disabled="isProcessing">Login</button>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
	import Flash from '../../helpers/flash'
    import Auth from '../../helpers/auth'
    import { post } from '../../helpers/api'
    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                errors: {},
                isProcessing: false
            }
        },
        methods: {
            login() {
                this.isProcessing = true
                this.errors = {}
                post('api/login', this.form)
                    .then((res) => {
                        if(res.data.success) {
                            // set token
                            Auth.set(res.data.api_token, res.data.user_id)
                            Flash.setSuccess('You have successfully logged in.')
                            this.$router.push('/')
                        }
                        this.isProcessing = false
                    })
                    .catch((err) => {
                        if(err.response.status === 422) {
                            this.errors = err.response.data
                        }
                        this.isProcessing = false
                    })
            }
        }
    }
</script>