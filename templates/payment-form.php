<?php
/**
 * Payment Form Template
 *
 * @package GiftFlowWP
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Get available payment gateways
$gateways = apply_filters('giftflowwp_payment_gateways', array());
?>

<div class="giftflowwp-payment-form-wrapper">
    <form id="giftflowwp-payment-form" class="giftflowwp-form">
        <div class="giftflowwp-form-row">
            <label for="giftflowwp-amount"><?php _e('Amount', 'giftflowwp'); ?></label>
            <input type="number" id="giftflowwp-amount" name="amount" min="1" step="0.01" required>
        </div>

        <div class="giftflowwp-form-row">
            <label><?php _e('Payment Method', 'giftflowwp'); ?></label>
            <div class="giftflowwp-payment-methods">
                <?php foreach ($gateways as $id => $gateway) : ?>
                    <div class="giftflowwp-payment-method">
                        <input type="radio" id="payment_method_<?php echo esc_attr($id); ?>" name="payment_method" value="<?php echo esc_attr($id); ?>" <?php checked($id, 'stripe'); ?>>
                        <label for="payment_method_<?php echo esc_attr($id); ?>"><?php echo esc_html($gateway['title']); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="stripe-payment-section" class="giftflowwp-payment-section">
            <div class="giftflowwp-form-row">
                <label for="giftflowwp-card-element"><?php _e('Card Details', 'giftflowwp'); ?></label>
                <div id="giftflowwp-card-element"></div>
                <div id="giftflowwp-card-errors" class="giftflowwp-error-message"></div>
            </div>
        </div>

        <div id="paypal-payment-section" class="giftflowwp-payment-section" style="display: none;">
            <div class="giftflowwp-form-row">
                <div id="paypal-button-container"></div>
                <div id="giftflowwp-paypal-errors" class="giftflowwp-error-message"></div>
            </div>
        </div>

        <div class="giftflowwp-form-row">
            <button type="submit" class="giftflowwp-button giftflowwp-button-primary">
                <?php _e('Pay Now', 'giftflowwp'); ?>
            </button>
        </div>
    </form>
</div>

<style>
.giftflowwp-payment-form-wrapper {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
}

.giftflowwp-form-row {
    margin-bottom: 20px;
}

.giftflowwp-form-row label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.giftflowwp-form-row input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.giftflowwp-payment-methods {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.giftflowwp-payment-method {
    display: flex;
    align-items: center;
    gap: 5px;
}

#giftflowwp-card-element {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
}

.giftflowwp-error-message {
    color: #dc3545;
    margin-top: 5px;
    font-size: 14px;
}

.giftflowwp-button {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.giftflowwp-button-primary {
    background-color: #007bff;
    color: #fff;
}

.giftflowwp-button-primary:hover {
    background-color: #0056b3;
}

.giftflowwp-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Handle payment method selection
    $('input[name="payment_method"]').on('change', function() {
        const method = $(this).val();
        
        // Hide all payment sections
        $('.giftflowwp-payment-section').hide();
        
        // Show selected payment section
        if (method === 'stripe') {
            $('#stripe-payment-section').show();
        } else if (method === 'paypal') {
            $('#paypal-payment-section').show();
        }
    });
    
    // Trigger change event on page load
    $('input[name="payment_method"]:checked').trigger('change');
});
</script> 