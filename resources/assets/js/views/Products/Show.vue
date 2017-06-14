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
					</div>
				</div>
			</div>
		</div>
		<div class="row" v-if="product.offers.length">
			<div class="col-md-8 col-md-offset-4">
				<h4>Available Offers</h4>
				<ul class="list-group">
					<li class="list-group-item" v-for="offer in product.offers">
						{{offer.name}} $ {{offer.price}} for {{ offer.quantity }} pcs
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>
<script>
import { get, del } from '../../helpers/api'
import * as moment from 'moment'
import Auth from '../../helpers/auth'
import Flash from '../../helpers/flash'
export default {
	data(){
		return {
			product: {
				user: {},
				offers: {}
			},
			authState: Auth.state
		}
	},
	created() {
		get('/api/products/' + this.$route.params.id)
		.then((response) => {
			this.product = response.data.product;
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
		}
	}
}
</script>