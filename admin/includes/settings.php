<?php
/**
 * GiftFlowWP Options
 *
 * @package GiftFlowWP
 * @subpackage Admin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// add custom nav item under plugin title in WordPress admin plugin list
add_filter('plugin_action_links_' . GIFTFLOWWP_PLUGIN_BASENAME, 'giftflowwp_add_settings_link');

function giftflowwp_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=giftflowwp-settings">' . __('Settings', 'giftflowwp') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// add submenu page to giftflowwp-dashboard menu
add_action('admin_menu', 'giftflowwp_add_settings_page', 30);

function giftflowwp_add_settings_page() {
    add_submenu_page(
        'giftflowwp-dashboard',
        'GiftFlowWP Settings',
        'Settings',
        'manage_options',
        'giftflowwp-settings',
        'giftflowwp_settings_page'
    );
}

function giftflowwp_settings_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get active tab
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';

    // build array of tabs 
    $tabs = apply_filters('giftflowwp_settings_tabs', [
        'general' => __('General', 'giftflowwp'),
        'payment' => __('Payment', 'giftflowwp'),
        'email' => __('Email', 'giftflowwp'),
        'design' => __('Design', 'giftflowwp'),
    ]);
    

    // Get all options
    // $general_options = get_option('giftflowwp_general_options');
    // $payment_options = get_option('giftflowwp_payment_options');
    // $email_options = get_option('giftflowwp_email_options');
    // $design_options = get_option('giftflowwp_design_options');
    ?>
    <div class="giftflow_page_giftflowwp-settings">
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <h2 class="nav-tab-wrapper">
                <?php foreach ($tabs as $tab_key => $tab_label) : ?>
                    <a href="?page=giftflowwp-settings&tab=<?php echo $tab_key; ?>" class="nav-tab <?php echo $active_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                        <?php echo $tab_label; ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <form method="post" action="options.php">
                <?php
                switch ($active_tab) {
                    case 'general':
                        settings_fields('giftflowwp_general_options');
                        do_settings_sections('giftflowwp-dashboard');
                        break;
                    case 'payment':
                        settings_fields('giftflowwp_payment_options');
                        do_settings_sections('giftflowwp-payment');
                        break;
                    case 'email':
                        settings_fields('giftflowwp_email_options');
                        do_settings_sections('giftflowwp-email');
                        break;
                    case 'design':
                        settings_fields('giftflowwp_design_options');
                        do_settings_sections('giftflowwp-design');
                        break;
                }

                do_action('giftflowwp_settings_tabs', $active_tab);

                submit_button(__('Save Settings', 'giftflowwp'));
                ?>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Initialize plugin settings
 */
