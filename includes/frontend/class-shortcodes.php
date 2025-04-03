<?php
/**
 * Shortcodes class for GiftFlowWp
 *
 * @package GiftFlowWp
 * @subpackage Frontend
 */

namespace GiftFlowWp\Frontend;

use GiftFlowWp\Core\Base;

/**
 * Handles all shortcode functionality
 */
class Shortcodes extends Base {
    /**
     * Initialize shortcodes
     */
    public function __construct() {
        parent::__construct();
        $this->init_shortcodes();
    }

    /**
     * Register shortcodes
     */
    private function init_shortcodes() {
        add_shortcode( 'giftflow_donation_form', array( $this, 'render_donation_form' ) );
        add_shortcode( 'giftflow_campaign', array( $this, 'render_campaign' ) );
        add_shortcode( 'giftflow_donations', array( $this, 'render_donations' ) );
    }

    /**
     * Render donation form shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_donation_form( $atts ) {
        $atts = shortcode_atts( array(
            'campaign_id' => 0,
            'amount' => '',
            'show_recurring' => true,
        ), $atts );

        ob_start();
        include $this->plugin_dir . 'templates/frontend/donation-form.php';
        return ob_get_clean();
    }

    /**
     * Render campaign shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_campaign( $atts ) {
        $atts = shortcode_atts( array(
            'id' => 0,
            'show_progress' => true,
            'show_donations' => true,
        ), $atts );

        ob_start();
        include $this->plugin_dir . 'templates/frontend/campaign.php';
        return ob_get_clean();
    }

    /**
     * Render donations list shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_donations( $atts ) {
        $atts = shortcode_atts( array(
            'campaign_id' => 0,
            'limit' => 10,
            'show_amount' => true,
        ), $atts );

        ob_start();
        include $this->plugin_dir . 'templates/frontend/donations.php';
        return ob_get_clean();
    }
} 