<?php
/**
 * Donation Transaction Meta Box Class
 *
 * @package GiftFlowWp
 * @subpackage Admin
 */

namespace GiftFlowWp\Admin\MetaBoxes;

/**
 * Donation Transaction Meta Box Class
 */
class Donation_Transaction_Meta extends Base_Meta_Box {
    /**
     * Initialize the meta box
     */
    public function __construct() {
        $this->id = 'donation_transaction_details';
        $this->title = __( 'Transaction Details', 'giftflowwp' );
        $this->post_type = 'donation';
        parent::__construct();
    }

    /**
     * Get meta box fields
     *
     * @return array
     */
    protected function get_fields() {
        return array(
            'amount' => array(
                'label' => __( 'Amount', 'giftflowwp' ),
                'type'  => 'number',
                'step'  => '0.01',
            ),
            'payment_method' => array(
                'label'   => __( 'Payment Method', 'giftflowwp' ),
                'type'    => 'select',
                'options' => array(
                    'stripe' => __( 'Stripe', 'giftflowwp' ),
                    'paypal' => __( 'PayPal', 'giftflowwp' ),
                    'bank'   => __( 'Bank Transfer', 'giftflowwp' ),
                ),
            ),
            'status' => array(
                'label'   => __( 'Status', 'giftflowwp' ),
                'type'    => 'select',
                'options' => array(
                    'pending'   => __( 'Pending', 'giftflowwp' ),
                    'completed' => __( 'Completed', 'giftflowwp' ),
                    'failed'    => __( 'Failed', 'giftflowwp' ),
                    'refunded'  => __( 'Refunded', 'giftflowwp' ),
                ),
            ),
            'transaction_id' => array(
                'label' => __( 'Transaction ID', 'giftflowwp' ),
                'type'  => 'textfield',
            ),
            'donor_id' => array(
                'label' => __( 'Donor', 'giftflowwp' ),
                'type'  => 'select',
                'options' => $this->get_donors(),
            ),
            'campaign_id' => array(
                'label' => __( 'Campaign', 'giftflowwp' ),
                'type'  => 'select',
                'options' => $this->get_campaigns(),
            ),
        );
    }

    /**
     * Render the meta box
     *
     * @param \WP_Post $post Post object.
     */
    public function render_meta_box( $post ) {
        wp_nonce_field( 'donation_transaction_details', 'donation_transaction_details_nonce' );

        $fields = $this->get_fields();
        foreach ( $fields as $field_id => $field ) {
            $value = get_post_meta( $post->ID, '_' . $field_id, true );
            
            // Create field instance
            $field_args = array(
                'value' => $value,
                'label' => $field['label'],
            );
            
            // Add additional field properties based on type
            if ( isset( $field['options'] ) ) {
                $field_args['options'] = $field['options'];
            }
            
            if ( isset( $field['step'] ) ) {
                $field_args['step'] = $field['step'];
            }
            
            // Create and render the field
            $field_instance = new \GiftFlowWP_Field( $field_id, $field_id, $field['type'], $field_args );
            echo $field_instance->render();
        }
    }

    /**
     * Save the meta box
     *
     * @param int $post_id Post ID.
     */
    public function save_meta_box( $post_id ) {
        if ( ! $this->verify_nonce( 'donation_transaction_details_nonce', 'donation_transaction_details' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = $this->get_fields();
        foreach ( $fields as $field_id => $field ) {
            if ( isset( $_POST[ $field_id ] ) ) {
                update_post_meta(
                    $post_id,
                    '_' . $field_id,
                    sanitize_text_field( wp_unslash( $_POST[ $field_id ] ) )
                );
            }
        }
    }

    /**
     * Get donors for select field
     *
     * @return array
     */
    private function get_donors() {
        $donors = array();
        $posts = get_posts(
            array(
                'post_type'      => 'donor',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            )
        );

        foreach ( $posts as $post ) {
            $donors[ $post->ID ] = $post->post_title;
        }

        return $donors;
    }

    /**
     * Get campaigns for select field
     *
     * @return array
     */
    private function get_campaigns() {
        $campaigns = array();
        $posts = get_posts(
            array(
                'post_type'      => 'campaign',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            )
        );

        foreach ( $posts as $post ) {
            $campaigns[ $post->ID ] = $post->post_title;
        }

        return $campaigns;
    }
} 