function giftflowwp_initialize_settings() {
    // Get current options
    $general_options = get_option('giftflowwp_general_options');
    $payment_options = get_option('giftflowwp_payment_options');
    $email_options = get_option('giftflowwp_email_options');
    $design_options = get_option('giftflowwp_design_options');

    // Define settings structure
    $settings = [
        'general' => [
            'option_name' => 'giftflowwp_general_options',
            'page' => 'giftflowwp-dashboard',
            'section' => 'giftflowwp_general_settings',
            'section_title' => __('General Settings', 'giftflowwp'),
            'section_callback' => 'giftflowwp_general_settings_callback',
            'fields' => [
                'currency' => [
                    'id' => 'giftflowwp_currency',
                    'name' => 'giftflowwp_general_options[currency]',
                    'type' => 'select',
                    'label' => __('Default Currency', 'giftflowwp'),
                    'value' => giftflowwp_get_current_currency(),
                    'options' => array_combine(
                        array_column(giftflowwp_get_common_currency(), 'code'),
                        array_map(function($currency) {
                            // countries
                            return $currency['countries'][0] . ' (' . $currency['code'] . ' ' . $currency['symbol'] . ')';
                        }, giftflowwp_get_common_currency())
                    ),
                    'description' => __('Select the default currency for donations', 'giftflowwp'),
                ],
                // currency template
                'currency_template' => [
                    'id' => 'giftflowwp_currency_template',
                    'name' => 'giftflowwp_general_options[currency_template]',
                    'type' => 'text',
                    'label' => __('Currency Template', 'giftflowwp'),
                    'value' => giftflowwp_get_currency_template(),
                    'description' => __('Enter the template for the currency formatted amount (default: {{currency_symbol}} {{amount}})', 'giftflowwp'),
                ],
                // preset donation amounts
                'preset_donation_amounts' => [
                    'id' => 'giftflowwp_preset_donation_amounts',
                    'name' => 'giftflowwp_general_options[preset_donation_amounts]',
                    'type' => 'text',
                    'label' => __('Preset Donation Amounts', 'giftflowwp'),
                    'value' => giftflowwp_get_preset_donation_amounts(),
                    'description' => __('Enter the preset donation amounts for the campaign (default: 10, 25, 35)', 'giftflowwp'),
                ],
                'min_amount' => [
                    'id' => 'giftflowwp_min_amount',
                    'name' => 'giftflowwp_general_options[min_amount]',
                    'type' => 'currency',
                    'label' => __('Minimum Donation Amount', 'giftflowwp'),
                    'value' => isset($general_options['min_amount']) ? $general_options['min_amount'] : '1',
                    'min' => '0',
                    'step' => '0.01',
                    'description' => __('Set the minimum amount that can be donated', 'giftflowwp'),
                ],
            ],
        ],
        'payment_methods' => giftflowwp_payment_methods_options(),
        'email' => [
            'option_name' => 'giftflowwp_email_options',
            'page' => 'giftflowwp-email',
            'section' => 'giftflowwp_email_settings',
            'section_title' => __('Email Templates', 'giftflowwp'),
            'section_callback' => 'giftflowwp_email_settings_callback',
            'fields' => [
                'donation_receipt' => [
                    'id' => 'giftflowwp_donation_receipt',
                    'name' => 'giftflowwp_email_options[donation_receipt]',
                    'type' => 'textarea',
                    'label' => __('Donation Receipt Template', 'giftflowwp'),
                    'value' => isset($email_options['donation_receipt']) ? $email_options['donation_receipt'] : '',
                    'rows' => 5,
                    'description' => __('Use the following placeholders: {donor_name}, {amount}, {date}, {transaction_id}', 'giftflowwp'),
                ],
                'donation_notification' => [
                    'id' => 'giftflowwp_donation_notification',
                    'name' => 'giftflowwp_email_options[donation_notification]',
                    'type' => 'textarea',
                    'label' => __('Admin Notification Template', 'giftflowwp'),
                    'value' => isset($email_options['donation_notification']) ? $email_options['donation_notification'] : '',
                    'rows' => 5,
                    'description' => __('Use the following placeholders: {donor_name}, {amount}, {date}, {transaction_id}', 'giftflowwp'),
                ],
                'email_from_name' => [
                    'id' => 'giftflowwp_email_from_name',
                    'name' => 'giftflowwp_email_options[email_from_name]',
                    'type' => 'textfield',
                    'label' => __('Email From Name', 'giftflowwp'),
                    'value' => isset($email_options['email_from_name']) ? $email_options['email_from_name'] : get_bloginfo('name'),
                    'description' => __('The name that appears in the From field of emails', 'giftflowwp'),
                ],
                'email_from_address' => [
                    'id' => 'giftflowwp_email_from_address',
                    'name' => 'giftflowwp_email_options[email_from_address]',
                    'type' => 'textfield',
                    'label' => __('Email From Address', 'giftflowwp'),
                    'value' => isset($email_options['email_from_address']) ? $email_options['email_from_address'] : get_bloginfo('admin_email'),
                    'input_type' => 'email',
                    'description' => __('The email address that appears in the From field of emails', 'giftflowwp'),
                ],
            ],
        ],
        'design' => [
            'option_name' => 'giftflowwp_design_options',
            'page' => 'giftflowwp-design',
            'section' => 'giftflowwp_design_settings',
            'section_title' => __('Design Settings', 'giftflowwp'),
            'section_callback' => 'giftflowwp_design_settings_callback',
            'fields' => [
                // primary color
                'primary_color' => [
                    'id' => 'giftflowwp_primary_color',
                    'name' => 'giftflowwp_design_options[primary_color]',
                    'type' => 'color',
                    'label' => __('Primary Color', 'giftflowwp'),
                    'value' => isset($design_options['primary_color']) ? $design_options['primary_color'] : '#0073aa',
                    'description' => __('The primary color used throughout the plugin', 'giftflowwp'),
                ],
                // button style
                'button_style' => [
                    'id' => 'giftflowwp_button_style',
                    'name' => 'giftflowwp_design_options[button_style]',
                    'type' => 'select',
                    'label' => __('Button Style', 'giftflowwp'),
                    'value' => isset($design_options['button_style']) ? $design_options['button_style'] : 'default',
                    'options' => [
                        'default' => __('Default', 'giftflowwp'),
                        'rounded' => __('Rounded', 'giftflowwp'),
                        'outline' => __('Outline', 'giftflowwp'),
                    ],
                    'description' => __('Choose the style for buttons', 'giftflowwp'),
                ],
                'form_layout' => [
                    'id' => 'giftflowwp_form_layout',
                    'name' => 'giftflowwp_design_options[form_layout]',
                    'type' => 'select',
                    'label' => __('Form Layout', 'giftflowwp'),
                    'value' => isset($design_options['form_layout']) ? $design_options['form_layout'] : 'default',
                    'options' => [
                        'default' => __('Default', 'giftflowwp'),
                        'compact' => __('Compact', 'giftflowwp'),
                        'modern' => __('Modern', 'giftflowwp'),
                    ],
                    'description' => __('Choose the layout for donation forms', 'giftflowwp'),
                ],
                'custom_css' => [
                    'id' => 'giftflowwp_custom_css',
                    'name' => 'giftflowwp_design_options[custom_css]',
                    'type' => 'textarea',
                    'label' => __('Custom CSS', 'giftflowwp'),
                    'value' => isset($design_options['custom_css']) ? $design_options['custom_css'] : '',
                    'rows' => 10,
                    'description' => __('Add custom CSS to style the plugin', 'giftflowwp'),
                ],
            ],
        ],
    ];

    // Register settings and add sections/fields
    foreach ($settings as $section_key => $section) {
        // Register setting
        register_setting($section['option_name'], $section['option_name']);

        // Add section
        add_settings_section(
            $section['section'],
            $section['section_title'],
            $section['section_callback'],
            $section['page']
        );

        // Add fields
        foreach ($section['fields'] as $field_key => $field) {
            $field_instance = new GiftFlowWP_Field(
                $field['id'],
                $field['name'],
                $field['type'],
                array_merge(
                    // ['label' => $field['label']],
                    // isset($field['label']) ? ['label' => $field['label']] : [],
                    isset($field['value']) ? ['value' => $field['value']] : [],
                    isset($field['options']) ? ['options' => $field['options']] : [],
                    isset($field['description']) ? ['description' => $field['description']] : [],
                  isset($field['min']) ? ['min' => $field['min']] : [],
                    isset($field['step']) ? ['step' => $field['step']] : [],
                    isset($field['rows']) ? ['rows' => $field['rows']] : [],
                    isset($field['input_type']) ? ['type' => $field['input_type']] : [],
                    isset($field['accordion_settings']) ? ['accordion_settings' => $field['accordion_settings']] : [],
                    isset($field['content']) ? ['content' => $field['content']] : [],
                )
            );

            add_settings_field(
                $field['id'],
                $field['label'],
                function() use ($field_instance) {
                    echo $field_instance->render();
                },
                $section['page'],
                $section['section']
            );
        }
    }
}
add_action('admin_init', 'giftflowwp_initialize_settings');

