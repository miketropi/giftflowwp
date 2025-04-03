<?php
/**
 * Plugin Name: GiftFlowWp
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
            <p><?php _e( 'GiftFlowWp requires Composer dependencies to be installed. Please run "composer install" in the plugin directory.', 'giftflowwp' ); ?></p>
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
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/core/class-ajax.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/common.php';
    
    // Admin files
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/dashboard.php';
    
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-base-post-type.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-donation.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-donor.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/post-types/class-campaign.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/settings.php';
    
    // Meta boxes
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-base-meta-box.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-donation-transaction-meta.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-donor-contact-meta.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/meta-boxes/class-campaign-details-meta.php';
    
    // Frontend files
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/frontend/class-shortcodes.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/frontend/class-forms.php';
    
    // Payment gateways
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/gateways/class-gateway-base.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/gateways/class-stripe.php';
    require_once GIFTFLOWWP_PLUGIN_DIR . 'includes/gateways/class-paypal.php';
    
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
    // Load text domain
    // load_plugin_textdomain( 'giftflowwp', false, dirname( GIFTFLOWWP_PLUGIN_BASENAME ) . '/languages' );

    // Initialize plugin
    $plugin = new \GiftFlowWp\Core\Loader(); 
    $plugin->init();
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
            __( 'GiftFlowWp requires PHP 7.4 or higher.', 'giftflowwp' ),
            'Plugin Activation Error',
            [ 'back_link' => true ]
        );
    }

    // Check if Composer dependencies are installed
    if ( ! file_exists( GIFTFLOWWP_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            __( 'GiftFlowWp requires Composer dependencies to be installed. Please run "composer install" in the plugin directory.', 'giftflowwp' ),
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
