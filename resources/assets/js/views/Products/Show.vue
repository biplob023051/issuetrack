<template>
	<div>
		<div class="row">
			<div class="col-md-4">
				<img :src="`/images/${product.image}`" class="img-responsive" v-if="product.image">
			</div>
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">
							{{ product.name }}
							<br>
							<small>Created by <b>{{ product.user.name }}</b> at {{ dateFormat(product.created_at) }}</small>
						</h2>
					</div>
					<div class="panel-body">
						<p>Price: $ {{ product.price }}, In Stock: {{ product.quantity }} pcs</p>
						<p>{{ product.description }}</p>
						<div v-if="authState.api_token && authState.user_id == product.user_id">
							<router-link :to="`/products/${product.id}/edit`" class="btn btn-primary">Edit</router-link>
							<button class="btn btn-danger" @click="remove">Delete</button>
						</div>
						<div class="row" v-if="product.offers.length">
							<div class="col-md-12">
								<h4>Available Offers</h4>
								<ul class="list-group">
									<li class="list-group-item" v-for="offer in product.offers">
										{{offer.name}} $ {{offer.price}} for {{ offer.quantity }} pcs
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				    <label>Write Comment</label>
				    <textarea class="form-control form-body" v-model="form.body" placeholder="Write comment"></textarea>
				    <small class="text-danger" v-if="error.body">{{error.body[0]}}</small>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" @click="comment" v-show="form.body" :disabled="isProcessing">Post</button>
				</div>
			</div>
			<div class="col-md-12" v-if="product.comments">
				<transition-group
					enter-active-class="animated slideInDown"
					leave-active-class="animated slideInUp"
					mode="out-in"
					appear
				>
					<div class="panel panel-default" v-for="(comment, index) in product.comments" :key="index">
						<div class="panel-heading">
							<h3 class="panel-title">
								<small>Commented by <b>{{ comment.user.name }}</b> at {{ dateFormat(comment.created_at) }}</small>
							</h3>
						</div>
						<div class="panel-body">
							<p>{{ comment.body }}</p>
						</div>
					</div>
				</transition-group>
			</div>
		</div>
	</div>
</template>
<script>
import { get, post, del } from '../../helpers/api'
import * as moment from 'moment'
import Auth from '../../helpers/auth'
import Flash from '../../helpers/flash'
export default {
	data(){
		return {
			form: {},
			error: {},
			isProcessing: false,
			product: {
				user: {},
				offers: {},
				comments: {}
			},
			authState: Auth.state
		}
	},
	created() {
		get('/api/products/' + this.$route.params.id)
		.then((response) => {
			this.product = response.data.product
			this.form = response.data.form
		})
	},
	methods: {
		dateFormat(datetime) {
			return moment(datetime).fromNow()
		},
		remove() {
			del('/api/products/' + this.$route.params.id)
			.then(response => {
				if(response.data.success) {
					Flash.setSuccess('You have successfully deleted product!')
					this.$router.push('/products')
				}
			})
		},
		comment() {
			this.error = ''
			this.isProcessing = true
			post('/api/products/' + this.$route.params.id + '/comment', this.form)
			.then(response => {
				if(response.data.success) {
					this.form.body = ''
					this.product.comments.unshift(response.data.comment)
					this.isProcessing = false
				}
			})
			.catch(err => {
				this.error = err.response.data
				this.isProcessing = false
			})
		}
	}
}
</script>