// Section Callbacks
function giftflowwp_general_settings_callback() {
    echo '<p>' . __('Configure general settings for GiftFlowWP.', 'giftflowwp') . '</p>';
}

function giftflowwp_payment_settings_callback() {
    echo '<p>' . __('Configure payment gateway settings.', 'giftflowwp') . '</p>';
}

function giftflowwp_email_settings_callback() {
    echo '<p>' . __('Customize email templates for notifications.', 'giftflowwp') . '</p>';
}

function giftflowwp_design_settings_callback() {
    echo '<p>' . __('Customize the appearance of donation forms.', 'giftflowwp') . '</p>';
}

// paypal content
function giftflowwp_paypal_content() {
    echo '<p>' . __('111', 'giftflowwp') . '</p>';
}

// payment methods options register
function giftflowwp_payment_methods_options() {
    $payment_options = get_option('giftflowwp_payment_options');
    // var_dump($payment_options);

    /**
     * @hook giftflowwp_payment_methods_settings : giftflowwp_payment_methods_options_stripe - 8
     * @hook giftflowwp_payment_methods_settings : giftflowwp_payment_methods_options_paypal - 10
     */
    $payment_fields = apply_filters('giftflowwp_payment_methods_settings', []);

    return [
        'option_name' => 'giftflowwp_payment_options',
        'page' => 'giftflowwp-payment',
        'section' => 'giftflowwp_payment_settings',
        'section_title' => __('Payment Methods', 'giftflowwp'),
        'section_callback' => 'giftflowwp_payment_settings_callback',
        'fields' => $payment_fields,
    ];
}

// add_action('giftflowwp_payment_methods_settings', 'giftflowwp_payment_methods_options_paypal', 10);

// function giftflowwp_payment_methods_options_paypal($payment_fields) {
//     $payment_options = get_option('giftflowwp_payment_options');

