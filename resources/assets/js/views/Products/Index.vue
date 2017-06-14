<template>
	<div>
		<div class="row">
			<div class="col-md-4" v-for="product in products">
				<div class="thumbnail">
					<router-link :to="`products/${product.id}`">
						<div class="caption">
							<p>{{ product.name }}</p>
						</div>	
						<img :src="`/images/${product.image}`" v-if="product.image" class="img-thumbnail">
					</router-link>
					<div class="caption">
						<small>Price: $ {{ product.price }}</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script>
	import { get } from '../../helpers/api'

	export default {
		data() {
			return {
				products: [] 
			}
		},
		created() {
			get('api/products')
				.then((response) => {
					this.products = response.data.products
				})
		}
	}
</script>

<style scoped>
	a:hover {
		text-decoration: none;
	}
</style>