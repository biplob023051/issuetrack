<template>
	<div class="row">
		<div class="col-md-12 box">
			<h4 class="col-md-6">{{action}} Product</h4>
			<div class="col-md-6 text-right">
				<button class="btn btn-success" @click="save" :disabled="isProcessing">Save</button>
				<button class="btn" @click="$router.back()" :disabled="isProcessing">Cancel</button>
			</div>
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4">
					<div class="box">
						<image-upload v-model="form.image"></image-upload>
						<small class="text-danger" v-if="error.image">{{error.image[0]}}</small>
					</div>
				</div>

				<div class="col-md-8">
					<div class="box">
						<div class="form-group">
						    <label>Name</label>
						    <input type="text" class="form-control" v-model="form.name">
						    <small class="text-danger" v-if="error.name">{{error.name[0]}}</small>
						</div>
						<div class="form-group">
						    <label>Quantity</label>
						    <input type="number" class="form-control" v-model="form.quantity">
						    <small class="text-danger" v-if="error.quantity">{{error.quantity[0]}}</small>
						</div>
						<div class="form-group">
						    <label>Price</label>
						    <input type="number" class="form-control" v-model="form.price">
						    <small class="text-danger" v-if="error.price">{{error.price[0]}}</small>
						</div>
						<div class="form-group">
						    <label>Description</label>
						    <textarea class="form-control form__description" v-model="form.description"></textarea>
						    <small class="text-danger" v-if="error.description">{{error.description[0]}}</small>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="box">
				<h3 class="product-sub-title" style="margin-left: 12px;">Offers</h3>
				<div v-for="(offer, index) in form.offers" class="row" style="margin: 5px 0;">
					<div class="col-md-4">
						<input type="text" class="form-control" v-model="offer.name" placeholder="Title" 
						:class="[error[`offers.${index}.name`] ? 'border-red' : '']">
					</div>
					<div class="col-md-2">
						<input type="number" class="form-control" v-model="offer.quantity" placeholder="Quantity"
						:class="[error[`offers.${index}.quantity`] ? 'border-red' : '']">
					</div>
					<div class="col-md-2">
						<input type="number" class="form-control" v-model="offer.price" placeholder="Price"
						:class="[error[`offers.${index}.price`] ? 'border-red' : '']">
					</div>
					<div class="col-md-1">
						<button @click="remove('offers', index)" class="btn btn-danger">&times;</button>
					</div>
				</div>
				<button @click="addOffer" class="btn btn-primary" style="margin-left: 14px;">Add Offer</button>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	import Vue from 'vue'
	import Flash from '../../helpers/flash'
	import { get, post } from '../../helpers/api'
	import { toMulipartedForm } from '../../helpers/form'
	import ImageUpload from '../../components/ImageUpload.vue'

	export default {
		components: {
			ImageUpload
		},
		data() {
			return {
				form: {
					offers: []
				},
				error: {},
				isProcessing: false,
				initializeURL: `/api/products/create`,
				storeURL: `/api/products`,
				action: 'Create'
			}
		},
		created() {
			if(this.$route.meta.mode === 'edit') {
				this.initializeURL = `/api/products/${this.$route.params.id}/edit`
				this.storeURL = `/api/products/${this.$route.params.id}?_method=PUT`
				this.action = 'Update'
			}
			get(this.initializeURL)
				.then((res) => {
					Vue.set(this.$data, 'form', res.data.form)
				})
		},
		methods: {
			save() {
				const form = toMulipartedForm(this.form, this.$route.meta.mode)
				post(this.storeURL, form)
				    .then((res) => {
				        if(res.data.success) {
				            Flash.setSuccess(res.data.message)
				            this.$router.push(`/products/${res.data.id}`)
				        }
				        this.isProcessing = false
				    })
				    .catch((err) => {
				        if(err.response.status === 422) {
				            this.error = err.response.data
				        }
				        this.isProcessing = false
				    })
			},
			addOffer() {
				this.form.offers.push({
					name: '',
					quantity: '',
					price: ''
				})
			},
			remove(type, index) {
				this.form[type].splice(index, 1)
			}
		}
	}
</script>

<style>
	.bg-white {
		background: #fff;
		padding: 10px 0;
		margin: 10px 0;
	}
	.box {
		box-shadow: rgba(0, 0, 0, 0.09) 0 1px 0;
        background: #fff;
        padding: 15px;
        margin: 15px;
	}

	.image-upload {
        height: 257px;
        background: #fafafa;
        border: 1px dashed  rgba(0,0,0,.1);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-preview {
        position: relative;
    }

    .image-close {
        position: absolute;
        right: 0;
        top: 0;
    }

    .date-control input{
		display: block;
	    width: 100%;
	    height: 36px;
	    padding: 6px 12px;
	    font-size: 14px;
	    line-height: 1.6;
	    color: #555555;
	    background-color: #fff;
	    background-image: none;
	    border: 1px solid #ccd0d2;
	    border-radius: 4px;
	    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
	}

	.border-red {
		border: 1px solid red;
	}
</style>
