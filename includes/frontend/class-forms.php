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
    
        wp_localize_script( 'giftflowwp-donation-forms', 'giftflowwpDonationForms', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'giftflowwp_donation_form' ),
        ) );
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
        // wp_send_json( $_POST );

        // get fields from fetch post data
        $fields = json_decode( file_get_contents( 'php://input' ), true );

        // wp_send_json( $fields );

        // convert amout to float
        $fields['donation_amount'] = floatval( $fields['donation_amount'] );

        // add filter to fields
        $fields = apply_filters( 'giftflowwp_donation_form_fields', $fields );

        // wp_send_json_success( $fields );

        // giftflowwp_donation_form
        check_ajax_referer( 'giftflowwp_donation_form', 'wp_nonce' );


        // Validate data
        if ( ! $this->validate_donation_data( $fields ) ) {
            wp_send_json_error( array(
                'message' => __( 'Invalid donation data', 'giftflowwp' ),
            ) );
        }

        // wp_send_json_success( [$fields] );

        // Create donation record
        $donation_id = $this->create_donation( $fields );

        if ( is_wp_error( $donation_id ) ) {
            wp_send_json_error( array(
                'message' => $donation_id->get_error_message(),
            ) );
        }

        // Process payment
        $payment_result = $this->process_payment( $fields, $donation_id );

        if ( is_wp_error( $payment_result ) ) {
            wp_send_json_error( array(
                'message' => $payment_result->get_error_message(),
            ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Donation processed successfully', 'giftflowwp' ),
            'donation_id' => $donation_id,
            'payment_result' => $payment_result,
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
    private function process_payment( $data, $donation_id ) {

        // call function based on payment method, allow 3rd party to process payment
        // check if function exists
        $payment_method = $data['payment_method'];
        $pm_obj =  \GiftFlowWp\Gateways\Gateway_Base::get_gateway( $payment_method );  
        if ( ! $pm_obj ) {
            return new \WP_Error( 'invalid_payment_method', __( 'Invalid payment method', 'giftflowwp' ) );
        }

        // Process payment using the gateway class
        if ( method_exists( $pm_obj, 'process_payment' ) ) {
            $payment_result = $pm_obj->process_payment( $data, $donation_id );
            if ( is_wp_error( $payment_result ) ) {
                return $payment_result; // Return error if payment processing fails
            }
            return $payment_result; // Return successful payment result
        }

        return false; // Return false if no process_payment method exists

        // if ( function_exists( 'GiftFlowWp\Gateways\giftflowwp_process_payment_' . $data['payment_method'] ) ) {
        //     return call_user_func( 'GiftFlowWp\Gateways\giftflowwp_process_payment_' . $data['payment_method'], $data, $donation_id );
        // }

        // return false;
    }

    /**
     * get donor record by email if exists, otherwise create new donor record
     * 
     * @param string $email Donor email
     * @param array $data Donation data
     * @return int|WP_Error
     */
    private function get_donor_id( $email, $data ) { 
        // get donor record by email
        $donor = get_posts( array(
            'post_type' => 'donor',
            'meta_key' => '_email',
            'meta_value' => $data['donor_email'],
        ) );

        if ( $donor ) {
            return $donor[0]->ID;
        } else {
            // create new donor record
            $donor_data = array(
                'post_title' => $data['donor_name'],
                'post_type' => 'donor',
                'post_status' => 'publish',
            );

            $donor_id = wp_insert_post( $donor_data );

            // save donor email
            update_post_meta( $donor_id, '_donor_email', $data['donor_email'] ); 
            update_post_meta( $donor_id, '_donor_first_name', $data['donor_name'] );

            return $donor_id;
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
    private function create_donation( $data, $payment_result = '' ) {
        // wp_send_json( $data );

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
        // Amount is required
        update_post_meta($donation_id, '_amount', $data['donation_amount']);

        // Campaign ID
        if (!empty($data['campaign_id'])) {
            update_post_meta($donation_id, '_campaign_id', $data['campaign_id']);
        }

        // Payment method
        if (!empty($data['payment_method'])) {
            update_post_meta($donation_id, '_payment_method', $data['payment_method']);
        }

        // Donation type
        if (!empty($data['donation_type'])) {
            update_post_meta($donation_id, '_donation_type', $data['donation_type']);
        }

        // Recurring interval
        if (!empty($data['recurring_interval'])) {
            update_post_meta($donation_id, '_recurring_interval', $data['recurring_interval']);
        }

        // Anonymous donation
        if (isset($data['anonymous_donation'])) {
            update_post_meta($donation_id, '_anonymous', $data['anonymous_donation']);
        }

        // Donor ID
        if (!empty($data['donor_email'])) {
            $donor_id = $this->get_donor_id(trim($data['donor_email']), $data);
            if ($donor_id) {
            update_post_meta($donation_id, '_donor_id', $donor_id);
            }
        }

        // donor_message
        if (!empty($data['donor_message'])) {
            update_post_meta($donation_id, '_donor_message', $data['donor_message']);
        }
        
        // custom field status
        update_post_meta( $donation_id, '_status', 'pending' ); 

        do_action( 'giftflowwp_donation_created', $donation_id );

        return $donation_id;
    }
} 