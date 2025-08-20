<?php
/**
 * Stripe Payment Gateway for GiftFlowWp
 *
 * This class implements Stripe payment processing using Omnipay v3
 * with support for Payment Intents, 3D Secure, and webhooks.
 *
 * @package GiftFlowWp
 * @subpackage Gateways
 * @since 1.0.0
 * @version 1.0.0
 */

namespace GiftFlowWp\Gateways;

use Omnipay\Omnipay;
use Omnipay\Stripe\PaymentIntentsGateway;

/**
 * Stripe Gateway Class
 */
class Stripe_Gateway extends Gateway_Base {
    
    /**
     * Omnipay gateway instance
     *
     * @var PaymentIntentsGateway
     */
    private $gateway;

    /**
     * Initialize gateway properties
     */
    protected function init_gateway() {
        $this->id = 'stripe';
        $this->title = __('Credit Card (Stripe)', 'giftflowwp');
        $this->description = __('Accept payments securely via Stripe using credit cards', 'giftflowwp');
        $this->icon = GIFTFLOWWP_PLUGIN_URL . 'assets/images/stripe-icon.png';
        $this->order = 10;
        $this->supports = array(
            'refunds',
            'webhooks',
            '3d_secure',
            'payment_intents',
            'customer_creation'
        );

        // Initialize Omnipay gateway
        $this->init_omnipay_gateway();
        
        // Add Stripe-specific assets
        $this->add_stripe_assets();
    }

    /**
     * Initialize Omnipay gateway
     */
    private function init_omnipay_gateway() {
        $api_key = $this->get_api_key();
        
        if (!empty($api_key)) {
            $this->gateway = Omnipay::create('Stripe\PaymentIntents');
            $this->gateway->setApiKey($api_key);
        }
    }

    /**
     * Get API key based on mode
     *
     * @return string
     */
    private function get_api_key() {
        $mode = $this->get_setting('stripe_mode', 'sandbox');
        
        if ($mode === 'live') {
            return $this->get_setting('stripe_live_secret_key');
        }
        
        return $this->get_setting('stripe_sandbox_secret_key');
    }

    /**
     * Get publishable key based on mode
     *
     * @return string
     */
    public function get_publishable_key() {
        $mode = $this->get_setting('stripe_mode', 'sandbox');
        
        if ($mode === 'live') {
            return $this->get_setting('stripe_live_publishable_key');
        }
        
        return $this->get_setting('stripe_sandbox_publishable_key');
    }

    /**
     * Add Stripe-specific assets
     */
    private function add_stripe_assets() {
        // Stripe.js library
        // $this->add_script('stripe-js', array(
        //     'src' => 'https://js.stripe.com/v3/',
        //     'deps' => array(),
        //     'version' => '3',
        //     'frontend' => true,
        //     'admin' => false,
        //     'in_footer' => false
        // ));

        // Custom Stripe donation script
        $this->add_script('giftflowwp-stripe-donation', array(
            'src' => GIFTFLOWWP_PLUGIN_URL . 'assets/js/stripe-donation.bundle.js',
            'deps' => array('jquery', 'stripe-js', 'giftflowwp-donation-forms'),
            'version' => GIFTFLOWWP_VERSION,
            'frontend' => true,
            'admin' => false,
            'in_footer' => true,
            'localize' => array(
                'name' => 'giftflowwpStripeDonation',
                'data' => $this->get_script_data()
            )
        ));

        // Stripe donation styles
        // $this->add_style('giftflowwp-stripe-donation', array(
        //     'src' => GIFTFLOWWP_PLUGIN_URL . 'assets/css/stripe-donation.bundle.css',
        //     'deps' => array('giftflowwp-donation-forms'),
        //     'version' => GIFTFLOWWP_VERSION,
        //     'frontend' => true,
        //     'admin' => false
        // ));
    }

