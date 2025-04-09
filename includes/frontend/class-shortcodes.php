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
        ), $atts );

        ob_start();
        // load the donation form template use class-template.php
        $template = new Template();
        $template->load_template('donation-form.php', $atts);
        return ob_get_clean();
    }

} 