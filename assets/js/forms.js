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
        steps.forEach(step => {
            step.classList.remove('active');
            step.style.display = 'none';
        });
        stepNavItems.forEach(item => item.classList.remove('active'));
        
        const currentStepElement = document.querySelector(`.giftflowwp-donation-form-step-${currentStep}`);
        currentStepElement.classList.add('active');
        currentStepElement.style.display = 'block';
        
        document.querySelector(`.giftflowwp-donation-form-step-nav-item[data-step="${currentStep}"]`).classList.add('active');
    }

    // Validate step 1 with improved error handling
    function validateStep1() {
        const amount = document.getElementById('giftflowwp-donation-form-input-amount').value;
        const firstName = document.getElementById('giftflowwp-donation-form-user-info-first-name').value;
        const lastName = document.getElementById('giftflowwp-donation-form-user-info-last-name').value;
        const email = document.getElementById('giftflowwp-donation-form-user-info-email').value;

        let isValid = true;
        let errorMessage = '';
        let errorField = null;

        if (!amount || parseFloat(amount) <= 0) {
            isValid = false;
            errorMessage = 'Please enter a valid donation amount';
            errorField = 'giftflowwp-donation-form-input-amount';
        } else if (!firstName) {
            isValid = false;
            errorMessage = 'Please enter your first name';
            errorField = 'giftflowwp-donation-form-user-info-first-name';
        } else if (!lastName) {
            isValid = false;
            errorMessage = 'Please enter your last name';
            errorField = 'giftflowwp-donation-form-user-info-last-name';
        } else if (!email || !isValidEmail(email)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
            errorField = 'giftflowwp-donation-form-user-info-email';
        }

        if (!isValid) {
            showError(errorMessage, errorField);
        }

        return isValid;
    }

    // Show error message with animation
    function showError(message, fieldId) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'giftflowwp-form-error';
        errorDiv.textContent = message;
        errorDiv.style.opacity = '0';
        errorDiv.style.transform = 'translateY(-10px)';
        
        const field = document.getElementById(fieldId);
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
        
        // Add error class to input
        field.classList.add('error');
        
        // Animate error message
        setTimeout(() => {
            errorDiv.style.opacity = '1';
            errorDiv.style.transform = 'translateY(0)';
        }, 10);
        
        // Remove error after 3 seconds
        setTimeout(() => {
            errorDiv.style.opacity = '0';
            errorDiv.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                errorDiv.remove();
                field.classList.remove('error');
            }, 300);
        }, 3000);
    }

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Preset amount buttons with active state
    const presetAmountButtons = form.querySelectorAll('.giftflowwp-donation-form-preset-amount');
    presetAmountButtons.forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.dataset.amount;
            const amountInput = document.getElementById('giftflowwp-donation-form-input-amount');
            amountInput.value = amount;
            
            // Update active state
            presetAmountButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            updateDonationSummary();
        });
    });

    // Amount input change with validation
    const amountInput = document.getElementById('giftflowwp-donation-form-input-amount');
    amountInput.addEventListener('input', function() {
        // Remove active state from preset buttons
        presetAmountButtons.forEach(btn => btn.classList.remove('active'));
        
        // Validate amount
        const amount = parseFloat(this.value);
        if (amount > 0) {
            this.classList.remove('error');
        } else {
            this.classList.add('error');
        }
        
        updateDonationSummary();
    });

    // Donation type change with animation
    const donationTypeInputs = form.querySelectorAll('input[name="donation_type"]');
    donationTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            const labels = form.querySelectorAll('.giftflowwp-donation-form-recurring-option label');
            labels.forEach(label => {
                label.style.transform = 'scale(1)';
                setTimeout(() => {
                    label.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        label.style.transform = 'scale(1)';
                    }, 150);
                }, 10);
            });
            updateDonationSummary();
        });
    });

    // Update donation summary with animation
    function updateDonationSummary() {
        const amount = document.getElementById('giftflowwp-donation-form-input-amount').value;
        const donationType = form.querySelector('input[name="donation_type"]:checked').value;
        const firstName = document.getElementById('giftflowwp-donation-form-user-info-first-name').value;
        const lastName = document.getElementById('giftflowwp-donation-form-user-info-last-name').value;
        const anonymous = document.getElementById('giftflowwp-donation-form-user-info-anonymous').checked;

        // Animate summary updates
        const summaryAmount = document.getElementById('summary-amount');
        const summaryType = document.getElementById('summary-type');
        const summaryDonor = document.getElementById('summary-donor');

        // Fade out
        [summaryAmount, summaryType, summaryDonor].forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-5px)';
        });

        // Update values
        const currencySymbol = (window.giftflowwpForms && window.giftflowwpForms.currency_symbol) || '$';
        summaryAmount.textContent = currencySymbol + amount;
        summaryType.textContent = donationType === 'monthly' ? 'Monthly' : 'One-time';
        summaryDonor.textContent = anonymous ? 'Anonymous' : `${firstName} ${lastName}`;

        // Fade in
        setTimeout(() => {
            [summaryAmount, summaryType, summaryDonor].forEach(el => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });
        }, 150);
    }

    // Payment method selection with animation
    const paymentMethodInputs = form.querySelectorAll('input[name="payment_method"]');
    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', function() {
            const selectedMethod = this.value;
            const labels = form.querySelectorAll('.giftflowwp-donation-form-payment-method label');
            
            // Animate payment method selection
            labels.forEach(label => {
                label.style.transform = 'scale(1)';
                setTimeout(() => {
                    label.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        label.style.transform = 'scale(1)';
                    }, 150);
                }, 10);
            });
            
            // Show/hide payment forms with animation
            const stripePaymentForm = document.getElementById('stripe-payment-form');
            stripePaymentForm.style.opacity = '0';
            stripePaymentForm.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                stripePaymentForm.style.display = selectedMethod === 'stripe' ? 'block' : 'none';
                if (selectedMethod === 'stripe') {
                    setTimeout(() => {
                        stripePaymentForm.style.opacity = '1';
                        stripePaymentForm.style.transform = 'translateY(0)';
                    }, 10);
                }
            }, 300);
        });
    });

    // Form submission with loading state
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

        // Show loading state
        const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="giftflowwp-loading-spinner"></span> Processing...';

        // Process payment based on selected method
        if (formData.payment_method === 'stripe') {
            processStripePayment(formData);
        } else if (formData.payment_method === 'paypal') {
            processPayPalPayment(formData);
        }
    });

    // Process Stripe payment with improved error handling
    function processStripePayment(formData) {
        const stripe = Stripe(giftflowwpForms.stripe_public_key);
        const elements = stripe.elements();

        const card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#dc2626',
                    iconColor: '#dc2626'
                }
            }
        });

        card.mount('#card-element');

        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
                displayError.style.opacity = '1';
                displayError.style.transform = 'translateY(0)';
            } else {
                displayError.style.opacity = '0';
                displayError.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    displayError.textContent = '';
                }, 300);
            }
        });

        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                errorElement.style.opacity = '1';
                errorElement.style.transform = 'translateY(0)';
                
                const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            } else {
                formData.payment_method_id = result.paymentMethod.id;
                submitDonation(formData);
            }
        });
    }

    // Process PayPal payment
    function processPayPalPayment(formData) {
        const params = new URLSearchParams();
        for (const [key, value] of Object.entries(formData)) {
            params.append(key, value);
        }

        window.location.href = `${giftflowwpForms.paypal_redirect_url}?${params.toString()}`;
    }

    // Submit donation with improved error handling
    function submitDonation(formData) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', giftflowwpForms.ajaxurl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        const data = new URLSearchParams();
        data.append('action', 'process_donation');
        for (const [key, value] of Object.entries(formData)) {
            data.append(key, value);
        }

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Show success message with animation
                    const successMessage = document.createElement('div');
                    successMessage.className = 'giftflowwp-success-message';
                    successMessage.innerHTML = `
                        <svg class="giftflowwp-success-icon" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        <p>${response.data.message || 'Thank you for your donation!'}</p>
                    `;
                    form.parentNode.insertBefore(successMessage, form.nextSibling);
                    
                    // Animate success message
                    setTimeout(() => {
                        successMessage.style.opacity = '1';
                        successMessage.style.transform = 'translateY(0)';
                    }, 10);
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = response.data.redirect_url;
                    }, 2000);
                } else {
                    showError(response.data.message || 'An error occurred. Please try again.');
                    const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            } else {
                showError('An error occurred. Please try again.');
                const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        };

        xhr.onerror = function() {
            showError('An error occurred. Please try again.');
            const submitButton = form.querySelector('.giftflowwp-donation-form-submit');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        };

        xhr.send(data.toString());
    }
}); 