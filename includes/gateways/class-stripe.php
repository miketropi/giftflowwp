<?php 
/**
 * Stripe payment gateway 
 * 
 * @since 1.0.0
 * @version 1.0.0
 */

use Omnipay\Omnipay;
use Omnipay\Stripe\Gateway as StripeGateway;

/**
 * Process Stripe payment
 * 
 * Data include:
 * - stripe_payment_token_id
 * - amount
 * - campaign_id
 * - donor_email
 * - donor_name
 * - payment_method
 */
function giftflowwp_process_payment_stripe( $data ) {
    // Get Stripe settings
    $payment_options = get_option('giftflowwp_payment_options');
		$mode = isset($payment_options['stripe_mode']) ? $payment_options['stripe_mode'] : 'sandbox';
	
		if ($mode === 'sandbox') {
			$stripe_secret_key = $payment_options['stripe']['stripe_sandbox_secret_key'];
		} else {
			$stripe_secret_key = $payment_options['stripe']['stripe_live_secret_key']; 
		}

    if (empty($stripe_secret_key)) {
        return new WP_Error('stripe_error', __('Stripe secret key is not configured', 'giftflowwp'));
    }

    try {
			// Initialize Stripe gateway
			/** @var StripeGateway $gateway */
			$gateway = Omnipay::create('Stripe');
			$gateway->setApiKey($stripe_secret_key);

			// Create purchase request
			$response = $gateway->purchase([
				'amount' => floatval($data['donation_amount']),
				'currency' => 'USD',
				'source' => $data['stripe_payment_token_id'], 
				'description' => sprintf(
					__('Donation from %s for campaign %s', 'giftflowwp'),
					$data['donor_name'],
					$data['campaign_id']
				),
				'metadata' => [
					'campaign_id' => $data['campaign_id'],
					'donor_email' => $data['donor_email'],
					'donor_name' => $data['donor_name']
				]
			])->send();

			// wp_send_json_success( [
			// 	'step' => 'process_payment_stripe',
			// 	'data' => $response,
			// ] );

			if ($response->isSuccessful()) {

				// Payment successful
				return [
					'success' => true,
					'transaction_id' => $response->getTransactionReference(),
					'message' => __('Payment processed successfully', 'giftflowwp')
				];
			} elseif ($response->isRedirect()) {
					// 3D Secure authentication required
				return [
					'success' => false,
					'redirect' => true,
					'redirect_url' => $response->getRedirectUrl(),
					'message' => __('3D Secure authentication required', 'giftflowwp')
				];
			} else {
				// Payment failed
				return new WP_Error(
					'stripe_error',
					$response->getMessage() ?: __('Payment failed', 'giftflowwp')
				);
			}
    } catch (\Exception $e) {
			return new WP_Error('stripe_error', $e->getMessage());
    }
}

// wp enqueue stripe scripts 
function giftflowwp_enqueue_stripe_scripts() {
	$script_handle = 'giftflowwp-stripe-donation';

	/**
	 * stripe-donation.bundle.js
	 * 
	 * dependencies:
	 * - jquery
	 * - giftflowwp-donation-forms
	 */
	wp_enqueue_script(
		$script_handle,
		GIFTFLOWWP_PLUGIN_URL . 'assets/js/stripe-donation.bundle.js', 
		array('jquery', 'giftflowwp-donation-forms'), 
		GIFTFLOWWP_VERSION, 
		true);

	// get payment options
	$payment_options = get_option('giftflowwp_payment_options');
	$enabled = isset($payment_options['stripe']['stripe_enabled']) ? $payment_options['stripe']['stripe_enabled'] : false;
	
	if (!$enabled) {
		return;
	}

	$mode = isset($payment_options['stripe_mode']) ? $payment_options['stripe_mode'] : 'sandbox';

	if ($mode === 'sandbox') {
		$stripe_publishable_key = $payment_options['stripe']['stripe_sandbox_publishable_key'];
	} else {
		$stripe_publishable_key = $payment_options['stripe']['stripe_live_publishable_key'];
	}

	wp_localize_script(
			$script_handle, 
			'giftflowwpStripeDonation',
			array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'stripe_publishable_key' => $stripe_publishable_key,
					'mode' => $mode,
			)
	);
}

add_action('wp_enqueue_scripts', 'giftflowwp_enqueue_stripe_scripts');
