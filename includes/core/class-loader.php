<?php
/**
 * Loader class for GiftFlowWp
 *
 * @package GiftFlowWp
 * @subpackage Core
 */

namespace GiftFlowWp\Core;

/**
 * Loader class that handles file loading and initialization
 */
class Loader extends Base {
    /**
     * Initialize the loader
     */
    public function __construct() {
        parent::__construct();
        $this->init_hooks();
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        wp_enqueue_style('giftflowwp-dashboard', $this->get_plugin_url() . 'assets/css/admin.bundle.css', array(), $this->get_version());
    }

    /**
     * Register dashboard styles
     */
    // function giftflowwp_dashboard_styles() {
    //     $screen = get_current_screen();
    //     if ($screen && $screen->id === 'toplevel_page_giftflowwp-dashboard') {
    //         wp_enqueue_style('giftflowwp-dashboard', GIFTFLOWWP_PLUGIN_URL . 'assets/css/admin.bundle.css', array(), GIFTFLOWWP_VERSION);
    //     }
    // }
    // add_action('admin_enqueue_scripts', 'giftflowwp_dashboard_styles');

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        // add_action( 'init', array( $this, 'init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'giftflowwp',
            false,
            dirname( plugin_basename( GIFTFLOWWP_PLUGIN_DIR ) ) . '/languages/'
        );
    }

    /**
     * Initialize plugin components
     */
    public function init() {
        // Initialize post types
        new \GiftFlowWp\Admin\PostTypes\Donation();
        new \GiftFlowWp\Admin\PostTypes\Donor();
        new \GiftFlowWp\Admin\PostTypes\Campaign();

        // Initialize meta boxes
        new \GiftFlowWp\Admin\MetaBoxes\Donation_Transaction_Meta();
        new \GiftFlowWp\Admin\MetaBoxes\Donor_Contact_Meta();
        new \GiftFlowWp\Admin\MetaBoxes\Campaign_Details_Meta();
    }
} 