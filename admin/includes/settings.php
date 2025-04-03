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
    // Register settings for each tab
    register_setting('giftflowwp_general_options', 'giftflowwp_general_options');
    register_setting('giftflowwp_payment_options', 'giftflowwp_payment_options');
    register_setting('giftflowwp_email_options', 'giftflowwp_email_options');
    register_setting('giftflowwp_design_options', 'giftflowwp_design_options');

    // General Settings Section
    add_settings_section(
        'giftflowwp_general_settings',
        __('General Settings', 'giftflowwp'),
        'giftflowwp_general_settings_callback',
        'giftflowwp-dashboard'
    );

    // Payment Methods Section
    add_settings_section(
        'giftflowwp_payment_settings',
        __('Payment Methods', 'giftflowwp'),
        'giftflowwp_payment_settings_callback',
        'giftflowwp-payment'
    );

    // Email Template Section
    add_settings_section(
        'giftflowwp_email_settings',
        __('Email Templates', 'giftflowwp'),
        'giftflowwp_email_settings_callback',
        'giftflowwp-email'
    );

    // Design Section
    add_settings_section(
        'giftflowwp_design_settings',
        __('Design Settings', 'giftflowwp'),
        'giftflowwp_design_settings_callback',
        'giftflowwp-design'
    );

    // Add fields to General Settings
    add_settings_field(
        'giftflowwp_currency',
        __('Default Currency', 'giftflowwp'),
        'giftflowwp_currency_callback',
        'giftflowwp-dashboard',
        'giftflowwp_general_settings'
    );

    add_settings_field(
        'giftflowwp_min_amount',
        __('Minimum Donation Amount', 'giftflowwp'),
        'giftflowwp_min_amount_callback',
        'giftflowwp-dashboard',
        'giftflowwp_general_settings'
    );

    // Add fields to Payment Methods
    add_settings_field(
        'giftflowwp_paypal_enabled',
        __('Enable PayPal', 'giftflowwp'),
        'giftflowwp_paypal_enabled_callback',
        'giftflowwp-payment',
        'giftflowwp_payment_settings'
    );

    add_settings_field(
        'giftflowwp_stripe_enabled',
        __('Enable Stripe', 'giftflowwp'),
        'giftflowwp_stripe_enabled_callback',
        'giftflowwp-payment',
        'giftflowwp_payment_settings'
    );

    add_settings_field(
        'giftflowwp_paypal_email',
        __('PayPal Email', 'giftflowwp'),
        'giftflowwp_paypal_email_callback',
        'giftflowwp-payment',
        'giftflowwp_payment_settings'
    );

    add_settings_field(
        'giftflowwp_stripe_publishable_key',
        __('Stripe Publishable Key', 'giftflowwp'),
        'giftflowwp_stripe_publishable_key_callback',
        'giftflowwp-payment',
        'giftflowwp_payment_settings'
    );

    add_settings_field(
        'giftflowwp_stripe_secret_key',
        __('Stripe Secret Key', 'giftflowwp'),
        'giftflowwp_stripe_secret_key_callback',
        'giftflowwp-payment',
        'giftflowwp_payment_settings'
    );

    // Add fields to Email Templates
    add_settings_field(
        'giftflowwp_donation_receipt',
        __('Donation Receipt Template', 'giftflowwp'),
        'giftflowwp_donation_receipt_callback',
        'giftflowwp-email',
        'giftflowwp_email_settings'
    );

    add_settings_field(
        'giftflowwp_donation_notification',
        __('Admin Notification Template', 'giftflowwp'),
        'giftflowwp_donation_notification_callback',
        'giftflowwp-email',
        'giftflowwp_email_settings'
    );

    add_settings_field(
        'giftflowwp_email_from_name',
        __('Email From Name', 'giftflowwp'),
        'giftflowwp_email_from_name_callback',
        'giftflowwp-email',
        'giftflowwp_email_settings'
    );

    add_settings_field(
        'giftflowwp_email_from_address',
        __('Email From Address', 'giftflowwp'),
        'giftflowwp_email_from_address_callback',
        'giftflowwp-email',
        'giftflowwp_email_settings'
    );

    // Add fields to Design Settings
    add_settings_field(
        'giftflowwp_primary_color',
        __('Primary Color', 'giftflowwp'),
        'giftflowwp_primary_color_callback',
        'giftflowwp-design',
        'giftflowwp_design_settings'
    );

    add_settings_field(
        'giftflowwp_button_style',
        __('Button Style', 'giftflowwp'),
        'giftflowwp_button_style_callback',
        'giftflowwp-design',
        'giftflowwp_design_settings'
    );

    add_settings_field(
        'giftflowwp_form_layout',
        __('Form Layout', 'giftflowwp'),
        'giftflowwp_form_layout_callback',
        'giftflowwp-design',
        'giftflowwp_design_settings'
    );

    add_settings_field(
        'giftflowwp_custom_css',
        __('Custom CSS', 'giftflowwp'),
        'giftflowwp_custom_css_callback',
        'giftflowwp-design',
        'giftflowwp_design_settings'
    );
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

// Field Callbacks
function giftflowwp_currency_callback() {
    $options = get_option('giftflowwp_general_options');
    $currency = isset($options['currency']) ? $options['currency'] : 'USD';
    ?>
    <select name="giftflowwp_general_options[currency]">
        <option value="USD" <?php selected($currency, 'USD'); ?>>USD ($)</option>
        <option value="EUR" <?php selected($currency, 'EUR'); ?>>EUR (€)</option>
        <option value="GBP" <?php selected($currency, 'GBP'); ?>>GBP (£)</option>
    </select>
    <?php
}

