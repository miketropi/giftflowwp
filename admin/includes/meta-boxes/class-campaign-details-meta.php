<?php
/**
 * Campaign Details Meta Box Class
 *
 * @package GiftFlowWp
 * @subpackage Admin
 */

namespace GiftFlowWp\Admin\MetaBoxes;

use GiftFlowWP_Field;

/**
 * Campaign Details Meta Box Class
 */
class Campaign_Details_Meta extends Base_Meta_Box {
    /**
     * Initialize the meta box
     */
    public function __construct() {
        $this->id = 'campaign_details';
        $this->title = __( 'Campaign Details', 'giftflowwp' );
        $this->post_type = 'campaign';
        parent::__construct();
    }

    /**
     * Get meta box fields
     *
     * @return array
     */
    protected function get_fields() {
        return array(
            'regular' => array(
                'goal_amount' => array(
                    'label' => __( 'Goal Amount', 'giftflowwp' ),
                    'type'  => 'currency',
                    'step'  => '1',
                    'min'   => '0',
                    'currency_symbol' => '$',
                    // description
                    'description' => __( 'Enter the goal amount for the campaign', 'giftflowwp' ),
                ),
                'start_date' => array(
                    'label' => __( 'Start Date', 'giftflowwp' ),
                    'type'  => 'datetime',
                    // 'date_format' => 'Y-m-d',
                    'description' => __( 'Enter the start date for the campaign', 'giftflowwp' ),
                ),
                'end_date' => array(
                    'label' => __( 'End Date', 'giftflowwp' ),
                    'type'  => 'datetime',
                    // 'date_format' => 'Y-m-d',
                    'description' => __( 'Enter the end date for the campaign', 'giftflowwp' ),
                ),
                'status' => array(
                    'label'   => __( 'Status', 'giftflowwp' ),
                    'type'    => 'select',
                    'options' => array(
                        'active'   => __( 'Active', 'giftflowwp' ),
                        'completed' => __( 'Completed', 'giftflowwp' ),
                        'pending'   => __( 'Pending', 'giftflowwp' ),
                        // 'draft'    => __( 'Draft', 'giftflowwp' ),
                    ),
                    'description' => __( 'Select the status for the campaign', 'giftflowwp' ),
                ),
            ),
            'advanced' => array(
                // repeater preset donation amounts ($10, $25, $50, $100, $250)
                'donation_amounts' => array(
                    'label' => __( 'Preset Donation Amounts', 'giftflowwp' ),
                    'type'  => 'repeater',
                    'repeater_settings' => array(
                        'fields' => array(
                            'amount' => array(
                                'label' => __( 'Amount', 'giftflowwp' ),
                                'type'  => 'currency',
                                'step'  => '1',
                                'min'   => '0',
                                'value' => 10,
                                'description' => __( 'Enter the amount for the donation', 'giftflowwp' ),
                                'currency_symbol' => '$',
                            ),
                        ),
                    ),
                ),

                // On / Off switch allow custom donation amounts
                'allow_custom_donation_amounts' => array(
                    'label' => __( 'Allow Custom Donation Amounts', 'giftflowwp' ),
                    'type'  => 'switch',
                    'description' => __( 'Allow users to enter their own donation amounts', 'giftflowwp' ),
                ),

                'location' => array(
                    'label' => __( 'Location', 'giftflowwp' ),
                    'type'  => 'textfield',
                    'description' => __( 'Enter the location for the campaign', 'giftflowwp' ),
                ),
                'gallery' => array(
                    'label' => __( 'Gallery', 'giftflowwp' ),
                    'type'  => 'gallery',
                    'description' => __( 'Upload images for the campaign', 'giftflowwp' ),
                    'gallery_settings' => array(
                        'image_size' => 'thumbnail',
                        'button_text' => __( 'Select Images', 'giftflowwp' ),
                        'remove_text' => __( 'Remove All', 'giftflowwp' ),
                    )
                )
            )
        );
    }

