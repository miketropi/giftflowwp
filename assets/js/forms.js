/**
 * Donation Form
 */
(async (w) => {
   'use strict';
	const donationForm = class {

    constructor(donationForm, options) {
			this.fields = {};
			this.form = donationForm;
			this.options = options;
			this.totalSteps = this.form.querySelectorAll('.donation-form__step-panel').length;
			this.currentStep = 1;

			this.init(donationForm, options);
    }
    
    init(donationForm, options) {
			const self = this;

			// set default payment method selected
			this.form.querySelector(`input[name="payment_method"][value="${options.paymentMethodSelected}"]`).checked = true;

			this.setInitFields(donationForm);
			this.onListenerFormFieldUpdate();

			// create event trigger on load form to document
			document.dispatchEvent(new CustomEvent('donationFormLoaded', {
				detail: {
					self: self,
					form: self.form
				}
			}));

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

			// on click next step
			this.form.addEventListener('click', (event) => {
				// is contains class and is element had class donation-form__button--next
				const isNextButton = event.target.classList.contains('donation-form__button--next') && event.target.tagName === 'BUTTON';
				if (isNextButton) {
					const stepPass = this.onValidateFieldsCurrentStep();
					// console.log('stepPass', stepPass);

					if (stepPass) {
						this.onNextStep();
					}
				}
			});

			// on click previous step
			this.form.addEventListener('click', (event) => {
				// is contains class and is element had class donation-form__button--back
				const isBackButton = event.target.classList.contains('donation-form__button--back') && event.target.tagName === 'BUTTON';

				if (isBackButton) {
					this.onPreviousStep();
				}
			});

			// on submit form
			this.form.addEventListener('submit', (event) => {
				event.preventDefault();
				this.onSubmitForm();
			});
    }

		onSetLoading(status) {
			const self = this;
			self.form.querySelector('.donation-form__button--submit').classList.toggle('loading', status);
			self.form.querySelector('.donation-form__button--submit').disabled = status;
		}

		async onSubmitForm() {
			const self = this;
			self.onSetLoading(true);

			// validate fields
			const pass = self.onValidateFieldsCurrentStep();
			// console.log('pass', pass);
			if (!pass) {
				return;
			}

			// do hooks before submit support async function after submit
			await self.onDoHooks();

			// send data
			const response = await self.onSendData(self.fields);

			console.log('onSubmitForm', response);

			self.onSetLoading(false);
		}

		async onSendData(data) {
			let ajaxurl = `${window.giftflowwpDonationForms.ajaxurl}?action=giftflowwp_donation_form&wp_nonce=${data.wp_nonce}`;

			const response = await fetch(ajaxurl, {
				method: 'POST',
				body: JSON.stringify(data),
				headers: {
					'Content-Type': 'application/json'
				}
			}).then(response => response.json())
				.then(data => console.log(data))
				.catch(error => console.error('Error:', error));

			return response;
		}

		async onDoHooks() {
			const self = this;
			
			// allow developer add hooks from outside support async function and return promise
			return new Promise((resolve, reject) => {
				self.form.dispatchEvent(new CustomEvent('donationFormBeforeSubmit', {
					detail: {
						self: self,
						fields: self.fields,
						resolve,
						reject
					}
				}));
			});
		}

		onSetField(name, value) {
			this.fields[name] = value;
		}

		onNextStep() {
			const self = this;
			self.currentStep++;

			// nav
			self.form.querySelector('.donation-form__step-link.is-active').classList.remove('is-active');
			self.form.querySelector(`.donation-form__step-item.nav-step-${self.currentStep} .donation-form__step-link`).classList.add('is-active');

			// panel
			self.form.querySelector('.donation-form__step-panel.is-active').classList.remove('is-active');
			self.form.querySelector('.donation-form__step-panel.step-' + self.currentStep).classList.add('is-active');
		}

		onPreviousStep() {
			const self = this;
			self.currentStep--;

			// nav
			self.form.querySelector('.donation-form__step-link.is-active').classList.remove('is-active');
			self.form.querySelector(`.donation-form__step-item.nav-step-${self.currentStep} .donation-form__step-link`).classList.add('is-active');

			// panel
			self.form.querySelector('.donation-form__step-panel.is-active').classList.remove('is-active');
			self.form.querySelector('.donation-form__step-panel.step-' + self.currentStep).classList.add('is-active');
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

			const wrapperField = inputField.closest('.donation-form__field');
			if (!wrapperField) {

				if(!this.onValidateValue('required', value)) {
					inputField.classList.add('error'); 
					this.onUpdateOutputField(field, '');
				} else {
					inputField.classList.remove('error');
					this.onUpdateOutputField(field, value);
				}

				return;
			}

			if (inputField.dataset.validate) {
				const pass = this.onValidateValue(inputField.dataset.validate, value);
				if (!pass) {
					// inputField.classList.add('error');
					wrapperField.classList.add('error');
					this.onUpdateOutputField(field, '');
				} else {
					// inputField.classList.remove('error');
					wrapperField.classList.remove('error');
					this.onUpdateOutputField(field, value);
				}
			}
		}

		onUpdateOutputField(field, value) {
			const outputField = this.form.querySelector(`[data-output="${field}"]`);
			const formatTemplate = outputField?.dataset?.formatTemplate;

			if (formatTemplate) {
				value = formatTemplate.replace('{{value}}', value);
			}

			if (outputField) {
				outputField.textContent = value;
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

		onValidateFieldsCurrentStep() {
			const self = this;
			const currentStepWrapper = this.form.querySelector('.donation-form__step-panel.is-active');
			let pass = true;
			
			if (!currentStepWrapper) {
				return;
			}

			const fields = currentStepWrapper.querySelectorAll('input[name][data-validate]');
			fields.forEach((field) => {
				const fieldName = field.name;
				const fieldValue = field.value;
				const fieldValidate = field.dataset.validate;
				
				if(!this.onValidateValue(fieldValidate, fieldValue)) {
					pass = false;
				}
				
				self.onUpdateUIByField(fieldName, fieldValue);
			});

			currentStepWrapper.querySelectorAll('[data-custom-validate="true"]').forEach((field) => {
				const status = field.dataset.customValidateStatus;

				if(status === 'false') {
					pass = false;

					// add error class to field
					field.classList.add('error', 'custom-error');
				}
			});

			return pass;
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
		initDonationForm('.donation-form', {
			paymentMethodSelected: 'stripe',
		});
 	});

})(window)