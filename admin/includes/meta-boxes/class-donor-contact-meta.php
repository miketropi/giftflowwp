<?php
/**
 * Donor Contact Meta Box Class
 *
 * @package GiftFlowWp
 * @subpackage Admin
 */

namespace GiftFlowWp\Admin\MetaBoxes;

/**
 * Donor Contact Meta Box Class
 */
class Donor_Contact_Meta extends Base_Meta_Box {
    /**
     * Initialize the meta box
     */
    public function __construct() {
        $this->id = 'donor_contact_details';
        $this->title = __( 'Contact Information', 'giftflowwp' );
        $this->post_type = 'donor';
        parent::__construct();
    }

    /**
     * Get meta box fields
     *
     * @return array
     */
    protected function get_fields() {
        return array(
            'email' => array(
                'label' => __( 'Email', 'giftflowwp' ),
                'type'  => 'email',
            ),
            'phone' => array(
                'label' => __( 'Phone', 'giftflowwp' ),
                'type'  => 'tel',
            ),
            'address' => array(
                'label' => __( 'Address', 'giftflowwp' ),
                'type'  => 'textarea',
            ),
            'city' => array(
                'label' => __( 'City', 'giftflowwp' ),
                'type'  => 'textfield',
            ),
            'state' => array(
                'label' => __( 'State/Province', 'giftflowwp' ),
                'type'  => 'textfield',
            ),
            'postal_code' => array(
                'label' => __( 'Postal Code', 'giftflowwp' ),
                'type'  => 'textfield',
            ),
            'country' => array(
                'label' => __( 'Country', 'giftflowwp' ),
                'type'  => 'textfield',
            ),
        );
    }

    /**
     * Render the meta box
     *
     * @param \WP_Post $post Post object.
     */
    public function render_meta_box( $post ) {
        wp_nonce_field( 'donor_contact_details', 'donor_contact_details_nonce' );

        $fields = $this->get_fields();
        foreach ( $fields as $field_id => $field ) {
            $value = get_post_meta( $post->ID, '_' . $field_id, true );
            
            // Create field instance
            $field_args = array(
                'value' => $value,
                'label' => $field['label'],
            );
            
            // Add additional field properties based on type
            if ( 'textarea' === $field['type'] ) {
                $field_args['rows'] = 3;
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
        if ( ! $this->verify_nonce( 'donor_contact_details_nonce', 'donor_contact_details' ) ) {
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
                if ( 'textarea' === $field['type'] ) {
                    update_post_meta(
                        $post_id,
                        '_' . $field_id,
                        sanitize_textarea_field( wp_unslash( $_POST[ $field_id ] ) )
                    );
                } else {
                    update_post_meta(
                        $post_id,
                        '_' . $field_id,
                        sanitize_text_field( wp_unslash( $_POST[ $field_id ] ) )
                    );
                }
            }
        }
    }
} 