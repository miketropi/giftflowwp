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

        $campaign_id = intval($atts['campaign_id']);
        if (!$campaign_id) {
            return;
            // return '<div class="giftflowwp-error">' . __('Invalid campaign ID', 'giftflowwp') . '</div>';
        }

        // gateways
        $gateways = \GiftFlowWp\Gateways\Gateway_Base::get_registered_gateways();

        // preset donation amounts
        $preset_donation_amounts = giftflowwp_get_preset_donation_amounts_by_campaign($campaign_id);

        // raised amount
        $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);

        // goal amount
        $goal_amount = giftflowwp_get_campaign_goal_amount($campaign_id);


        // Get default donation amount (first preset amount or 10)
        $default_amount = !empty($preset_donation_amounts) ? $preset_donation_amounts[0]['amount'] : 10;

        // Get campaign title
        $campaign_title = get_the_title($campaign_id);

        // Get currency symbol
        $currency_symbol = giftflowwp_get_global_currency_symbol();

        // Get currency format template
        $currency_format_template = giftflowwp_get_currency_js_format_template();

        // array of donation types
        $donation_types = array();

        // get one-time donation
        $one_time_donation = get_post_meta($campaign_id, '_one_time', true);

        // if one-time donation is on, add it to the array
        if ($one_time_donation) {
            $donation_types[] = [
                'name' => 'one-time',
                'icon' => '',
                'label' => __('One-time Donation', 'giftflowwp'),
                'description' => __('Make a single donation', 'giftflowwp'),
            ];
        }

        // get recurring donation
        $recurring_donation = get_post_meta($campaign_id, '_recurring', true);
        // get recurring interval
        $recurring_interval = get_post_meta($campaign_id, '_recurring_interval', true);

        // if recurring donation is on, add it to the array
        if ($recurring_donation) {

            $recurring_label_array = [
                'daily' => __('Daily Donation', 'giftflowwp'),
                'weekly' => __('Weekly Donation', 'giftflowwp'),
                'monthly' => __('Monthly Donation', 'giftflowwp'),
                'yearly' => __('Yearly Donation', 'giftflowwp'),
            ];

            $recurring_label = $recurring_label_array[$recurring_interval];

            $donation_types[] = [
                'name' => 'recurring',
                'icon' => giftflowwp_svg_icon('loop'),
                'label' => $recurring_label,
                'description' => __('Make a recurring donation', 'giftflowwp'),
            ];
        }

        $user_fullname = '';
        $user_email = '';
        $user_info_readonly = false;
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $user_fullname = $current_user->display_name;
            $user_email = $current_user->user_email;
            $user_info_readonly = true;
        }

        ob_start();

        $atts['gateways'] = $gateways;
        $atts['preset_donation_amounts'] = $preset_donation_amounts;
        $atts['raised_amount'] = $raised_amount;
        $atts['goal_amount'] = $goal_amount;
        $atts['default_amount'] = $default_amount;
        $atts['campaign_title'] = $campaign_title;
        $atts['currency_symbol'] = $currency_symbol;
        $atts['currency_format_template'] = $currency_format_template;
        $atts['recurring_interval'] = $recurring_interval;
        $atts['donation_types'] = $donation_types;
        $atts['user_fullname'] = $user_fullname;
        $atts['user_email'] = $user_email;
        $atts['user_info_readonly'] = $user_info_readonly;

        // load the donation form template use class-template.php
        $template = new Template();
        $template->load_template('donation-form.php', $atts);
        return ob_get_clean();
    }

} 