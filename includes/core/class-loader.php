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
    public function admin_enqueue_scripts() {
        wp_enqueue_script('giftflowwp-admin', $this->get_plugin_url() . 'assets/js/admin.bundle.js', array(), $this->get_version(), true);
        wp_enqueue_style('giftflowwp-admin', $this->get_plugin_url() . 'assets/css/admin.bundle.css', array(), $this->get_version());
    
        wp_localize_script('giftflowwp-admin', 'giftflowwp_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('giftflowwp_admin_nonce'),
        ));
    }

    public function enqueue_scripts() {
        // block-campaign-status-bar.bundle.css
        wp_enqueue_style('giftflowwp-block-campaign-status-bar', $this->get_plugin_url() . 'assets/css/block-campaign-status-bar.bundle.css', array(), $this->get_version());
    
        // // donation-form.bundle.css
        // wp_enqueue_style('giftflowwp-donation-form', $this->get_plugin_url() . 'assets/css/donation-form.bundle.css', array(), $this->get_version());
    
        // // forms.bundle.js
        // wp_enqueue_script('giftflowwp-forms', $this->get_plugin_url() . 'assets/js/forms.bundle.js', array('jquery'), $this->get_version(), true);
    
        // // stripe-donation.bundle.js
        // wp_enqueue_script('giftflowwp-stripe-donation', $this->get_plugin_url() . 'assets/js/stripe-donation.bundle.js', array('jquery', 'giftflowwp-forms'), $this->get_version(), true);
    }

    // enqueue blocks
    public function enqueue_blocks() {
        wp_enqueue_style('giftflowwp-common', $this->get_plugin_url() . 'assets/css/common.bundle.css', array(), $this->get_version());

        $args = require($this->get_plugin_dir() . '/blocks-build/index.asset.php');
        wp_enqueue_script('giftflowwp-blocks', $this->get_plugin_url() . '/blocks-build/index.js', $args['dependencies'], $args['version'], true);
        wp_enqueue_style('giftflowwp-block-campaign-status-bar', $this->get_plugin_url() . 'assets/css/block-campaign-status-bar.bundle.css', array(), $this->get_version());
        wp_enqueue_style('giftflowwp-block-campaign-single-content', $this->get_plugin_url() . 'assets/css/block-campaign-single-content.bundle.css', array(), $this->get_version());
        
        // load common js
        $args_common = require($this->get_plugin_dir() . '/assets/js/common.bundle.asset.php');
        wp_enqueue_script('giftflowwp-common', $this->get_plugin_url() . 'assets/js/common.bundle.js', array('jquery'), $args_common['version'], true);
        
        // localize script
        wp_localize_script('giftflowwp-common', 'giftflowwp_common', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('giftflowwp_common_nonce'),
        ));
    }

    // Creating a new (custom) block category
    public function register_block_category( $categories ) {
        $categories[] = array(
            'slug'  => 'giftflowwp',
            'title' => 'GiftFlowWP',
            'icon'  => 'megaphone'
        );
    
        return $categories;
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'enqueue_block_assets', array( $this, 'enqueue_blocks' ) );
        add_filter( 'block_categories_all', array( $this, 'register_block_category' ) );
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
        // core
        new \GiftFlowWp\Core\Block_Template();

        // Initialize post types
        new \GiftFlowWp\Admin\PostTypes\Donation();
        new \GiftFlowWp\Admin\PostTypes\Donor();
        new \GiftFlowWp\Admin\PostTypes\Campaign();

        // Initialize meta boxes
        new \GiftFlowWp\Admin\MetaBoxes\Donation_Transaction_Meta();
        new \GiftFlowWp\Admin\MetaBoxes\Donor_Contact_Meta();
        new \GiftFlowWp\Admin\MetaBoxes\Campaign_Details_Meta();

        // Initialize frontend components
        new \GiftFlowWp\Frontend\Shortcodes();
        new \GiftFlowWp\Frontend\Forms(); 

        \GiftFlowWp\Gateways\Gateway_Base::init_gateways();
    }

    public function activate() {

        $this->create_pages_init();

        // reset permalinks
        flush_rewrite_rules();
    }

    public function create_pages_init() {

        // create 2 pages donor-account and thank-donor & set template for there 
        $donor_account_page = get_page_by_path('donor-account');
        if (!$donor_account_page) {

            $donor_account_page = wp_insert_post(array(
                'post_title' => esc_html__('Donor Account', 'giftflowwp'),
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));

            update_post_meta(
                $donor_account_page,
                '_wp_page_template',
                'donor-account'
            );
        }

        $thank_donor_page = get_page_by_path('thank-donor');
        if (!$thank_donor_page) {

            $thank_donor_page = wp_insert_post(array(
                'post_title' => esc_html__('Thank Donor', 'giftflowwp'),
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));

            update_post_meta(
                $thank_donor_page,
                '_wp_page_template',
                'thank-donor'
            );
        }
    }

    public function deactivate() {

    }
} 