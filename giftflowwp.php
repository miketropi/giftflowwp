<?php
/**
 * Plugin Name: GiftFlow
 * Plugin URI: https://giftflowwp.com
 * Description: A comprehensive WordPress plugin for managing donations, donors, and campaigns with modern features and extensible architecture.
 * Version: 1.0.0
 * Author: GiftFlowWp Team
 * Author URI: https://giftflowwp.com
 * Text Domain: giftflowwp
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin constants
define( 'GIFTFLOWWP_VERSION', '1.0.0' );
define( 'GIFTFLOWWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GIFTFLOWWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GIFTFLOWWP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Include Composer autoloader
if ( file_exists( GIFTFLOWWP_PLUGIN_DIR . 'vendor/autoload.php' ) ) { 
    require_once GIFTFLOWWP_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    add_action( 'admin_notices', function() {
        ?>
        <div class="notice notice-error">
            <p><?php esc_html_e( 'GiftFlowWp requires Composer dependencies to be installed. Please run "composer install" in the plugin directory.', 'giftflowwp' ); ?></p>
        </div>
        <?php
    } );
    return;
}

/**
 * Load plugin files
 * 
 * A safer approach to loading plugin files using direct includes
 * rather than relying on autoloading which can be error-prone
 */
function giftflowwp_load_files() {
    // Core files
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-base.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-loader.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-field.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-role.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-ajax.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-block-template.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-wp-block-custom-hooks.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'blocks/index.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/common.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/hooks.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/mail.php';

    
    // Admin files
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/dashboard.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/class-export.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/class-chart.php';
    
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-base-post-type.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-donation.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-donor.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-campaign.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/settings.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/api.php';
    
    // Meta boxes
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-base-meta-box.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-donation-transaction-meta.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-donor-contact-meta.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-campaign-details-meta.php';
    
    // Frontend files
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/frontend/class-shortcodes.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/frontend/class-forms.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/frontend/class-template.php';
    
    // Payment gateways
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/gateways/class-gateway-base.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/gateways/class-stripe.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/gateways/class-paypal.php';

    // Blocks
    
    // Apply filters to allow extensions to load additional files
    $additional_files = apply_filters( 'giftflowwp_load_files', array() );
    
    if ( ! empty( $additional_files ) && is_array( $additional_files ) ) {
        foreach ( $additional_files as $file ) {
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }
    }
}

// Load all required files
giftflowwp_load_files();

// Initialize plugin
add_action( 'plugins_loaded', 'giftflowwp_init' );

/**
 * Initialize the plugin
 */
function giftflowwp_init() { 
    // Initialize plugin
    $plugin = new \GiftFlowWp\Core\Loader();   
    // $plugin->init();
}

// Activation hook
register_activation_hook( __FILE__, 'giftflowwp_activate' );

/**
 * Plugin activation
 */
function giftflowwp_activate() {
    // Check PHP version
    if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            esc_html__( 'GiftFlowWp requires PHP 7.4 or higher.', 'giftflowwp' ),
            'Plugin Activation Error',
            [ 'back_link' => true ]
        );
    }

    // Check if Composer dependencies are installed
    if ( ! file_exists( GIFTFLOWWP_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            esc_html__( 'GiftFlowWp requires Composer dependencies to be installed. Please run "composer install" in the plugin directory.', 'giftflowwp' ),
            'Plugin Activation Error',
            [ 'back_link' => true ]
        );
    }

    // Initialize plugin
    $plugin = new \GiftFlowWp\Core\Loader();
    $plugin->activate();
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'giftflowwp_deactivate' );

/**
 * Plugin deactivation
 */
function giftflowwp_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();

    // Deactivate plugin
    $plugin = new \GiftFlowWp\Core\Loader();
    $plugin->deactivate();
}

add_action('admin_bar_menu', 'giftflowwp_admin_bar_item', 100);

function giftflowwp_admin_bar_item($wp_admin_bar) {
    // Add parent item
    $args = [
        'id'    => 'giftflowwp_admin_bar_item',
        'title' => esc_html__('Gift Flow Dashboard', 'giftflowwp'),
        'href'  => admin_url('admin.php?page=giftflowwp-dashboard'), // or any URL
        'meta'  => [
            'class' => 'giftflowwp_admin_bar_item',
            'title' => esc_html__('Go to Gift Flow Dashboard', 'giftflowwp'), // Tooltip
        ],
    ];
    $wp_admin_bar->add_node($args);

    
    // $wp_admin_bar->add_node([
    //     'id'     => 'giftflowwp_admin_bar_item_donations',
    //     'title'  => esc_html__('Donations', 'giftflowwp'),
    //     'href'   => admin_url('edit.php?post_type=donation'),
    //     'parent' => 'giftflowwp_admin_bar_item',
    // ]);

    
    // $wp_admin_bar->add_node([
    //     'id'     => 'giftflowwp_admin_bar_item_donors',
    //     'title'  => esc_html__('Donors', 'giftflowwp'),
    //     'href'   => admin_url('edit.php?post_type=donor'),
    //     'parent' => 'giftflowwp_admin_bar_item',
    // ]);

    
    // $wp_admin_bar->add_node([
    //     'id'     => 'giftflowwp_admin_bar_item_campaigns',
    //     'title'  => esc_html__('Campaigns', 'giftflowwp'),
    //     'href'   => admin_url('edit.php?post_type=campaign'),
    //     'parent' => 'giftflowwp_admin_bar_item',
    // ]);

    // Add child item
    $wp_admin_bar->add_node([
        'id'     => 'giftflowwp_admin_bar_item_settings',
        'title'  => esc_html__('Settings', 'giftflowwp'),
        'href'   => admin_url('admin.php?page=giftflowwp-settings'),
        'parent' => 'giftflowwp_admin_bar_item',
    ]);
}