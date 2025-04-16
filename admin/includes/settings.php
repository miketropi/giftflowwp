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

    // Get all options
    $general_options = get_option('giftflowwp_general_options');
    $payment_options = get_option('giftflowwp_payment_options');
    $email_options = get_option('giftflowwp_email_options');
    $design_options = get_option('giftflowwp_design_options');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <h2 class="nav-tab-wrapper">
            <a href="?page=giftflowwp-settings&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                <?php _e('General', 'giftflowwp'); ?>
            </a>
            <a href="?page=giftflowwp-settings&tab=payment" class="nav-tab <?php echo $active_tab === 'payment' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Payment', 'giftflowwp'); ?>
            </a>
            <a href="?page=giftflowwp-settings&tab=email" class="nav-tab <?php echo $active_tab === 'email' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Email', 'giftflowwp'); ?>
            </a>
            <a href="?page=giftflowwp-settings&tab=design" class="nav-tab <?php echo $active_tab === 'design' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Design', 'giftflowwp'); ?>
            </a>
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
            submit_button(__('Save Settings', 'giftflowwp'));
            ?>
        </form>
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
        'payment_methods' => [
            'option_name' => 'giftflowwp_payment_options',
            'page' => 'giftflowwp-payment',
            'section' => 'giftflowwp_payment_settings',
            'section_title' => __('Payment Methods', 'giftflowwp'),
            'section_callback' => 'giftflowwp_payment_settings_callback',
            'fields' => [
                'paypal_enabled' => [
                    'id' => 'giftflowwp_paypal_enabled',
                    'name' => 'giftflowwp_payment_options[paypal_enabled]',
                    'type' => 'switch',
                    'label' => __('Enable PayPal', 'giftflowwp'),
                    'value' => isset($payment_options['paypal_enabled']) ? $payment_options['paypal_enabled'] : false,
                    'description' => __('Enable PayPal as a payment method', 'giftflowwp'),
                ],
                'stripe_enabled' => [
                    'id' => 'giftflowwp_stripe_enabled',
                    'name' => 'giftflowwp_payment_options[stripe_enabled]',
                    'type' => 'switch',
                    'label' => __('Enable Stripe', 'giftflowwp'),
                    'value' => isset($payment_options['stripe_enabled']) ? $payment_options['stripe_enabled'] : false,
                    'description' => __('Enable Stripe as a payment method', 'giftflowwp'),
                ],
                'paypal_email' => [
                    'id' => 'giftflowwp_paypal_email',
                    'name' => 'giftflowwp_payment_options[paypal_email]',
                    'type' => 'textfield',
                    'label' => __('PayPal Email', 'giftflowwp'),
                    'value' => isset($payment_options['paypal_email']) ? $payment_options['paypal_email'] : '',
                    'input_type' => 'email',
                    'description' => __('Enter your PayPal email address', 'giftflowwp'),
                ],
                'stripe_publishable_key' => [
                    'id' => 'giftflowwp_stripe_publishable_key',
                    'name' => 'giftflowwp_payment_options[stripe_publishable_key]',
                    'type' => 'textfield',
                    'label' => __('Stripe Publishable Key', 'giftflowwp'),
                    'value' => isset($payment_options['stripe_publishable_key']) ? $payment_options['stripe_publishable_key'] : '',
                    'description' => __('Enter your Stripe publishable key', 'giftflowwp'),
                ],
                'stripe_secret_key' => [
                    'id' => 'giftflowwp_stripe_secret_key',
                    'name' => 'giftflowwp_payment_options[stripe_secret_key]',
                    'type' => 'textfield',
                    'label' => __('Stripe Secret Key', 'giftflowwp'),
                    'value' => isset($payment_options['stripe_secret_key']) ? $payment_options['stripe_secret_key'] : '',
                    'input_type' => 'password',
                    'description' => __('Enter your Stripe secret key', 'giftflowwp'),
                ],
            ],
        ],
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
                    isset($field['value']) ? ['value' => $field['value']] : [],
                    isset($field['options']) ? ['options' => $field['options']] : [],
                    isset($field['description']) ? ['description' => $field['description']] : [],
                    isset($field['min']) ? ['min' => $field['min']] : [],
                    isset($field['step']) ? ['step' => $field['step']] : [],
                    isset($field['rows']) ? ['rows' => $field['rows']] : [],
                    isset($field['input_type']) ? ['type' => $field['input_type']] : [],
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
