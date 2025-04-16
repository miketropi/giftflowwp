/**
 * Stripe Donation 
 * 
 */
import {loadStripe} from '@stripe/stripe-js';
const STRIPE_PUBLIC_KEY = 'pk_test_51RCupsGHehBuaAbSrAjpuxwEqiigNhCXMvcHexzqd2v8YY9lOPy403ifo5p89vrcviO4p3SJPkPEejxi2xIpiv9A00JfVSw8VW';

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
      const $validateWrapper = $wrapper.querySelector('[data-custom-validate="true"]');
      const $errorMessage = $wrapper.querySelector('.custom-error-message .custom-error-message-text');
      cardElement.mount($element);

      cardElement.on('change', async (event) => {
        console.log('event', event);

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

        // create token method
        const { token, error } = await this.getSelf().stripe.createToken(cardElement, {
          type: 'card',
          billing_details: {
            name: fields.card_name,
          }
        });

        if(error) {
          reject(error);
        } else {
          self.onSetField('stripe_payment_token_id', token.id);
          resolve(token);
        }
      });
    }
  }

  document.addEventListener('donationFormLoaded', (e) => {
    const { self, form } = e.detail;
    new StripeDonation(form, self);
  });

})(window);