//     $payment_fields['paypal'] = [
//         'id' => 'giftflowwp_paypal',
//         'name' => 'giftflowwp_payment_options[paypal]',
//         'type' => 'accordion',
//         'label' => __('PayPal', 'giftflowwp'),
//         'description' => __('Configure PayPal payment settings', 'giftflowwp'),
//         'accordion_settings' => [
//             'label' => __('PayPal Settings', 'giftflowwp'),
//             // 'icon' => 'dashicons-admin-tools',
//             // 'icon_position' => 'left',
//             'is_open' => true,
//             'fields' => [
//                 'paypal_enabled' => [
//                     'id' => 'giftflowwp_paypal_enabled',
//                     'type' => 'switch',
//                     'label' => __('Enable PayPal', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_enabled']) ? $payment_options['paypal']['paypal_enabled'] : false,
//                     'description' => __('Enable PayPal as a payment method', 'giftflowwp'),
//                 ],
//                 'paypal_mode' => [
//                     'id' => 'giftflowwp_paypal_mode',
//                     'type' => 'select',
//                     'label' => __('PayPal Mode', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_mode']) ? $payment_options['paypal']['paypal_mode'] : 'sandbox',
//                     'options' => [
//                         'sandbox' => __('Sandbox (Test Mode)', 'giftflowwp'),
//                         'live' => __('Live (Production Mode)', 'giftflowwp'),
//                     ],
//                     'description' => __('Select PayPal environment mode', 'giftflowwp'),
//                 ],
//                 'paypal_sandbox_email' => [
//                     'id' => 'giftflowwp_paypal_sandbox_email',
//                     'type' => 'textfield',
//                     'label' => __('PayPal Sandbox Email', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_sandbox_email']) ? $payment_options['paypal']['paypal_sandbox_email'] : '',
//                     'input_type' => 'email',
//                     'description' => __('Enter your PayPal sandbox email address', 'giftflowwp'),
//                 ],
//                 'paypal_sandbox_client_id' => [
//                     'id' => 'giftflowwp_paypal_sandbox_client_id',
//                     'type' => 'textfield',
//                     'label' => __('PayPal Sandbox Client ID', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_sandbox_client_id']) ? $payment_options['paypal']['paypal_sandbox_client_id'] : '',
//                     'description' => __('Enter your PayPal sandbox client ID', 'giftflowwp'),
//                 ],
//                 'paypal_sandbox_secret' => [
//                     'id' => 'giftflowwp_paypal_sandbox_secret',
//                     'type' => 'textfield',
//                     'label' => __('PayPal Sandbox Secret', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_sandbox_secret']) ? $payment_options['paypal']['paypal_sandbox_secret'] : '',
//                     'input_type' => 'password',
//                     'description' => __('Enter your PayPal sandbox secret key', 'giftflowwp'),
//                 ],
//                 'paypal_live_email' => [
//                     'id' => 'giftflowwp_paypal_live_email',
//                     'type' => 'textfield',
//                     'label' => __('PayPal Live Email', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_live_email']) ? $payment_options['paypal']['paypal_live_email'] : '',
//                     'input_type' => 'email',
//                     'description' => __('Enter your PayPal live email address', 'giftflowwp'),
//                 ],
//                 'paypal_live_client_id' => [
//                     'id' => 'giftflowwp_paypal_live_client_id',
//                     'type' => 'textfield',
//                     'label' => __('PayPal Live Client ID', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_live_client_id']) ? $payment_options['paypal']['paypal_live_client_id'] : '',
//                     'description' => __('Enter your PayPal live client ID', 'giftflowwp'),
//                 ],
                
//                 'paypal_live_secret' => [
//                     'id' => 'giftflowwp_paypal_live_secret',
//                     'type' => 'textfield',
//                     'label' => __('PayPal Live Secret', 'giftflowwp'),
//                     'value' => isset($payment_options['paypal']['paypal_live_secret']) ? $payment_options['paypal']['paypal_live_secret'] : '',
//                     'input_type' => 'password',
//                     'description' => __('Enter your PayPal live secret key', 'giftflowwp'),
//                 ],
//             ]
//         ]
//     ];

//     return $payment_fields;
// }

// stripe fields
// function giftflowwp_payment_methods_options_stripe($payment_fields) {
//     $payment_options = get_option('giftflowwp_payment_options');

