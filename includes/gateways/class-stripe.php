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
    $stripe_secret_key = isset($payment_options['stripe_secret_key']) ? $payment_options['stripe_secret_key'] : '';
		$stripe_secret_key = 'sk_test_51RCupsGHehBuaAbS59hjg6nwV5RtmTavKav6MGuh9Y7Uor1W618l3pUrg0MKUzzoeuq4nCBlyIDRDFkbW8IyMrBB00ghY2d11g';

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

			wp_send_json_success( [
				'step' => 'process_payment_stripe',
				'data' => $response,
			] );

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