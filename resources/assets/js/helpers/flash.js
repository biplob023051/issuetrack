export default {
	state: {
		success: null,
		error: null,
		warning: null
	},
	setSuccess(message) {
		this.state.success = message
		setTimeout(() => {
			this.removeSuccess()
		}, 2000);
	},
	setWarning(message) {
		this.state.warning = message
		setTimeout(() => {
			this.removeSuccess()
		}, 3000);
	},
	setError(message) {
		this.state.error = message
		setTimeout(() => {
			this.removeError()
		}, 3000);
	},
	removeSuccess() {
		this.state.success = null
	},
	removeWarning() {
		this.state.warning = null
	},
	removeError() {
		this.state.error = null
	}
}
