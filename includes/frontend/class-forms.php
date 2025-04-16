<?php
/**
 * Forms class for GiftFlowWp
 *
 * @package GiftFlowWp
 * @subpackage Frontend
 */

namespace GiftFlowWp\Frontend;

use GiftFlowWp\Core\Base;

/**
 * Handles donation form functionality
 */
class Forms extends Base {
    /**
     * Initialize forms
     */
    public function __construct() {
        parent::__construct();
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_giftflowwp_donation_form', array( $this, 'process_donation' ) );
        add_action( 'wp_ajax_nopriv_giftflowwp_donation_form', array( $this, 'process_donation' ) );
    }

    /**
     * Enqueue required scripts and styles
     */
    public function enqueue_scripts() {
        // donation-form.bundle.css
        wp_enqueue_style('giftflowwp-donation-form', $this->get_plugin_url() . 'assets/css/donation-form.bundle.css', array(), $this->get_version());
    
        // forms.bundle.js
        wp_enqueue_script('giftflowwp-donation-forms', $this->get_plugin_url() . 'assets/js/forms.bundle.js', array('jquery'), $this->get_version(), true);
    
        // stripe-donation.bundle.js
        wp_enqueue_script('giftflowwp-stripe-donation', $this->get_plugin_url() . 'assets/js/stripe-donation.bundle.js', array('jquery', 'giftflowwp-donation-forms'), $this->get_version(), true);

        wp_localize_script(
            'giftflowwp-donation-forms',
            'giftflowwpDonationForms',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                // 'nonce' => wp_create_nonce( 'giftflowwp_donation_nonce' ),
            )
        );
    }

    /**
     * Process donation form submission
     * 
     * @return void
     * 
     * Fields include:
     * - anonymous_donation: boolean (true/false)
     * - campaign_id: string (campaign post ID)
     * - card_name: string (name on card)
     * - donation_amount: string (donation amount)
     * - donation_type: string (once/monthly/etc)
     * - donor_email: string (email address)
     * - donor_name: string (donor's name)
     * - payment_method: string (payment method)
     */
    public function process_donation() {
        // get fields from fetch post data
        $fields = json_decode( file_get_contents( 'php://input' ), true );

        // convert amout to float
        $fields['donation_amount'] = floatval( $fields['donation_amount'] );

        // add filter to fields
        $fields = apply_filters( 'giftflowwp_donation_form_fields', $fields );

        // wp_send_json_success( $fields );

        // giftflowwp_donation_form
        check_ajax_referer( 'giftflowwp_donation_form', 'wp_nonce' );

        // wp_send_json_success( $fields );

        // Validate data
        if ( ! $this->validate_donation_data( $fields ) ) {
            wp_send_json_error( array(
                'message' => __( 'Invalid donation data', 'giftflowwp' ),
            ) );
        }

        // Process payment
        $payment_result = $this->process_payment( $fields );

        if ( is_wp_error( $payment_result ) ) {
            wp_send_json_error( array(
                'message' => $payment_result->get_error_message(),
            ) );
        }

        // Create donation record
        $donation_id = $this->create_donation( $data, $payment_result );

        if ( is_wp_error( $donation_id ) ) {
            wp_send_json_error( array(
                'message' => $donation_id->get_error_message(),
            ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Donation processed successfully', 'giftflowwp' ),
            'donation_id' => $donation_id,
        ) );
    }

    /**
     * Validate donation data
     *
     * @param array $data Donation data
     * @return bool
     */
    private function validate_donation_data( $data ) {
        if ( $data['donation_amount'] <= 0 ) {
            return false;
        }

        if ( empty( $data['donor_name'] ) || empty( $data['donor_email'] ) ) {
            return false;
        }

        if ( ! is_email( $data['donor_email'] ) ) {
            return false;
        }

        if ( empty( $data['payment_method'] ) ) {
            return false;
        } 

        return true;
    }

    /**
     * Process payment
     *
     * @param array $data Donation data
     * @return mixed
     */
    private function process_payment( $data ) {
        // This will be implemented based on the payment gateway
        // wp_send_json_success( [
        //     'step' => 'process_payment',
        //     'data' => $data,
        // ] );

        // create hook for payment method, allow 3rd party to process payment
        // $payment_result = do_action( 'giftflowwp_process_payment_' . $data['payment_method'], $data );
        // wp_send_json_success( $payment_result );

        // // return payment result
        // return $payment_result;

        // return true if payment is successful
        // return true;

        // call function based on payment method, allow 3rd party to process payment
        // check if function exists
        if ( function_exists( 'giftflowwp_process_payment_' . $data['payment_method'] ) ) {
            return call_user_func( 'giftflowwp_process_payment_' . $data['payment_method'], $data );
        }

        return false;
    }

    /**
     * Create donation record
     *
     * @param array $data Donation data
     * @param mixed $payment_result Payment processing result
     * @return int|WP_Error
     */
    private function create_donation( $data, $payment_result ) {
        $donation_data = array(
            'post_title' => sprintf(
                __( 'Donation from %s', 'giftflowwp' ),
                $data['donor_name']
            ),
            'post_type' => 'donation',
            'post_status' => 'publish',
        );

        $donation_id = wp_insert_post( $donation_data );

        if ( is_wp_error( $donation_id ) ) {
            return $donation_id;
        }

        // Save donation meta
        update_post_meta( $donation_id, '_donation_amount', $data['amount'] );
        update_post_meta( $donation_id, '_donation_campaign_id', $data['campaign_id'] );
        update_post_meta( $donation_id, '_donation_donor_name', $data['donor_name'] );
        update_post_meta( $donation_id, '_donation_donor_email', $data['donor_email'] );
        update_post_meta( $donation_id, '_donation_payment_method', $data['payment_method'] );
        update_post_meta( $donation_id, '_donation_is_recurring', $data['is_recurring'] );

        return $donation_id;
    }
} 