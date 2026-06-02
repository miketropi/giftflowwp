---
name: gf-gateway
description: Use ONLY when creating or modifying a payment gateway in the GiftFlow WordPress plugin. Follows the Gateway_Base abstract class with self-registering registry pattern. Trigger keywords: "add payment gateway", "create gateway", "new gateway", "payment method", "Stripe", "PayPal", "bank transfer".
---

# GiftFlow Payment Gateway

## Pattern Overview

All payment gateways extend `GiftFlow\Gateways\Gateway_Base`. Instances auto-register into `Gateway_Base::$gateway_registry` via the constructor. Three abstract methods must be implemented.

## Steps

1. **Create the class** in `includes/gateways/class-{name}.php`:

```php
<?php
namespace GiftFlow\Gateways;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class {Name}_Gateway extends Gateway_Base {

    protected function init_gateway() {
        $this->id          = '{gateway-id}';
        $this->title       = esc_html__( '{Display Title}', 'giftflow' );
        $this->description = esc_html__( '{Description}', 'giftflow' );
        $this->icon        = '{svg-or-url}';
        $this->order       = 20;
        $this->supports    = array( 'one-time', 'recurring' );
    }

    protected function register_settings_fields() {
        return array(
            // Return settings field configurations for admin.
            // These appear in Settings > Payment Methods.
            $this->id . '_enabled' => array(
                'label'   => esc_html__( 'Enable {Name}', 'giftflow' ),
                'type'    => 'switch',
            ),
            $this->id . '_api_key' => array(
                'label'   => esc_html__( 'API Key', 'giftflow' ),
                'type'    => 'textfield',
            ),
        );
    }

    public function template_html() {
        // Return HTML for the frontend payment form.
        ob_start();
        giftflow_load_template( 'payment-gateway/{name}-template.php', array(
            'gateway' => $this,
        ) );
        return ob_get_clean();
    }

    public function process_payment( $data, $donation_id = 0 ) {
        // Handle payment processing.
        // Return array on success, throw or return WP_Error on failure.
        return array(
            'status'       => 'completed',
            'transaction_id' => 'txn_xxx',
            'message'      => esc_html__( 'Payment successful', 'giftflow' ),
        );
    }
}

// Instantiate — auto-registers into gateway registry.
new {Name}_Gateway();
```

2. **Require the file** in `giftflow.php` → `giftflow_load_files()`:

```php
require_once GIFTFLOW_PLUGIN_DIR . 'includes/gateways/class-{name}.php';
```

3. **Settings are auto-loaded** from `giftflow_payment_options` option. Enable/disable is automatic via `{id}_enabled` field.

4. **Create template** in `templates/payment-gateway/{name}-template.php` for the frontend payment form.

5. **For JS-dependent gateways** (like Stripe Elements, PayPal SDK):
   - Use `$this->add_script()` and `$this->add_style()` in `ready()` or `init_gateway()`
   - Gateways auto-enqueue via `enqueue_frontend_assets()` / `enqueue_admin_assets()`

## Key Rules
- Gateway ID: lowercase alphanumeric with hyphens, e.g., `my-gateway`
- Settings option group: `giftflow_payment_options`
- Enabled field name: `{gateway_id}_enabled`
- Always implement all four abstract methods
- Use `esc_html__()` / `__()` with `'giftflow'` domain
- For API keys: use `$this->get_setting('{id}_api_key')` to read stored values
- Use `GiftFlow\Core\Donations` class for updating donation status
- Use `GiftFlow\Core\Donation_Event_History::add()` for audit trail

## Existing Gateways for Reference
- `includes/gateways/class-stripe.php` — `Stripe_Gateway` → id `stripe` (1677 lines, most complex)
- `includes/gateways/class-paypal.php` — `PayPal_Gateway` → id `paypal`
- `includes/gateways/class-direct-bank-transfer.php` — `Direct_Bank_Transfer_Gateway` → id `direct-bank-transfer`
