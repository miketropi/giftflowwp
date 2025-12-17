# Integrating New Payment Methods with GiftFlow

This guide explains how to create custom payment gateway integrations for the GiftFlow donation plugin by extending the base gateway architecture.

## Overview

GiftFlow uses an extensible payment gateway system based on the `Gateway_Base` abstract class. All payment gateways extend this base class and implement the required abstract methods while optionally overriding default behaviors.

## Gateway Base Architecture

### Required Abstract Methods

Every payment gateway must implement these abstract methods:

1. **`init_gateway()`** - Initialize gateway properties (ID, title, description, etc.)
2. **`process_payment($data, $donation_id)`** - Handle the actual payment processing
3. **`register_settings_fields($payment_fields)`** - Define admin configuration fields

### Gateway Properties

Set these properties in your `init_gateway()` method:

```php
protected function init_gateway() {
    $this->id = 'your_gateway_id';              // Unique identifier
    $this->title = 'Your Gateway Name';          // Display name
    $this->description = 'Gateway description';   // User-facing description
    $this->icon = '<svg>...</svg>';             // Payment method icon (SVG)
    $this->order = 10;                          // Display order (lower = higher priority)
    $this->supports = array(                    // Supported features
        'webhooks',
        '3d_secure',
        'refunds'
    );
}
```

## Creating a New Payment Gateway

### Step 1: Create Gateway Class

Create a new PHP file in `includes/gateways/class-your-gateway.php`:

```php
<?php
namespace GiftFlow\Gateways;

class Your_Gateway extends Gateway_Base {
    
    /**
     * Initialize gateway properties
     */
    protected function init_gateway() {
        $this->id = 'your_gateway';
        $this->title = __('Your Payment Gateway', 'giftflow');
        $this->description = __('Pay securely using Your Gateway', 'giftflow');
        $this->icon = '<svg><!-- Your icon SVG --></svg>';
        $this->order = 20;
        $this->supports = array('webhooks');
    }

    /**
     * Process payment
     */
    public function process_payment($data, $donation_id = 0) {
        // Your payment processing logic
        // Return true for success, WP_Error for failure
    }

    /**
     * Register admin settings fields
     */
    public function register_settings_fields($payment_fields = array()) {
        // Define your gateway's configuration fields
        return $payment_fields;
    }
}
```

### Step 2: Register the Gateway

Add registration hook at the end of your gateway file:

```php
add_action('giftflow_register_gateways', function() {
    new \GiftFlow\Gateways\Your_Gateway();
});
```

## Detailed Implementation Guide

### Payment Processing

The `process_payment()` method receives:
- `$data` - Array containing payment and donor information
- `$donation_id` - WordPress post ID of the donation record

**Expected Data Structure:**
```php
$data = array(
    'donation_amount' => '100.00',
    'donor_name' => 'John Doe',
    'donor_email' => 'john@example.com',
    'campaign_id' => 123,
    // Gateway-specific fields (e.g., payment tokens, card info)
);
```

**Return Values:**
- Return `true` for successful payments
- Return `WP_Error` object for failures
- Return array with redirect info for external redirects

### Admin Settings Configuration

Define your gateway's configuration fields in `register_settings_fields()`:

```php
public function register_settings_fields($payment_fields = array()) {
    $payment_options = get_option('giftflow_payment_options');
    
    $payment_fields['your_gateway'] = [
        'id' => 'giftflow_your_gateway',
        'name' => 'giftflow_payment_options[your_gateway]',
        'type' => 'accordion',
        'label' => __('Your Gateway Settings', 'giftflow'),
        'accordion_settings' => [
            'label' => __('Configuration', 'giftflow'),
            'is_open' => true,
            'fields' => [
                'enabled' => [
                    'type' => 'switch',
                    'label' => __('Enable Your Gateway', 'giftflow'),
                    'value' => isset($payment_options['your_gateway']['enabled']) ? $payment_options['your_gateway']['enabled'] : false,
                ],
                'api_key' => [
                    'type' => 'textfield',
                    'label' => __('API Key', 'giftflow'),
                    'input_type' => 'password',
                    'value' => isset($payment_options['your_gateway']['api_key']) ? $payment_options['your_gateway']['api_key'] : '',
                ],
                'mode' => [
                    'type' => 'select',
                    'label' => __('Mode', 'giftflow'),
                    'options' => [
                        'sandbox' => __('Sandbox', 'giftflow'),
                        'live' => __('Live', 'giftflow')
                    ],
                    'value' => isset($payment_options['your_gateway']['mode']) ? $payment_options['your_gateway']['mode'] : 'sandbox',
                ]
            ]
        ]
    ];

    return $payment_fields;
}
```

### Frontend Template

Create a payment form template by implementing `template_html()`:

```php
public function template_html() {
    ob_start();
    ?>
    <label class="donation-form__payment-method">
        <input type="radio" name="payment_method" value="<?php echo esc_attr($this->id); ?>" required>
        <span class="donation-form__payment-method-content">
            <?php echo $this->icon; ?>
            <span class="donation-form__payment-method-title"><?php echo esc_html($this->title); ?></span>
        </span>
    </label>
    <div class="donation-form__payment-method-description">
        <!-- Your custom payment form fields -->
        <div class="donation-form__field">
            <label><?php _e('Account Number', 'giftflow'); ?></label>
            <input type="text" name="account_number" required>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
```

