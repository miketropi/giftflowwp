/**
 * Stripe Donation 
 * 
 */
import {loadStripe} from '@stripe/stripe-js';
const STRIPE_PUBLIC_KEY = 'pk_test_51RCupsGHehBuaAbSrAjpuxwEqiigNhCXMvcHexzqd2v8YY9lOPy403ifo5p89vrcviO4p3SJPkPEejxi2xIpiv9A00JfVSw8VW';

((w) => {

  // make stripeDonation class
  const StripeDonation = class { 

    constructor(form, donationForm) {
      this.form = form;
      this.donationForm = donationForm;

      this.init();
    }

    async init() {
      this.stripe = await loadStripe(STRIPE_PUBLIC_KEY);
      this.stripeElements = this.stripe.elements();

      const cardElement = this.stripeElements.create('card');
      const $element = this.form.querySelector('#STRIPE-CARD-ELEMENT');
      const $wrapper = $element.closest('.donation-form__payment-method-description');
      const $errorMessage = $wrapper.querySelector('.custom-error-message .custom-error-message-text');
      cardElement.mount($element);

      cardElement.on('change', async (event) => {
        console.log('event', event);

        if (event.complete) {
          // console.log('Card information is complete.');
          $wrapper.dataset.customValidateStatus = 'true';
          $wrapper.classList.remove('error', 'custom-error');
        } else {
          // console.log('Card information is incomplete.');
          $wrapper.dataset.customValidateStatus = 'false';

          // add error message
          if(event.error) {
            $wrapper.classList.add('error', 'custom-error');
            $errorMessage.textContent = event.error.message;
          } else {
            $wrapper.classList.remove('error', 'custom-error');
            $errorMessage.textContent = '';
          }
        }
      });

    }
  }

  document.addEventListener('donationFormLoaded', (e) => {
    const { self, form } = e.detail;
    new StripeDonation(form, self);
  });

})(window);