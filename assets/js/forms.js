/**
 * Donation Form
 */

((w) => {
   'use strict';

   const donationForm = class {

    constructor(donationForm, options) {
			this.fields = {};
			this.form = donationForm;
			this.options = options;

			this.init(donationForm, options);
    }
    
    init(donationForm, options) {
			this.setInitFields(donationForm);
			this.onListenerFormFieldUpdate();

			// on change amount field
			this.form.addEventListener('input', (event) => {
				if (event.target.name === 'donation_amount') {
					this.onUpdateAmountField(event.target.value);
				}
			});

			// on click Preset Amount
			this.form.addEventListener('click', (event) => {
				if (event.target.classList.contains('donation-form__preset-amount')) {
					this.onClickPresetAmount(event);
				}
			});
    }

		setInitFields(donationForm) {
			const self = this;
			const fields = donationForm.querySelectorAll('input[name]');
			fields.forEach((field) => {

				let value = field.value;

				// validate event.target is checkbox field
				if (field.type === 'checkbox') {
					value = field.checked;
				}

				// validate event.target is radio field
				if (field.type === 'radio') {
					// get field name
					const fieldName = field.name;
					// const fieldValue = field.value;
					value = self.form.querySelector(`input[name="${fieldName}"]:checked`).value;
				}

				this.fields[field.name] = value;
			});

			// console.log('fields', this.fields);
		}

		onListenerFormFieldUpdate() {
			const self = this;
			this.form.addEventListener('change', (event) => {
				self.fields[event.target.name] = event.target.value;
				let value = event.target.value;

				// validate event.target is checkbox field
				if (event.target.type === 'checkbox') {
					value = event.target.checked;
				}

				// validate event.target is radio field
				if (event.target.type === 'radio') {
					const fieldName = event.target.name;
					value = self.form.querySelector(`input[name="${fieldName}"]:checked`).value;
				}

				// update UI by field
				self.onUpdateUIByField(event.target.name, value);

				// console.log('fields', self.fields);
			});
		}

		onUpdateUIByField(field, value) {
			// console.log('onUpdateUIByField', field, value);

			const inputField = this.form.querySelector(`input[name="${field}"]`);
			if (!inputField) {
				return;
			}

			if (field === 'donation_amount') {
				if(!this.onValidateValue('required', value)) {
					inputField.classList.add('error');
				} else {
					inputField.classList.remove('error');
				}
			}

			const wrapperField = inputField.closest('.donation-form__field');
			if (!wrapperField) {
				return;
			}

			if (inputField.dataset.validate) {
				const pass = this.onValidateValue(inputField.dataset.validate, value);
				if (!pass) {
					// inputField.classList.add('error');
					wrapperField.classList.add('error');
				} else {
					// inputField.classList.remove('error');
					wrapperField.classList.remove('error');
				}
			}
		}

		// on click Preset Amount
		onClickPresetAmount(event) {
			event.preventDefault();
			event.stopPropagation();

			const self = this;
			const amount = event.target.dataset.amount;
			self.form.querySelector('input[name="donation_amount"]').value = amount;
			
			// Update UI by field
			this.onUpdateUIByField('donation_amount', amount);

			event.target.classList.add('active')
			self.form.querySelectorAll('.donation-form__preset-amount').forEach((presetAmount) => {
				if (presetAmount !== event.target) {
					presetAmount.classList.remove('active');
				}
			});
		}

		// on update amout field
		onUpdateAmountField(value) {
			// remove active
			this.form.querySelectorAll('.donation-form__preset-amount').forEach((presetAmount) => {
				presetAmount.classList.remove('active');
			});
		}
		
		// validate field by type
		onValidateValue(type, value) {
			switch (type) {
				// email
				case 'email':
					return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

				// phone
				case 'phone':
					// number or string + on the first position
					return /^[0-9]+$/.test(value) || /^[a-zA-Z]+$/.test(value);

				// required
				case 'required':
					return value.trim() !== '';

				// default
				default:
					return true;
			}
		}
	}

	// make custom event trigger donation form and how to use it	
	/**
	* Custom event to trigger donation form initialization
	* 
	* Usage:
	* document.dispatchEvent(new CustomEvent('initDonationForm', {
	*   detail: {
	*     formSelector: '.my-custom-donation-form', // Optional: target specific forms
	*     options: {} // Optional: pass configuration options
	*   }
	* }));
	*/
	document.addEventListener('initDonationForm', (event) => {
		const { formSelector, options } = event.detail || {};
		
		if (formSelector) {
			// Initialize specific forms matching the selector
			document.querySelectorAll(formSelector).forEach((form) => {
				new donationForm(form, options);
			});
		} else {
			// Initialize all donation forms if no selector provided
			document.querySelectorAll('.donation-form').forEach((form) => {
				new donationForm(form, options);
			});
		}
		
		console.log('Donation forms initialized via custom event');
	});

	const initDonationForm = (formSelector, options) => {
		document.dispatchEvent(new CustomEvent('initDonationForm', {
			detail: {
				formSelector,
				options
			}
		}));
	}

	w.initDonationForm = initDonationForm;

	// dom loaded
	document.addEventListener('DOMContentLoaded', () => {
		// initialize all donation forms
		initDonationForm('.donation-form', {});
 	});

})(window)