## Advanced Features

### Adding JavaScript Assets

Use the `add_script()` method in your `ready()` hook:

```php
protected function ready() {
    $this->add_script('your-gateway-js', array(
        'src' => GIFTFLOW_PLUGIN_URL . 'assets/js/your-gateway.js',
        'deps' => array('jquery', 'giftflow-donation-forms'),
        'version' => GIFTFLOW_VERSION,
        'frontend' => true,
        'localize' => array(
            'name' => 'yourGatewayData',
            'data' => array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('your_gateway_nonce')
            )
        )
    ));
}
```

### AJAX Payment Processing

Add AJAX handlers in your `init_additional_hooks()` method:

```php
protected function init_additional_hooks() {
    add_action('wp_ajax_process_your_gateway_payment', array($this, 'ajax_process_payment'));
    add_action('wp_ajax_nopriv_process_your_gateway_payment', array($this, 'ajax_process_payment'));
}

public function ajax_process_payment() {
    check_ajax_referer('your_gateway_nonce', 'nonce');
    
    $data = $_POST;
    $donation_id = intval($data['donation_id']);
    
    $result = $this->process_payment($data, $donation_id);
    
    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    } else {
        wp_send_json_success($result);
    }
}
```

### Webhook Support

Implement webhook handling for real-time payment updates:

```php
protected function init_additional_hooks() {
    add_action('wp_ajax_your_gateway_webhook', array($this, 'handle_webhook'));
    add_action('wp_ajax_nopriv_your_gateway_webhook', array($this, 'handle_webhook'));
}

public function handle_webhook() {
    $payload = file_get_contents('php://input');
    $data = json_decode($payload, true);
    
    // Verify webhook signature
    // Process webhook data
    // Update donation status
    
    status_header(200);
    echo 'OK';
    exit;
}
```

## Data Storage and Metadata

Store transaction data and payment metadata:

```php
private function handle_successful_payment($response, $donation_id) {
    $transaction_id = $response->getTransactionReference();
    
    // Update donation meta
    update_post_meta($donation_id, '_transaction_id', $transaction_id);
    update_post_meta($donation_id, '_payment_method', $this->id);
    update_post_meta($donation_id, '_status', 'completed');
    update_post_meta($donation_id, '_transaction_raw_data', wp_json_encode($response->getData()));
    
    // Trigger action hook
    do_action('giftflow_' . $this->id . '_payment_completed', $donation_id, $transaction_id, $response->getData());
    
    return true;
}
```

## Error Handling and Logging

Implement proper error handling:

```php
private function log_error($type, $message, $donation_id, $code = '') {
    $log_data = array(
        'action' => $this->id . '_payment_error',
        'type' => $type,
        'donation_id' => $donation_id,
        'error_message' => $message,
        'error_code' => $code,
        'timestamp' => current_time('mysql')
    );

    error_log('[GiftFlow ' . ucfirst($this->id) . ' Error] ' . wp_json_encode($log_data));
}
```

## Testing Your Gateway

1. Enable WordPress debug mode: `define('WP_DEBUG', true);`
2. Test with sandbox/test API credentials
3. Verify webhook endpoints are accessible
4. Test both successful and failed payment scenarios
5. Confirm donation records are created with proper metadata

## Example: Stripe Gateway Reference

The included Stripe gateway (`class-stripe.php`) demonstrates:
- Omnipay library integration
- 3D Secure authentication handling
- Payment Intent processing
- Webhook implementation
- Comprehensive error handling
- Admin settings configuration

Study this implementation as a reference for advanced payment gateway features.

## Support and Compatibility

### Supported Features
- `webhooks` - Real-time payment status updates
- `3d_secure` - Strong Customer Authentication
- `refunds` - Refund processing capabilities
- `payment_intents` - Modern payment processing
- `customer_creation` - Customer profile management

### WordPress Hooks
Your gateway can use these action hooks:
- `giftflow_{gateway_id}_payment_completed`
- `giftflow_{gateway_id}_payment_failed`
- `giftflow_{gateway_id}_webhook_received`

## Security Considerations

1. Always validate and sanitize input data
2. Use WordPress nonces for CSRF protection
3. Store sensitive credentials securely (use `input_type: 'password'`)
4. Verify webhook signatures when available
5. Log security events for monitoring
6. Follow PCI DSS guidelines for card data handling

## File Structure

```
includes/gateways/
    class-gateway-base.php       # Base abstract class
    class-stripe.php             # Stripe implementation
    class-paypal.php             # PayPal implementation
    class-your-gateway.php       # Your custom gateway

assets/js/
    your-gateway.js              # Frontend JavaScript

admin/css/
    your-gateway-admin.css       # Admin styling (optional)
```

This architecture ensures your custom payment gateway integrates seamlessly with GiftFlow's donation processing system while maintaining security and WordPress coding standards.