function giftflowwp_min_amount_callback() {
    $options = get_option('giftflowwp_general_options');
    $min_amount = isset($options['min_amount']) ? $options['min_amount'] : '1';
    ?>
    <input type="number" name="giftflowwp_general_options[min_amount]" value="<?php echo esc_attr($min_amount); ?>" min="0" step="0.01" />
    <?php
}

function giftflowwp_paypal_enabled_callback() {
    $options = get_option('giftflowwp_payment_options');
    $enabled = isset($options['paypal_enabled']) ? $options['paypal_enabled'] : false;
    ?>
    <input type="checkbox" name="giftflowwp_payment_options[paypal_enabled]" value="1" <?php checked($enabled, 1); ?> />
    <?php
}

function giftflowwp_stripe_enabled_callback() {
    $options = get_option('giftflowwp_payment_options');
    $enabled = isset($options['stripe_enabled']) ? $options['stripe_enabled'] : false;
    ?>
    <input type="checkbox" name="giftflowwp_payment_options[stripe_enabled]" value="1" <?php checked($enabled, 1); ?> />
    <?php
}

function giftflowwp_paypal_email_callback() {
    $options = get_option('giftflowwp_payment_options');
    $email = isset($options['paypal_email']) ? $options['paypal_email'] : '';
    ?>
    <input type="email" name="giftflowwp_payment_options[paypal_email]" value="<?php echo esc_attr($email); ?>" class="regular-text" />
    <?php
}

function giftflowwp_stripe_publishable_key_callback() {
    $options = get_option('giftflowwp_payment_options');
    $key = isset($options['stripe_publishable_key']) ? $options['stripe_publishable_key'] : '';
    ?>
    <input type="text" name="giftflowwp_payment_options[stripe_publishable_key]" value="<?php echo esc_attr($key); ?>" class="regular-text" />
    <?php
}

function giftflowwp_stripe_secret_key_callback() {
    $options = get_option('giftflowwp_payment_options');
    $key = isset($options['stripe_secret_key']) ? $options['stripe_secret_key'] : '';
    ?>
    <input type="password" name="giftflowwp_payment_options[stripe_secret_key]" value="<?php echo esc_attr($key); ?>" class="regular-text" />
    <?php
}

function giftflowwp_donation_receipt_callback() {
    $options = get_option('giftflowwp_email_options');
    $template = isset($options['donation_receipt']) ? $options['donation_receipt'] : '';
    ?>
    <textarea name="giftflowwp_email_options[donation_receipt]" rows="5" cols="50" class="large-text"><?php echo esc_textarea($template); ?></textarea>
    <p class="description"><?php _e('Use the following placeholders: {donor_name}, {amount}, {date}, {transaction_id}', 'giftflowwp'); ?></p>
    <?php
}

function giftflowwp_donation_notification_callback() {
    $options = get_option('giftflowwp_email_options');
    $template = isset($options['donation_notification']) ? $options['donation_notification'] : '';
    ?>
    <textarea name="giftflowwp_email_options[donation_notification]" rows="5" cols="50" class="large-text"><?php echo esc_textarea($template); ?></textarea>
    <p class="description"><?php _e('Use the following placeholders: {donor_name}, {amount}, {date}, {transaction_id}', 'giftflowwp'); ?></p>
    <?php
}

function giftflowwp_email_from_name_callback() {
    $options = get_option('giftflowwp_email_options');
    $name = isset($options['email_from_name']) ? $options['email_from_name'] : get_bloginfo('name');
    ?>
    <input type="text" name="giftflowwp_email_options[email_from_name]" value="<?php echo esc_attr($name); ?>" class="regular-text" />
    <?php
}

function giftflowwp_email_from_address_callback() {
    $options = get_option('giftflowwp_email_options');
    $email = isset($options['email_from_address']) ? $options['email_from_address'] : get_bloginfo('admin_email');
    ?>
    <input type="email" name="giftflowwp_email_options[email_from_address]" value="<?php echo esc_attr($email); ?>" class="regular-text" />
    <?php
}

function giftflowwp_primary_color_callback() {
    $options = get_option('giftflowwp_design_options');
    $color = isset($options['primary_color']) ? $options['primary_color'] : '#0073aa';
    ?>
    <input type="color" name="giftflowwp_design_options[primary_color]" value="<?php echo esc_attr($color); ?>" />
    <?php
}

function giftflowwp_button_style_callback() {
    $options = get_option('giftflowwp_design_options');
    $style = isset($options['button_style']) ? $options['button_style'] : 'default';
    ?>
    <select name="giftflowwp_design_options[button_style]">
        <option value="default" <?php selected($style, 'default'); ?>>Default</option>
        <option value="rounded" <?php selected($style, 'rounded'); ?>>Rounded</option>
        <option value="outline" <?php selected($style, 'outline'); ?>>Outline</option>
    </select>
    <?php
}

function giftflowwp_form_layout_callback() {
    $options = get_option('giftflowwp_design_options');
    $layout = isset($options['form_layout']) ? $options['form_layout'] : 'default';
    ?>
    <select name="giftflowwp_design_options[form_layout]">
        <option value="default" <?php selected($layout, 'default'); ?>>Default</option>
        <option value="compact" <?php selected($layout, 'compact'); ?>>Compact</option>
        <option value="modern" <?php selected($layout, 'modern'); ?>>Modern</option>
    </select>
    <?php
}

function giftflowwp_custom_css_callback() {
    $options = get_option('giftflowwp_design_options');
    $css = isset($options['custom_css']) ? $options['custom_css'] : '';
    ?>
    <textarea name="giftflowwp_design_options[custom_css]" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($css); ?></textarea>
    <?php
}
