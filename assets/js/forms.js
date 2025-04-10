document.addEventListener('DOMContentLoaded', function() {
    // Form steps handling
    const form = document.getElementById('giftflowwp-donation-form');
    const steps = form.querySelectorAll('.giftflowwp-donation-form-step');
    const stepNavItems = form.querySelectorAll('.giftflowwp-donation-form-step-nav-item');
    let currentStep = 1;

    // Next step button
    const nextStepButton = form.querySelector('.giftflowwp-donation-form-next-step');
    nextStepButton.addEventListener('click', function() {
        if (validateStep1()) {
            currentStep++;
            updateSteps();
            updateDonationSummary();
        }
    });

    // Previous step button
    const prevStepButton = form.querySelector('.giftflowwp-donation-form-prev-step');
    prevStepButton.addEventListener('click', function() {
        currentStep--;
        updateSteps();
    });

    // Update steps visibility and navigation
    function updateSteps() {
        steps.forEach(step => step.classList.remove('active'));
        stepNavItems.forEach(item => item.classList.remove('active'));
        
        document.querySelector(`.giftflowwp-donation-form-step-${currentStep}`).classList.add('active');
        document.querySelector(`.giftflowwp-donation-form-step-nav-item[data-step="${currentStep}"]`).classList.add('active');
    }

    // Validate step 1
    function validateStep1() {
        const amount = document.getElementById('giftflowwp-donation-form-input-amount').value;
        const firstName = document.getElementById('giftflowwp-donation-form-user-info-first-name').value;
        const lastName = document.getElementById('giftflowwp-donation-form-user-info-last-name').value;
        const email = document.getElementById('giftflowwp-donation-form-user-info-email').value;

        let isValid = true;
        let errorMessage = '';

        if (!amount || parseFloat(amount) <= 0) {
            isValid = false;
            errorMessage = 'Please enter a valid donation amount';
        } else if (!firstName) {
            isValid = false;
            errorMessage = 'Please enter your first name';
        } else if (!lastName) {
            isValid = false;
            errorMessage = 'Please enter your last name';
        } else if (!email || !isValidEmail(email)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        }

        if (!isValid) {
            alert(errorMessage);
        }

        return isValid;
    }

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Preset amount buttons
    const presetAmountButtons = form.querySelectorAll('.giftflowwp-donation-form-preset-amount');
    presetAmountButtons.forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.dataset.amount;
            document.getElementById('giftflowwp-donation-form-input-amount').value = amount;
            updateDonationSummary();
        });
    });

    // Amount input change
    const amountInput = document.getElementById('giftflowwp-donation-form-input-amount');
    amountInput.addEventListener('input', function() {
        updateDonationSummary();
    });

    // Donation type change
    const donationTypeInputs = form.querySelectorAll('input[name="donation_type"]');
    donationTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateDonationSummary();
        });
    });

    // Update donation summary
    function updateDonationSummary() {
        const amount = document.getElementById('giftflowwp-donation-form-input-amount').value;
        const donationType = form.querySelector('input[name="donation_type"]:checked').value;
        const firstName = document.getElementById('giftflowwp-donation-form-user-info-first-name').value;
        const lastName = document.getElementById('giftflowwp-donation-form-user-info-last-name').value;
        const anonymous = document.getElementById('giftflowwp-donation-form-user-info-anonymous').checked;

        // Update amount with fallback for currency symbol
        const currencySymbol = (window.giftflowwpForms && window.giftflowwpForms.currency_symbol) || '$';
        document.getElementById('summary-amount').textContent = currencySymbol + amount;

        // Update type
        document.getElementById('summary-type').textContent = donationType === 'monthly' ? 'Monthly' : 'One-time';

        // Update donor name
        if (anonymous) {
            document.getElementById('summary-donor').textContent = 'Anonymous';
        } else {
            document.getElementById('summary-donor').textContent = `${firstName} ${lastName}`;
        }
    }

    // Payment method selection
    const paymentMethodInputs = form.querySelectorAll('input[name="payment_method"]');
    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', function() {
            const selectedMethod = this.value;
            
            // Hide all payment forms
            const stripePaymentForm = document.getElementById('stripe-payment-form');
            stripePaymentForm.style.display = 'none';
            
            // Show selected payment form
            if (selectedMethod === 'stripe') {
                stripePaymentForm.style.display = 'block';
            }
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!validateStep1()) {
            currentStep = 1;
            updateSteps();
            return;
        }

        const formData = {
            amount: document.getElementById('giftflowwp-donation-form-input-amount').value,
            first_name: document.getElementById('giftflowwp-donation-form-user-info-first-name').value,
            last_name: document.getElementById('giftflowwp-donation-form-user-info-last-name').value,
            email: document.getElementById('giftflowwp-donation-form-user-info-email').value,
            message: document.getElementById('giftflowwp-donation-form-user-info-message').value,
            anonymous: document.getElementById('giftflowwp-donation-form-user-info-anonymous').checked,
            payment_method: form.querySelector('input[name="payment_method"]:checked').value,
            donation_type: form.querySelector('input[name="donation_type"]:checked').value,
            campaign_id: form.dataset.campaignId,
            nonce: giftflowwpForms.nonce
        };

        // Disable submit button
        const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
        submitButton.disabled = true;

        // Process payment based on selected method
        if (formData.payment_method === 'stripe') {
            processStripePayment(formData);
        } else if (formData.payment_method === 'paypal') {
            processPayPalPayment(formData);
        }
    });

    // Process Stripe payment
    function processStripePayment(formData) {
        // Initialize Stripe
        const stripe = Stripe(giftflowwpForms.stripe_public_key);
        const elements = stripe.elements();

        // Create card element
        const card = elements.create('card');
        card.mount('#card-element');

        // Handle card errors
        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Create payment method
        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
                submitButton.disabled = false;
            } else {
                formData.payment_method_id = result.paymentMethod.id;
                submitDonation(formData);
            }
        });
    }

    // Process PayPal payment
    function processPayPalPayment(formData) {
        // Create URL parameters
        const params = new URLSearchParams();
        for (const [key, value] of Object.entries(formData)) {
            params.append(key, value);
        }

        // Redirect to PayPal
        window.location.href = `${giftflowwpForms.paypal_redirect_url}?${params.toString()}`;
    }

    // Submit donation
    function submitDonation(formData) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', giftflowwpForms.ajaxurl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Create form data
        const data = new URLSearchParams();
        data.append('action', 'process_donation');
        for (const [key, value] of Object.entries(formData)) {
            data.append(key, value);
        }

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Show success message
                    alert('Thank you for your donation!');
                    // Redirect to thank you page
                    window.location.href = response.data.redirect_url;
                } else {
                    // Show error message
                    alert(response.data.message);
                    const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
                    submitButton.disabled = false;
                }
            } else {
                alert('An error occurred. Please try again.');
                const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
                submitButton.disabled = false;
            }
        };

        xhr.onerror = function() {
            alert('An error occurred. Please try again.');
            const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
            submitButton.disabled = false;
        };

        xhr.send(data.toString());
    }
}); 