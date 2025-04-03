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
        add_action( 'wp_ajax_process_donation', array( $this, 'process_donation' ) );
        add_action( 'wp_ajax_nopriv_process_donation', array( $this, 'process_donation' ) );
    }

    /**
     * Enqueue required scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'giftflowwp-forms',
            $this->plugin_url . 'assets/css/forms.css',
            array(),
            $this->version
        );

        wp_enqueue_script(
            'giftflowwp-forms',
            $this->plugin_url . 'assets/js/forms.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        wp_localize_script(
            'giftflowwp-forms',
            'giftflowwpForms',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'giftflowwp_donation_nonce' ),
            )
        );
    }

    /**
     * Process donation form submission
     */
    public function process_donation() {
        check_ajax_referer( 'giftflowwp_donation_nonce', 'nonce' );

        $data = array(
            'amount' => isset( $_POST['amount'] ) ? floatval( $_POST['amount'] ) : 0,
            'campaign_id' => isset( $_POST['campaign_id'] ) ? intval( $_POST['campaign_id'] ) : 0,
            'donor_name' => isset( $_POST['donor_name'] ) ? sanitize_text_field( $_POST['donor_name'] ) : '',
            'donor_email' => isset( $_POST['donor_email'] ) ? sanitize_email( $_POST['donor_email'] ) : '',
            'payment_method' => isset( $_POST['payment_method'] ) ? sanitize_text_field( $_POST['payment_method'] ) : '',
            'is_recurring' => isset( $_POST['is_recurring'] ) ? (bool) $_POST['is_recurring'] : false,
        );

        // Validate data
        if ( ! $this->validate_donation_data( $data ) ) {
            wp_send_json_error( array(
                'message' => __( 'Invalid donation data', 'giftflowwp' ),
            ) );
        }

        // Process payment
        $payment_result = $this->process_payment( $data );

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
        if ( $data['amount'] <= 0 ) {
            return false;
        }

        if ( empty( $data['donor_name'] ) || empty( $data['donor_email'] ) ) {
            return false;
        }

        if ( ! is_email( $data['donor_email'] ) ) {
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
        return true;
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