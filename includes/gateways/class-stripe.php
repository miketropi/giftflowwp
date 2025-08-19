<?php 
/**
 * Stripe payment gateway 
 * 
 * @since 1.0.0
 * @version 1.0.0
 */

namespace GiftFlowWp\Gateways;

use Omnipay\Omnipay;
use Omnipay\Stripe\Gateway as StripeGateway;

/**
 * Stripe Gateway Class
 */
class Stripe_Gateway {
    
    /**
     * Initialize gateway
     */
    public function __construct() {
        $this->id = 'stripe';
        $this->title = __('Stripe', 'giftflowwp');
        $this->description = __('Accept payments via Stripe using credit cards', 'giftflowwp');
    }
    
    /**
     * Process payment
     *
     * @param array $data Payment data
     * @param int $donation_id Donation ID
     * @return mixed
     */
    public function process_payment($data, $donation_id = 0) {
        // Get Stripe settings
        $payment_options = get_option('giftflowwp_payment_options');
        $mode = isset($payment_options['stripe_mode']) ? $payment_options['stripe_mode'] : 'sandbox';
        
        if ($mode === 'sandbox') {
            $stripe_secret_key = $payment_options['stripe']['stripe_sandbox_secret_key'];
        } else {
            $stripe_secret_key = $payment_options['stripe']['stripe_live_secret_key']; 
        }

        if (empty($stripe_secret_key)) {
            return new \WP_Error('stripe_error', __('Stripe secret key is not configured', 'giftflowwp'));
        }

        try {
            // Initialize Stripe gateway
            /** @var StripeGateway $gateway */
            $gateway = Omnipay::create('Stripe\PaymentIntents');
            $gateway->setApiKey($stripe_secret_key);

            // Create purchase request
            $response = $gateway->purchase([
                'amount' => floatval($data['donation_amount']),
                'currency' => 'USD',
                'paymentMethod' => $data['stripe_payment_token_id'], 
                'description' => sprintf(
                    __('Donation from %s for campaign %s', 'giftflowwp'),
                    $data['donor_name'],
                    $data['campaign_id']
                ),
                'metadata' => [
                    'campaign_id' => $data['campaign_id'],
                    'donor_email' => $data['donor_email'],
                    'donor_name' => $data['donor_name']
                ],
                'confirm' => true,
                'returnUrl' => add_query_arg('giftflow_stripe_return', '1', home_url())
            ])->send();

            if ($response->isSuccessful()) {

                $transaction_id = $response->getTransactionReference();

                // update transaction ID in donation post meta
                if (!empty($donation_id)) {
                    update_post_meta($donation_id, '_transaction_id', $transaction_id);
                    update_post_meta($donation_id, '_payment_method', 'stripe');
                    update_post_meta($donation_id, '_status', 'completed');
                }

                // Payment successful
                return [
                    'success' => true,
                    'transaction_id' => $transaction_id,
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
            } elseif ($response->requiresAction()) {
                // Payment requires additional action (e.g., 3D Secure)
                return [
                    'success' => false,
                    'requires_action' => true,
                    'client_secret' => $response->getPaymentIntentClientSecret(),
                    'message' => __('Payment requires additional authentication', 'giftflowwp')
                ];
            } else {
                // Payment failed
                return new \WP_Error(
                    'stripe_error',
                    $response->getMessage() ?: __('Payment failed', 'giftflowwp')
                );
            }
        } catch (\Exception $e) {
            return new \WP_Error('stripe_error', $e->getMessage());
        }
    }
}

/**
 * Get Stripe Gateway instance
 * 
 * @return Stripe_Gateway
 */
function giftflowwp_get_stripe_gateway() {
    return new \GiftFlowWp\Gateways\Stripe_Gateway();
}

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
 * 
 * @param array $data Payment data
 * @param int $donation_id Donation ID
 * @return mixed
 */
function giftflowwp_process_payment_stripe( $data = [], $donation_id = 0 ) {
    // Get Stripe gateway instance
    $stripe_gateway = giftflowwp_get_stripe_gateway();
    
    // Process payment using the class
    return $stripe_gateway->process_payment( $data, $donation_id );
}

/**
 * Logging functions
 */
function giftflowwp_log_stripe_request($params, $donation_id) {
	if (!defined('WP_DEBUG') || !WP_DEBUG) {
		return;
	}

	$log_data = [
		'action' => 'stripe_payment_request',
		'donation_id' => $donation_id,
		'amount' => $params['amount'],
		'currency' => $params['currency'],
		'timestamp' => current_time('mysql')
	];

	error_log('[GiftFlow Stripe Request] ' . json_encode($log_data));
}

function giftflowwp_log_stripe_success($transaction_id, $donation_id) {
	$log_data = [
		'action' => 'stripe_payment_success',
		'donation_id' => $donation_id,
		'transaction_id' => $transaction_id,
		'timestamp' => current_time('mysql')
	];

	error_log('[GiftFlow Stripe Success] ' . json_encode($log_data));
}

function giftflowwp_log_stripe_error($type, $message, $donation_id, $code = '') {
	$log_data = [
		'action' => 'stripe_payment_error',
		'type' => $type,
		'donation_id' => $donation_id,
		'error_message' => $message,
		'error_code' => $code,
		'timestamp' => current_time('mysql')
	];

	error_log('[GiftFlow Stripe Error] ' . json_encode($log_data));
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
					'nonce' => wp_create_nonce('giftflow_stripe_nonce'),
					'return_url' => add_query_arg('giftflow_stripe_return', '1', home_url()),
			)
	);
}

add_action('wp_enqueue_scripts', 'GiftFlowWp\Gateways\giftflowwp_enqueue_stripe_scripts');