//     $payment_fields['stripe'] =  [
//         'id' => 'giftflowwp_stripe',
//         'name' => 'giftflowwp_payment_options[stripe]',
//         'type' => 'accordion',
//         'label' => __('Stripe', 'giftflowwp'),
//         'description' => __('Configure Stripe payment settings', 'giftflowwp'),
//         'accordion_settings' => [
//             'label' => __('Stripe Settings', 'giftflowwp'),
//             'is_open' => true,
//             'fields' => [
//                 'stripe_enabled' => [
//                     'id' => 'giftflowwp_stripe_enabled',
//                     'type' => 'switch',
//                     'label' => __('Enable Stripe', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe']['stripe_enabled']) ? $payment_options['stripe']['stripe_enabled'] : false,
//                     'description' => __('Enable Stripe as a payment method', 'giftflowwp'),
//                 ],
//                 'stripe_mode' => [
//                     'id' => 'giftflowwp_stripe_mode',
//                     'type' => 'select',
//                     'label' => __('Stripe Mode', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe_mode']) ? $payment_options['stripe_mode'] : 'sandbox',
//                     'options' => [
//                         'sandbox' => __('Sandbox (Test Mode)', 'giftflowwp'),
//                         'live' => __('Live (Production Mode)', 'giftflowwp'),
//                     ],
//                     'description' => __('Select Stripe environment mode', 'giftflowwp'),
//                 ],
//                 'stripe_sandbox_publishable_key' => [
//                     'id' => 'giftflowwp_stripe_sandbox_publishable_key',
//                     'type' => 'textfield',
//                     'label' => __('Stripe Sandbox Publishable Key', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe']['stripe_sandbox_publishable_key']) ? $payment_options['stripe']['stripe_sandbox_publishable_key'] : '',
//                     'description' => __('Enter your Stripe sandbox publishable key', 'giftflowwp'),
//                 ],
//                 'stripe_sandbox_secret_key' => [
//                     'id' => 'giftflowwp_stripe_sandbox_secret_key',
//                     'type' => 'textfield',
//                     'label' => __('Stripe Sandbox Secret Key', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe']['stripe_sandbox_secret_key']) ? $payment_options['stripe']['stripe_sandbox_secret_key'] : '',
//                     'input_type' => 'password',
//                     'description' => __('Enter your Stripe sandbox secret key', 'giftflowwp'),
//                 ],
//                 'stripe_live_publishable_key' => [
//                     'id' => 'giftflowwp_stripe_live_publishable_key',
//                     'type' => 'textfield',
//                     'label' => __('Stripe Live Publishable Key', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe']['stripe_live_publishable_key']) ? $payment_options['stripe']['stripe_live_publishable_key'] : '',
//                     'description' => __('Enter your Stripe live publishable key', 'giftflowwp'),
//                 ],
//                 'stripe_live_secret_key' => [
//                     'id' => 'giftflowwp_stripe_live_secret_key',
//                     'type' => 'textfield',
//                     'label' => __('Stripe Live Secret Key', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe']['stripe_live_secret_key']) ? $payment_options['stripe']['stripe_live_secret_key'] : '',
//                     'input_type' => 'password',
//                     'description' => __('Enter your Stripe live secret key', 'giftflowwp'),
//                 ],
//                 // stripe_capture
//                 'stripe_capture' => [
//                     'id' => 'giftflowwp_stripe_capture',
//                     'type' => 'select',
//                     'label' => __('Capture Payment', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe']['stripe_capture']) ? $payment_options['stripe']['stripe_capture'] : 'yes',
//                     'options' => [
//                         'yes' => __('Capture immediately', 'giftflowwp'),
//                         'no' => __('Authorize only (capture later)', 'giftflowwp')
//                     ],
//                     'description' => __('Capture payment immediately or authorize for later capture', 'giftflowwp'),
//                 ],
//                 // stripe_webhook_enabled
//                 'stripe_webhook_enabled' => [
//                     'id' => 'giftflowwp_stripe_webhook_enabled',
//                     'type' => 'switch',
//                     'label' => __('Enable Webhook Notifications', 'giftflowwp'),
//                     'value' => isset($payment_options['stripe']['stripe_webhook_enabled']) ? $payment_options['stripe']['stripe_webhook_enabled'] : false,
//                     'description' => sprintf(
//                         __('Enable webhooks for payment status updates. Webhook URL: %s', 'giftflowwp'),
//                         '<code>' . admin_url('admin-ajax.php?action=giftflowwp_stripe_webhook') . '</code>'
//                     ),
//                 ],
//             ]
//         ]
//     ];

//     return $payment_fields;
// }

// add_action('giftflowwp_payment_methods_settings', 'giftflowwp_payment_methods_options_stripe', 8);