    /**
     * Render the meta box
     *
     * @param \WP_Post $post Post object.
     */
    public function render_meta_box( $post ) {
        wp_nonce_field( 'campaign_details', 'campaign_details_nonce' );

        $fields = $this->get_fields();
        
        // Render regular fields
        foreach ( $fields['regular'] as $field_id => $field_args ) {
            $value = get_post_meta( $post->ID, '_' . $field_id, true );

            // Create field instance
            $field = new GiftFlowWP_Field(
                $field_id,
                $field_id,
                $field_args['type'],
                array_merge(
                    $field_args,
                    array(
                        'value' => $value,
                        'wrapper_classes' => array('campaign-details-field'),
                    )
                )
            );
            
            // Render the field
            echo $field->render();
        }
        
        // Add advanced section toggle button
        echo '<div class="campaign-advanced-toggle">';
        echo '<button type="button" class="button toggle-advanced-fields">' . __( 'Show Advanced Options', 'giftflowwp' ) . '</button>';
        echo '</div>';
        
        // Add advanced fields container
        echo '<div class="campaign-advanced-fields" style="display:none;">';
        echo '<h3>' . __( 'Advanced Options', 'giftflowwp' ) . '</h3>';
        
        // Render advanced fields
        foreach ( $fields['advanced'] as $field_id => $field_args ) {
            $value = get_post_meta( $post->ID, '_' . $field_id, true );

            // if field type is repeater, then we need to unserialize the value
            if ( $field_args['type'] == 'repeater' && $value ) {
                $value = unserialize( $value );
            }

            // Create field instance
            $field = new GiftFlowWP_Field(
                $field_id,
                $field_id,
                $field_args['type'],
                array_merge(
                    $field_args,
                    array(
                        'value' => $value,
                        'wrapper_classes' => array('campaign-details-field', 'advanced-field'),
                    )
                )
            );
            
            // Render the field
            echo $field->render();
        }
        
        echo '</div>';
        
        // Add JavaScript for toggle functionality
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.toggle-advanced-fields').on('click', function() {
                    var $advancedFields = $('.campaign-advanced-fields');
                    var $button = $(this);
                    
                    if ($advancedFields.is(':visible')) {
                        $advancedFields.slideUp();
                        $button.text('<?php echo esc_js( __( 'Show Advanced Options', 'giftflowwp' ) ); ?>');
                    } else {
                        $advancedFields.slideDown();
                        $button.text('<?php echo esc_js( __( 'Hide Advanced Options', 'giftflowwp' ) ); ?>');
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Save the meta box
     *
     * @param int $post_id Post ID.
     */
    public function save_meta_box( $post_id ) {
        if ( ! $this->verify_nonce( 'campaign_details_nonce', 'campaign_details' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $fields = $this->get_fields();
        // var_dump(is_array($_POST['allow_custom_donation_amounts']));die;

        // Save regular fields
        foreach ( $fields['regular'] as $field_id => $field ) {
            if ( isset( $_POST[ $field_id ] ) ) {
                $value = $_POST[ $field_id ];
                $value = sanitize_text_field( wp_unslash( $value ) );

                update_post_meta(
                    $post_id,
                    '_' . $field_id,
                    $value
                );
            } else {
                update_post_meta(
                    $post_id,
                    '_' . $field_id,
                    ''
                );
            }
        }
        
        // Save advanced fields
        foreach ( $fields['advanced'] as $field_id => $field ) {
            if ( isset( $_POST[ $field_id ] ) ) {

                $value = $_POST[ $field_id ];

                // if value is an array, then we need to save each value as a separate post meta
                if ( is_array( $value ) ) {
                    $value = serialize( $value );
                } else {
                    $value = sanitize_text_field( wp_unslash( $_POST[ $field_id ] ) );
                }

                update_post_meta(
                    $post_id,
                    '_' . $field_id,
                    $value
                );
            } else {
                update_post_meta(
                    $post_id,
                    '_' . $field_id,
                    ''
                );
            }
        }
    }
} 