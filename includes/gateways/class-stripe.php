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

        // SVG icon
        $this->icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>';

        $this->order = 10;
        $this->supports = array(
            // 'refunds',
            'webhooks',
            '3d_secure',
            'payment_intents',
            // 'customer_creation'
        );
    }

    protected function ready() {
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

        // Custom Stripe donation script
        $this->add_script('giftflowwp-stripe-donation', array(
            'src' => GIFTFLOWWP_PLUGIN_URL . 'assets/js/stripe-donation.bundle.js',
            'deps' => array('jquery', 'giftflowwp-donation-forms'),
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
        /*
        $this->add_style('giftflowwp-stripe-donation', array(
            'src' => GIFTFLOWWP_PLUGIN_URL . 'assets/css/stripe-donation.bundle.css',
            'deps' => array('giftflowwp-donation-forms'),
            'version' => GIFTFLOWWP_VERSION,
            'frontend' => true,
            'admin' => false
        ));
        */
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
    public function register_settings_fields($payment_fields = array()) {
        $payment_options = get_option('giftflowwp_payment_options');
        $payment_fields['stripe'] =  [
            'id' => 'giftflowwp_stripe',
            'name' => 'giftflowwp_payment_options[stripe]',
            'type' => 'accordion',
            'label' => __('Stripe (Credit Card)', 'giftflowwp'),
            'description' => __('Configure Stripe payment settings', 'giftflowwp'),
            'accordion_settings' => [
                'label' => __('Stripe Settings', 'giftflowwp'),
                'is_open' => true,
                'fields' => [
                    'stripe_enabled' => [
                        'id' => 'giftflowwp_stripe_enabled',
                        'type' => 'switch',
                        'label' => __('Enable Stripe', 'giftflowwp'),
                        'value' => isset($payment_options['stripe']['stripe_enabled']) ? $payment_options['stripe']['stripe_enabled'] : false,
                        'description' => __('Enable Stripe as a payment method', 'giftflowwp'),
                    ],
                    'stripe_mode' => [
                        'id' => 'giftflowwp_stripe_mode',
                        'type' => 'select',
                        'label' => __('Stripe Mode', 'giftflowwp'),
                        'value' => isset($payment_options['stripe_mode']) ? $payment_options['stripe_mode'] : 'sandbox',
                        'options' => [
                            'sandbox' => __('Sandbox (Test Mode)', 'giftflowwp'),
                            'live' => __('Live (Production Mode)', 'giftflowwp'),
                        ],
                        'description' => __('Select Stripe environment mode', 'giftflowwp'),
                    ],
                    'stripe_sandbox_publishable_key' => [
                        'id' => 'giftflowwp_stripe_sandbox_publishable_key',
                        'type' => 'textfield',
                        'label' => __('Stripe Sandbox Publishable Key', 'giftflowwp'),
                        'value' => isset($payment_options['stripe']['stripe_sandbox_publishable_key']) ? $payment_options['stripe']['stripe_sandbox_publishable_key'] : '',
                        'description' => __('Enter your Stripe sandbox publishable key', 'giftflowwp'),
                    ],
                    'stripe_sandbox_secret_key' => [
                        'id' => 'giftflowwp_stripe_sandbox_secret_key',
                        'type' => 'textfield',
                        'label' => __('Stripe Sandbox Secret Key', 'giftflowwp'),
                        'value' => isset($payment_options['stripe']['stripe_sandbox_secret_key']) ? $payment_options['stripe']['stripe_sandbox_secret_key'] : '',
                        'input_type' => 'password',
                        'description' => __('Enter your Stripe sandbox secret key', 'giftflowwp'),
                    ],
                    'stripe_live_publishable_key' => [
                        'id' => 'giftflowwp_stripe_live_publishable_key',
                        'type' => 'textfield',
                        'label' => __('Stripe Live Publishable Key', 'giftflowwp'),
                        'value' => isset($payment_options['stripe']['stripe_live_publishable_key']) ? $payment_options['stripe']['stripe_live_publishable_key'] : '',
                        'description' => __('Enter your Stripe live publishable key', 'giftflowwp'),
                    ],
                    'stripe_live_secret_key' => [
                        'id' => 'giftflowwp_stripe_live_secret_key',
                        'type' => 'textfield',
                        'label' => __('Stripe Live Secret Key', 'giftflowwp'),
                        'value' => isset($payment_options['stripe']['stripe_live_secret_key']) ? $payment_options['stripe']['stripe_live_secret_key'] : '',
                        'input_type' => 'password',
                        'description' => __('Enter your Stripe live secret key', 'giftflowwp'),
                    ],
                    // stripe_capture
                    // 'stripe_capture' => [
                    //     'id' => 'giftflowwp_stripe_capture',
                    //     'type' => 'select',
                    //     'label' => __('Capture Payment', 'giftflowwp'),
                    //     'value' => isset($payment_options['stripe']['stripe_capture']) ? $payment_options['stripe']['stripe_capture'] : 'yes',
                    //     'options' => [
                    //         'yes' => __('Capture immediately', 'giftflowwp'),
                    //         'no' => __('Authorize only (capture later)', 'giftflowwp')
                    //     ],
                    //     'description' => __('Capture payment immediately or authorize for later capture', 'giftflowwp'),
                    // ],
                    // stripe_webhook_enabled
                    'stripe_webhook_enabled' => [
                        'id' => 'giftflowwp_stripe_webhook_enabled',
                        'type' => 'switch',
                        'label' => __('Enable Webhook', 'giftflowwp'),
                        'value' => isset($payment_options['stripe']['stripe_webhook_enabled']) ? $payment_options['stripe']['stripe_webhook_enabled'] : false,
                        /* translators: This is the label for enabling the Stripe webhook option in the payment gateway settings */
                        'description' => sprintf(__('Enable webhooks for payment status updates. Webhook URL: %s', 'giftflowwp'), '<code>' . admin_url('admin-ajax.php?action=giftflowwp_stripe_webhook') . '</code><br>' . __('Recommended Stripe events to send: <strong>payment_intent.succeeded</strong>, <strong>payment_intent.payment_failed</strong>, <strong>charge.refunded</strong>.', 'giftflowwp')
                        ),
                    ],
                ]
            ]
        ];

        return $payment_fields;
    }

    public function template_html() {
        ob_start();
        $icons = array(
            'error' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
            'checked' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check-icon lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>',
        );

        ?>
        <label class="donation-form__payment-method">
            <input type="radio" checked name="payment_method" value="<?php echo esc_attr($this->id); ?>" required>
            <span class="donation-form__payment-method-content">
                <?php echo wp_kses($this->icon, giftflowwp_allowed_svg_tags()); ?>
                <span class="donation-form__payment-method-title"><?php echo esc_html($this->title); ?></span>
            </span>
        </label>
        <div 
            class="donation-form__payment-method-description donation-form__payment-method-description--stripe donation-form__fields" 
            >
            <div class="donation-form__payment-notification">
                <?php echo wp_kses($icons['checked'], giftflowwp_allowed_svg_tags()); ?>
                <p><?php esc_html_e('We use Stripe to process payments. Your payment information is encrypted and never stored on our servers.', 'giftflowwp'); ?></p>
            </div>

            <?php // name on card field ?>
            <div class="donation-form__field">
                <label for="card_name" class="donation-form__field-label"><?php esc_html_e('Name on card', 'giftflowwp'); ?></label>
                <input type="text" id="card_name" name="card_name" class="donation-form__field-input" required data-validate="required">

                <div class="donation-form__field-error custom-error-message">
                <?php echo wp_kses($icons['error'], giftflowwp_allowed_svg_tags()); ?>
                <span class="custom-error-message-text">
                    <?php esc_html_e('Name on card is required', 'giftflowwp'); ?>
                </span>
                </div>
            </div>
            
            <?php // card element ?>
            <div 
                class="donation-form__field" 
                data-custom-validate="true" 
                data-custom-validate-status="false" >
                <label for="card_number" class="donation-form__field-label"><?php esc_html_e('Card number', 'giftflowwp'); ?></label>
                <div id="STRIPE-CARD-ELEMENT"></div> <?php // Render card via stripe.js ?>

                <div class="donation-form__field-error custom-error-message">
                <?php echo wp_kses($icons['error'], giftflowwp_allowed_svg_tags()); ?>
                <span class="custom-error-message-text">
                    <?php esc_html_e('Card information is incomplete', 'giftflowwp'); ?>
                </span>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
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
            
            // if ($this->get_setting('stripe_capture', 'yes') === 'yes') {
            //     $response = $this->gateway->purchase($payment_data)->send();
            // } else {
            //     $response = $this->gateway->authorize($payment_data)->send();
            // }

            $response = $this->gateway->purchase($payment_data)->send();

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
            /* translators: 1: donor name, 2: campaign id or name */
            'description' => sprintf(__('Donation from %1$s for campaign %2$s', 'giftflowwp'), sanitize_text_field($data['donor_name']), $data['campaign_id']),
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
            'statement_descriptor_suffix' => $statement_descriptor
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
        update_post_meta($donation_id, '_status', 'completed');

        $this->log_success($payment_intent_id, $donation_id);

        do_action('giftflowwp_stripe_payment_completed', $donation_id, $payment_intent_id, $all_data);

        // return true when payment is successful
        return true;

        // return array(
        //     'success' => true,
        //     'transaction_id' => $payment_intent_id,
        //     'message' => __('Payment processed successfully', 'giftflowwp')
        // );
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
                case 'charge.refunded':
                    // Handle charge refunded if needed
                    $this->handle_payment_charge_refunded($event['data']['object']);
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
                    wp_safe_redirect(esc_url(add_query_arg('payment_status', 'success', home_url())));
                } else {
                    $this->handle_failed_payment($response, $donation_id);
                    wp_safe_redirect(esc_url(add_query_arg('payment_status', 'failed', home_url())));
                }
            } catch (\Exception $e) {
                $this->log_error('return_url_error', $e->getMessage(), $donation_id);
                wp_safe_redirect(esc_url(add_query_arg('payment_status', 'error', home_url())));
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
            update_post_meta($donation_id, '_status', 'completed');
            // update_post_meta($donation_id, '_transaction_id', $payment_intent['id']);
            
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
                
            update_post_meta($donation_id, '_status', 'failed');
            update_post_meta($donation_id, '_payment_error', $error_message);
            
            do_action('giftflowwp_stripe_webhook_payment_failed', $donation_id, $payment_intent);
        }
    }

    private function handle_payment_charge_refunded($charge) {
        $donation_id = isset($charge['metadata']['donation_id']) 
            ? intval($charge['metadata']['donation_id']) 
            : 0;

        if ($donation_id) {
            update_post_meta($donation_id, '_status', 'refunded');
            // update_post_meta($donation_id, '_refund_transaction_id', $charge['id']);
            
            do_action('giftflowwp_stripe_webhook_charge_refunded', $donation_id, $charge);
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

