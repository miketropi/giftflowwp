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
        // 'design' => __('Design', 'giftflowwp'),
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
                    // case 'design':
                    //     settings_fields('giftflowwp_design_options');
                    //     do_settings_sections('giftflowwp-design');
                    //     break;
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
            'section_title' => __('Email', 'giftflowwp'),
            'section_callback' => 'giftflowwp_email_settings_callback',
            'fields' => [
                // 'donation_receipt' => [
                //     'id' => 'giftflowwp_donation_receipt',
                //     'name' => 'giftflowwp_email_options[donation_receipt]',
                //     'type' => 'textarea',
                //     'label' => __('Donation Receipt Template', 'giftflowwp'),
                //     'value' => isset($email_options['donation_receipt']) ? $email_options['donation_receipt'] : '',
                //     'rows' => 5,
                //     'description' => __('Use the following placeholders: {donor_name}, {amount}, {date}, {transaction_id}', 'giftflowwp'),
                // ],
                // 'donation_notification' => [
                //     'id' => 'giftflowwp_donation_notification',
                //     'name' => 'giftflowwp_email_options[donation_notification]',
                //     'type' => 'textarea',
                //     'label' => __('Admin Notification Template', 'giftflowwp'),
                //     'value' => isset($email_options['donation_notification']) ? $email_options['donation_notification'] : '',
                //     'rows' => 5,
                //     'description' => __('Use the following placeholders: {donor_name}, {amount}, {date}, {transaction_id}', 'giftflowwp'),
                // ],
                'email_from_name' => [
                    'id' => 'giftflowwp_email_from_name',
                    'name' => 'giftflowwp_email_options[email_from_name]',
                    'type' => 'textfield',
                    'label' => __('Email From Name', 'giftflowwp'),
                    'value' => isset($email_options['email_from_name']) ? $email_options['email_from_name'] : get_bloginfo('name'),
                    'description' => __('The name that appears in the From field of emails', 'giftflowwp'),
                ],
                'email_admin_address' => [
                    'id' => 'giftflowwp_email_admin_address',
                    'name' => 'giftflowwp_email_options[email_admin_address]',
                    'type' => 'textfield',
                    'label' => __('Email Admin Address', 'giftflowwp'),
                    'value' => isset($email_options['email_admin_address']) ? $email_options['email_admin_address'] : get_bloginfo('admin_email'),
                    'input_type' => 'email',
                    'description' => __('Admin notification email address', 'giftflowwp'),
                ],
                'html_1' => [
                    'id' => 'giftflowwp_html_1',
                    'name' => 'giftflowwp_email_options[html_1]',
                    'type' => 'html', 
                    'html' => giftflowwp_test_mail_html(),
                    'label' => __('Test Email Template', 'giftflowwp'),
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
                    isset($field['html']) ? ['html' => $field['html']] : [],
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
    echo '<p>' . __('Configure email for notifications.', 'giftflowwp') . '</p>';
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

function giftflowwp_test_mail_html() {
    // get mail options 
    $email_opts = get_option('giftflowwp_email_options');
    $admin_email = $email_opts['email_admin_address'] ?? get_bloginfo('admin_email');

    // Define the default notification buttons
    $notification_buttons = [
        [
            'id'    => 'admin_new_donation',
            'label' => __('Notification: New Donation for Admin', 'giftflowwp'),
        ],
        [
            'id'    => 'donor_new_donation',
            'label' => __('Notification: New Donation for Donor', 'giftflowwp'),
        ],
        [
            'id'    => 'donor_thanks',
            'label' => __('Thanks Mail for Donor (Donation Successful)', 'giftflowwp'),
        ],
    ];

    /**
     * Allow developers to customize or extend the notification buttons.
     *
     * @param array $notification_buttons
     */
    $notification_buttons = apply_filters('giftflowwp_test_email_notification_buttons', $notification_buttons);

    // Render the buttons
    ob_start();
    echo '<div class="giftflowwp-email-notification-buttons" style="display: flex; gap: 10px; flex-wrap: wrap;">';
    foreach ($notification_buttons as $button) {
        printf(
            '<button 
                type="button" 
                class="button giftflowwp-email-btn" 
                id="%s" 
                onclick="window.giftflowwp.testSendMail_Handle(\'%s\')">%s</button> ',
            esc_attr($button['id']),
            esc_attr($button['id']),
            esc_html($button['label'])
        );
    }
    
    echo '</div>';
    // Add a message to indicate the recipient of the test email
    echo '<div style="margin-top:10px; color:#666; font-size:13px;">';
    esc_html_e('Test emails will be sent to the admin email address above: ', 'giftflowwp');
    echo '<u>' . $admin_email . '</u>';
    echo '</div>';
    return ob_get_clean();
}