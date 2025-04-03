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
            // first name
            'first_name' => array(
                'label' => __( 'First Name', 'giftflowwp' ),
                'type'  => 'textfield',
                'description' => __( 'Enter the first name of the donor', 'giftflowwp' ),
            ),
            // last name
            'last_name' => array(
                'label' => __( 'Last Name', 'giftflowwp' ),
                'type'  => 'textfield',
                'description' => __( 'Enter the last name of the donor', 'giftflowwp' ),
            ),
            // email
            'email' => array(
                'label' => __( 'Email', 'giftflowwp' ),
                'type'  => 'email',
                'description' => __( 'Enter the email of the donor', 'giftflowwp' ),
            ),
            // phone
            'phone' => array(
                'label' => __( 'Phone', 'giftflowwp' ),
                'type'  => 'tel',
                'description' => __( 'Enter the phone number of the donor', 'giftflowwp' ),
            ),
            // address
            'address' => array(
                'label' => __( 'Address', 'giftflowwp' ),
                'type'  => 'textarea',
                'description' => __( 'Enter the address of the donor', 'giftflowwp' ),
            ),
            // city
            'city' => array(
                'label' => __( 'City', 'giftflowwp' ),
                'type'  => 'textfield',
                'description' => __( 'Enter the city of the donor', 'giftflowwp' ),
            ),
            // state
            'state' => array(
                'label' => __( 'State/Province', 'giftflowwp' ),
                'type'  => 'textfield',
                'description' => __( 'Enter the state/province of the donor', 'giftflowwp' ),
            ),
            // postal code
            'postal_code' => array(
                'label' => __( 'Postal Code', 'giftflowwp' ),
                'type'  => 'textfield',
                'description' => __( 'Enter the postal code of the donor', 'giftflowwp' ),
            ),
            // country
            'country' => array(
                'label' => __( 'Country', 'giftflowwp' ),
                'type'  => 'textfield',
                'description' => __( 'Enter the country of the donor', 'giftflowwp' ),
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
            
            // Create field instance with all necessary parameters
            $field_instance = new \GiftFlowWP_Field(
                $field_id,                    // id
                $field_id,                    // name
                $field['type'],              // type
                array(
                    'value' => $value,
                    'label' => $field['label'],
                    'description' => $field['description'],
                    'wrapper_classes' => array('giftflowwp-field-wrapper'),
                    'classes' => array('giftflowwp-field-input'),
                    'attributes' => array(
                        'id' => $field_id,
                        'name' => $field_id,
                    )
                )
            );
            
            // Render the field
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