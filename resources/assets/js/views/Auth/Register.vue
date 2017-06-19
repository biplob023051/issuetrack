<template>
	<div class="row">
		<div class="col-md-6 col-md-offset-3 col-sm-12">
			<form role="form" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
				<div class="form-group">
					<h3>Register Please!</h3>
				</div>
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" v-model="form.name" placeholder="Your name" class="form-control" name="name">
					<small class="text-danger" v-if="form.errors.has('name')">{{ form.errors.get('name') }}</small>
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" v-model="form.email" placeholder="Your email" class="form-control" name="email">
					<small class="text-danger" v-if="form.errors.has('email')">{{ form.errors.get('email') }}</small>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" v-model="form.password" placeholder="At least 6 characters" class="form-control" name="password">
					<small class="text-danger" v-if="form.errors.has('password')">{{ form.errors.get('password') }}</small>
				</div>
				<div class="form-group">
					<label for="password_confirmation">Confirm Password</label>
					<input type="password" v-model="form.password_confirmation" placeholder="Confirm password" class="form-control" name="password_confirmation">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary" :disabled="form.errors.any() || isProcessing">Register</button>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
	import Form from '../../Form/Form'
	import Flash from '../../helpers/flash'

	export default {
		data() {
			return {
				isProcessing: false,
				form: new Form({
		            name: '',
					email: '',
					password: '',
					password_confirmation: ''
		        })
			}
		},
		methods: {
			onSubmit() {
				this.isProcessing = true
				this.form.post('api/register')
				.then((response) => {
					Flash.setSuccess('Congratulations! You have now successfully registered.')
					this.$router.push('/login')
				})
				.catch((error) => {
					this.isProcessing = false	
				})
			}
		}
	}
</script>