    /**
     * Get script localization data
     *
     * @return array
     */
    private function get_script_data() {
        return array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'stripe_publishable_key' => $this->get_publishable_key(),
            'mode' => $this->get_setting('stripe_mode', 'sandbox'),
            'nonce' => wp_create_nonce('giftflow_stripe_nonce'),
            'return_url' => add_query_arg('giftflow_stripe_return', '1', home_url()),
            'messages' => array(
                'processing' => __('Processing payment...', 'giftflowwp'),
                'error' => __('Payment failed. Please try again.', 'giftflowwp'),
                'invalid_card' => __('Please enter valid card details.', 'giftflowwp'),
                'authentication_required' => __('Additional authentication required.', 'giftflowwp')
            )
        );
    }

    /**
     * Get gateway settings fields
     *
     * @return array
     */
    protected function get_settings_fields() {
        return array(
            'enabled' => array(
                'title' => __('Enable Stripe', 'giftflowwp'),
                'type' => 'checkbox',
                'description' => __('Enable Stripe payment gateway', 'giftflowwp'),
                'default' => 'no'
            ),
            'title' => array(
                'title' => __('Title', 'giftflowwp'),
                'type' => 'text',
                'description' => __('Title that users see during checkout', 'giftflowwp'),
                'default' => __('Credit Card (Stripe)', 'giftflowwp')
            ),
            'description' => array(
                'title' => __('Description', 'giftflowwp'),
                'type' => 'textarea',
                'description' => __('Description that users see during checkout', 'giftflowwp'),
                'default' => __('Pay securely with your credit card via Stripe', 'giftflowwp')
            ),
            'mode' => array(
                'title' => __('Mode', 'giftflowwp'),
                'type' => 'select',
                'description' => __('Select sandbox for testing, live for production', 'giftflowwp'),
                'options' => array(
                    'sandbox' => __('Sandbox (Test)', 'giftflowwp'),
                    'live' => __('Live (Production)', 'giftflowwp')
                ),
                'default' => 'sandbox'
            ),
            'sandbox_secret_key' => array(
                'title' => __('Sandbox Secret Key', 'giftflowwp'),
                'type' => 'password',
                'description' => __('Your Stripe test secret key (starts with sk_test_)', 'giftflowwp')
            ),
            'sandbox_publishable_key' => array(
                'title' => __('Sandbox Publishable Key', 'giftflowwp'),
                'type' => 'text',
                'description' => __('Your Stripe test publishable key (starts with pk_test_)', 'giftflowwp')
            ),
            'live_secret_key' => array(
                'title' => __('Live Secret Key', 'giftflowwp'),
                'type' => 'password',
                'description' => __('Your Stripe live secret key (starts with sk_live_)', 'giftflowwp')
            ),
            'live_publishable_key' => array(
                'title' => __('Live Publishable Key', 'giftflowwp'),
                'type' => 'text',
                'description' => __('Your Stripe live publishable key (starts with pk_live_)', 'giftflowwp')
            ),
            'capture' => array(
                'title' => __('Capture Payment', 'giftflowwp'),
                'type' => 'select',
                'description' => __('Capture payment immediately or authorize for later capture', 'giftflowwp'),
                'options' => array(
                    'yes' => __('Capture immediately', 'giftflowwp'),
                    'no' => __('Authorize only (capture later)', 'giftflowwp')
                ),
                'default' => 'yes'
            ),
            'statement_descriptor' => array(
                'title' => __('Statement Descriptor', 'giftflowwp'),
                'type' => 'text',
                'description' => __('Text that appears on customer\'s credit card statement (max 22 characters)', 'giftflowwp'),
                'default' => get_bloginfo('name')
            ),
            'webhook_enabled' => array(
                'title' => __('Enable Webhooks', 'giftflowwp'),
                'type' => 'checkbox',
                'description' => sprintf(
                    __('Enable webhooks for payment status updates. Webhook URL: %s', 'giftflowwp'),
                    '<code>' . $this->get_webhook_url() . '</code>'
                ),
                'default' => 'yes'
            )
        );
    }

    /**
     * Additional hooks for Stripe gateway
     */
    protected function init_additional_hooks() {
        // AJAX handlers
        add_action('wp_ajax_giftflowwp_process_stripe_payment', array($this, 'ajax_process_payment'));
        add_action('wp_ajax_nopriv_giftflowwp_process_stripe_payment', array($this, 'ajax_process_payment'));
        
        // Webhook handler
        add_action('wp_ajax_giftflowwp_stripe_webhook', array($this, 'handle_webhook'));
        add_action('wp_ajax_nopriv_giftflowwp_stripe_webhook', array($this, 'handle_webhook'));
        
        // Return URL handler
        add_action('init', array($this, 'handle_return_url'));
    }

    /**
     * Process payment
     *
     * @param array $data Payment data
     * @param int $donation_id Donation ID
     * @return mixed
     */
    public function process_payment($data, $donation_id = 0) {
        if (!$this->gateway) {
            return new \WP_Error('stripe_error', __('Stripe is not properly configured', 'giftflowwp'));
        }

        if (!$donation_id) {
            return new \WP_Error('stripe_error', __('Donation ID is required', 'giftflowwp'));
        }

        try {
            $payment_data = $this->prepare_payment_data($data, $donation_id);
            
            if ($this->get_setting('stripe_capture', '1') === '1') {
                $response = $this->gateway->purchase($payment_data)->send();
            } else {
                $response = $this->gateway->authorize($payment_data)->send();
            }

            return $this->handle_payment_response($response, $donation_id);
            
        } catch (\Exception $e) {
            $this->log_error('payment_exception', $e->getMessage(), $donation_id);
            return new \WP_Error('stripe_error', $e->getMessage());
        }
    }

    /**
     * Prepare payment data for Omnipay
     *
     * @param array $data
     * @param int $donation_id
     * @return array
     */
    private function prepare_payment_data($data, $donation_id) {
        $statement_descriptor = $this->get_setting('statement_descriptor', get_bloginfo('name'));
        $statement_descriptor = substr($statement_descriptor, 0, 22); // Stripe limit
        
        return array(
            'amount' => floatval($data['donation_amount']),
            'currency' => $this->get_currency(),
            'paymentMethod' => $data['stripe_payment_method_id'],
            'description' => sprintf(
                __('Donation from %s for campaign %s', 'giftflowwp'),
                sanitize_text_field($data['donor_name']),
                $data['campaign_id']
            ),
            'customer' => array(
                'name' => sanitize_text_field($data['donor_name']),
                'email' => sanitize_email($data['donor_email']),
            ),
            'metadata' => array(
                'donation_id' => $donation_id,
                'campaign_id' => $data['campaign_id'],
                'donor_email' => sanitize_email($data['donor_email']),
                'donor_name' => sanitize_text_field($data['donor_name']),
                'site_url' => home_url()
            ),
            'confirm' => true,
            'returnUrl' => $this->get_return_url($donation_id),
            'statementDescriptor' => $statement_descriptor
        );
    }

    /**
     * Handle payment response
     *
     * @param mixed $response
     * @param int $donation_id
     * @return array|\WP_Error
     */
    private function handle_payment_response($response, $donation_id) {
        if ($response->isSuccessful()) {
            return $this->handle_successful_payment($response, $donation_id);
        } elseif ($response->isRedirect()) {
            return $this->handle_redirect_payment($response, $donation_id);
        } elseif (method_exists($response, 'requiresAction') && $response->requiresAction()) {
            return $this->handle_action_required($response, $donation_id);
        } else {
            return $this->handle_failed_payment($response, $donation_id);
        }
    }

    /**
     * Handle successful payment
     *
     * @param mixed $response
     * @param int $donation_id
     * @return array
     */
    private function handle_successful_payment($response, $donation_id) {
        $payment_intent_id = $response->getPaymentIntentReference();
        $all_data = $response->getData();

        // Update donation meta
        update_post_meta($donation_id, '_transaction_id', $payment_intent_id);
        update_post_meta($donation_id, '_transaction_raw_data', wp_json_encode($all_data));
        update_post_meta($donation_id, '_payment_method', 'stripe');
        update_post_meta($donation_id, '_payment_status', 'completed');

        $this->log_success($payment_intent_id, $donation_id);

        do_action('giftflowwp_stripe_payment_completed', $donation_id, $payment_intent_id, $all_data);

        return array(
            'success' => true,
            'transaction_id' => $payment_intent_id,
            'message' => __('Payment processed successfully', 'giftflowwp')
        );
    }

    /**
     * Handle redirect payment (3D Secure)
     *
     * @param mixed $response
     * @param int $donation_id
     * @return array
     */
    private function handle_redirect_payment($response, $donation_id) {
        $payment_intent_id = $response->getPaymentIntentReference();
        
        // Store payment intent for later verification
        update_post_meta($donation_id, '_stripe_payment_intent_id', $payment_intent_id);
        update_post_meta($donation_id, '_payment_status', 'processing');

        return array(
            'success' => false,
            'redirect' => true,
            'redirect_url' => $response->getRedirectUrl(),
            'message' => __('3D Secure authentication required', 'giftflowwp')
        );
    }

    /**
     * Handle action required payment
     *
     * @param mixed $response
     * @param int $donation_id
     * @return array
     */
    private function handle_action_required($response, $donation_id) {
        $client_secret = method_exists($response, 'getPaymentIntentClientSecret') 
            ? $response->getPaymentIntentClientSecret() 
            : '';

        return array(
            'success' => false,
            'requires_action' => true,
            'client_secret' => $client_secret,
            'message' => __('Payment requires additional authentication', 'giftflowwp')
        );
    }

    /**
     * Handle failed payment
     *
     * @param mixed $response
     * @param int $donation_id
     * @return \WP_Error
     */
    private function handle_failed_payment($response, $donation_id) {
        $error_message = $response->getMessage() ?: __('Payment failed', 'giftflowwp');
        $error_code = method_exists($response, 'getCode') ? $response->getCode() : '';
        
        $this->log_error('payment_failed', $error_message, $donation_id, $error_code);
        
        update_post_meta($donation_id, '_payment_status', 'failed');
        update_post_meta($donation_id, '_payment_error', $error_message);

        return new \WP_Error('stripe_error', $error_message);
    }

    /**
     * AJAX handler for processing payments
     */
    public function ajax_process_payment() {
        check_ajax_referer('giftflow_stripe_nonce', 'nonce');

        $data = $_POST;
        $donation_id = intval($data['donation_id']);

        $result = $this->process_payment($data, $donation_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'message' => $result->get_error_message()
            ));
        } else {
            wp_send_json_success($result);
        }
    }

    /**
     * Handle webhook notifications
     */
    public function handle_webhook() {
        if (!$this->get_setting('stripe_webhook_enabled', '1')) {
            status_header(200);
            exit;
        }

        $payload = file_get_contents('php://input');
        $event = json_decode($payload, true);

        if (!$event || !isset($event['type'])) {
            status_header(400);
            exit;
        }

        try {
            switch ($event['type']) {
                case 'payment_intent.succeeded':
                    $this->handle_payment_intent_succeeded($event['data']['object']);
                    break;
                case 'payment_intent.payment_failed':
                    $this->handle_payment_intent_failed($event['data']['object']);
                    break;
            }
            
            status_header(200);
            echo 'OK';
            
        } catch (\Exception $e) {
            $this->log_error('webhook_error', $e->getMessage(), 0);
            status_header(500);
        }
        
        exit;
    }

    /**
     * Handle return URL from 3D Secure
     */
    public function handle_return_url() {
        if (!isset($_GET['giftflow_stripe_return']) || !isset($_GET['payment_intent'])) {
            return;
        }

        $payment_intent_id = sanitize_text_field($_GET['payment_intent']);
        
        // Find donation by payment intent ID
        $donations = get_posts(array(
            'post_type' => 'donation',
            'meta_key' => '_transaction_id',
            'meta_value' => $payment_intent_id,
            'posts_per_page' => 1
        ));

        if (!empty($donations)) {
            $donation_id = $donations[0]->ID;
            
            // Verify payment intent status
            try {
                $response = $this->gateway->completePurchase(array(
                    'paymentIntentReference' => $payment_intent_id
                ))->send();

                if ($response->isSuccessful()) {
                    $this->handle_successful_payment($response, $donation_id);
                    wp_redirect(add_query_arg('payment_status', 'success', home_url()));
                } else {
                    $this->handle_failed_payment($response, $donation_id);
                    wp_redirect(add_query_arg('payment_status', 'failed', home_url()));
                }
            } catch (\Exception $e) {
                $this->log_error('return_url_error', $e->getMessage(), $donation_id);
                wp_redirect(add_query_arg('payment_status', 'error', home_url()));
            }
            
            exit;
        }
    }

    /**
     * Handle successful payment intent webhook
     *
     * @param array $payment_intent
     */
    private function handle_payment_intent_succeeded($payment_intent) {
        $donation_id = isset($payment_intent['metadata']['donation_id']) 
            ? intval($payment_intent['metadata']['donation_id']) 
            : 0;

        if ($donation_id) {
            update_post_meta($donation_id, '_payment_status', 'completed');
            update_post_meta($donation_id, '_transaction_id', $payment_intent['id']);
            
            do_action('giftflowwp_stripe_webhook_payment_completed', $donation_id, $payment_intent);
        }
    }

    /**
     * Handle failed payment intent webhook
     *
     * @param array $payment_intent
     */
    private function handle_payment_intent_failed($payment_intent) {
        $donation_id = isset($payment_intent['metadata']['donation_id']) 
            ? intval($payment_intent['metadata']['donation_id']) 
            : 0;

        if ($donation_id) {
            $error_message = isset($payment_intent['last_payment_error']['message']) 
                ? $payment_intent['last_payment_error']['message'] 
                : __('Payment failed', 'giftflowwp');
                
            update_post_meta($donation_id, '_payment_status', 'failed');
            update_post_meta($donation_id, '_payment_error', $error_message);
            
            do_action('giftflowwp_stripe_webhook_payment_failed', $donation_id, $payment_intent);
        }
    }

    /**
     * Get currency code
     *
     * @return string
     */
    private function get_currency() {
        return apply_filters('giftflowwp_stripe_currency', 'USD');
    }

    /**
     * Get return URL
     *
     * @param int $donation_id
     * @return string
     */
    private function get_return_url($donation_id) {
        return add_query_arg(array(
            'giftflow_stripe_return' => '1',
            'donation_id' => $donation_id
        ), home_url());
    }

    /**
     * Get webhook URL
     *
     * @return string
     */
    public function get_webhook_url() {
        return admin_url('admin-ajax.php?action=giftflowwp_stripe_webhook');
    }

    /**
     * Log successful payment
     *
     * @param string $transaction_id
     * @param int $donation_id
     */
    private function log_success($transaction_id, $donation_id) {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        $log_data = array(
            'action' => 'stripe_payment_success',
            'donation_id' => $donation_id,
            'transaction_id' => $transaction_id,
            'timestamp' => current_time('mysql')
        );

        error_log('[GiftFlow Stripe Success] ' . wp_json_encode($log_data));
    }

    /**
     * Log error
     *
     * @param string $type
     * @param string $message
     * @param int $donation_id
     * @param string $code
     */
    private function log_error($type, $message, $donation_id, $code = '') {
        $log_data = array(
            'action' => 'stripe_payment_error',
            'type' => $type,
            'donation_id' => $donation_id,
            'error_message' => $message,
            'error_code' => $code,
            'timestamp' => current_time('mysql')
        );

        error_log('[GiftFlow Stripe Error] ' . wp_json_encode($log_data));
    }
}

add_action('giftflowwp_register_gateways', function() {
    new \GiftFlowWp\Gateways\Stripe_Gateway();
});

/**
 * Helper function to get Stripe Gateway instance
 * 
 * @return Stripe_Gateway
 */
function giftflowwp_get_stripe_gateway() {
    return Gateway_Base::get_gateway('stripe');
}

/**
 * Process Stripe payment (backward compatibility)
 * 
 * @param array $data Payment data
 * @param int $donation_id Donation ID
 * @return mixed
 */
function giftflowwp_process_payment_stripe($data = array(), $donation_id = 0) {
    $stripe_gateway = giftflowwp_get_stripe_gateway();
    
    if (!$stripe_gateway) {
        return new \WP_Error('stripe_error', __('Stripe gateway not found', 'giftflowwp'));
    }
    
    return $stripe_gateway->process_payment($data, $donation_id);
}

