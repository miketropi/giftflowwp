/**
 * Stripe Donation 
 * 
 */
import {loadStripe} from '@stripe/stripe-js';
const STRIPE_PUBLIC_KEY = giftflowStripeDonation.stripe_publishable_key;

((w) => {
  'use strict';

  // make stripeDonation class
  const StripeDonation = class { 

    constructor(form, formObject) {
      this.form = form;
      this.formObject = formObject;

      this.init();
    }

    getSelf() {
      return this;
    }

    async init() {
      const self = this;
      this.stripe = await loadStripe(STRIPE_PUBLIC_KEY);
      this.stripeElements = this.stripe.elements();

      const cardElement = this.stripeElements.create('card');
      const $element = this.form.querySelector('#STRIPE-CARD-ELEMENT');
      const $wrapper = $element.closest('.donation-form__payment-method-description');
      const $wrapperField = $element.closest('.donation-form__field');
      const $validateWrapper = $wrapperField; //$wrapperField.querySelector('[data-custom-validate="true"]');
      const $errorMessage = $wrapperField.querySelector('.custom-error-message .custom-error-message-text');
      
      cardElement.mount($element);

      cardElement.on('change', async (event) => {
        // console.log('event', event);

        if (event.complete) {
          // console.log(self.formObject.fields);
          // console.log('Card information is complete.');
          
          $validateWrapper.dataset.customValidateStatus = 'true';
          $validateWrapper.classList.remove('error', 'custom-error');
        } else {
          // console.log('Card information is incomplete.');
          $validateWrapper.dataset.customValidateStatus = 'false';

          // add error message
          if(event.error) {
            $validateWrapper.classList.add('error', 'custom-error');
            $errorMessage.textContent = event.error.message;
          } else {
            $validateWrapper.classList.remove('error', 'custom-error');
            $errorMessage.textContent = '';
          }
        }
      });


      // add event listener to form
      this.form.addEventListener('donationFormBeforeSubmit', async (e) => {
        const { self, fields, resolve, reject } = e.detail;

        // create token method (Old)
        // const { token, error } = await this.getSelf().stripe.createToken(cardElement, {
        //   type: 'card',
        //   billing_details: {
        //     name: fields.card_name,
        //   }
        // });

        // new
        const {paymentMethod, error} = await this.getSelf().stripe.createPaymentMethod({
          type: 'card',
          card: cardElement,
          billing_details: {
            name: fields.card_name,
            // email: fields.card_email,
          }});

        // console.log('token', token);
        // console.log('error', error);

        if(error) {
          $validateWrapper.classList.add('error', 'custom-error');
          // $validateWrapper.querySelector('.custom-error-message-text').textContent = error.message;
          $errorMessage.textContent = error.message;
          // console.log('Stripe error:', error.message, $errorMessage);
          
          reject(error);
        } else {
          // console.log('Stripe payment method created:', paymentMethod);
          // self.onSetField('stripe_payment_token_id', token.id);
          self.onSetField('stripe_payment_method_id', paymentMethod.id);
          resolve(paymentMethod)
          // resolve(token);
        }
      });
    }
  }

  document.addEventListener('donationFormLoaded', (e) => {
    const { self, form } = e.detail;
    new StripeDonation(form, self);
  });

})(window);