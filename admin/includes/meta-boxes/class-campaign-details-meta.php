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
        $preset_donation_amounts = explode(',', giftflowwp_get_preset_donation_amounts());
        $preset_donation_amounts = array_map(function($amount) {
            return array(
                'amount' => (int)trim($amount),
            );
        }, $preset_donation_amounts);
        // var_dump($preset_donation_amounts);die;

        return array(
            'regular' => array(
                'goal_amount' => array(
                    'label' => __( 'Goal Amount', 'giftflowwp' ),
                    'type'  => 'currency',
                    'step'  => '1',
                    'min'   => '0',
                    // 'currency_symbol' => '$',
                    // description
                    'description' => __( 'Enter the goal amount for the campaign', 'giftflowwp' ),
                ),
                'start_date' => array(
                    'label' => __( 'Start Date', 'giftflowwp' ),
                    'type'  => 'datetime',
                    // 'date_format' => 'Y-m-d',
                    'description' => __( 'Select the start date for the campaign 📅.', 'giftflowwp' ),
                ),
                'end_date' => array(
                    'label' => __( 'End Date', 'giftflowwp' ),
                    'type'  => 'datetime',
                    // 'date_format' => 'Y-m-d',
                    'description' => __( 'Select the end date for the campaign 📅, If empty, the campaign will be active indefinitely ♾️', 'giftflowwp' ),
                ),
                'status' => array(
                    'label'   => __( 'Status', 'giftflowwp' ),
                    'type'    => 'select',
                    'options' => array(
                        'active'   => __( 'Active', 'giftflowwp' ),
                        'completed' => __( 'Completed', 'giftflowwp' ),
                        // closed
                        'closed' => __( 'Closed', 'giftflowwp' ),
                        // pending
                        'pending' => __( 'Pending', 'giftflowwp' ), 
                    ),
                    'description' => '
                        <p>' . __( 'Select the status for the campaign donations:', 'giftflowwp' ) . '</p>
                        <ul>
                            <li><strong>' . __( 'Active', 'giftflowwp' ) . '</strong> 🟢: ' . __( 'Campaign is open for donations', 'giftflowwp' ) . ' 💰</li>
                            <li><strong>' . __( 'Completed', 'giftflowwp' ) . '</strong> 🏆: ' . __( 'Campaign is closed and all donations are collected  ✅, but donations are still allowed.', 'giftflowwp' ) . '</li>
                            <li><strong>' . __( 'Closed', 'giftflowwp' ) . '</strong> 🔒: ' . __( 'Campaign is closed and no more donations are allowed', 'giftflowwp' ) . ' ⛔</li>
                            <li><strong>' . __( 'Pending', 'giftflowwp' ) . '</strong> ⏳: ' . __( 'Campaign donations are pending and auto-activated when start date is reached', 'giftflowwp' ) . ' 📅</li>
                        </ul>',
                ),
                // on / off one-time donation
                'one_time' => array(
                    'label' => __( 'One-Time', 'giftflowwp' ),
                    'type'  => 'switch',
                    'description' => __( 'Allow one-time donations', 'giftflowwp' ),
                    'default' => 1,
                ),
                // on / off recurring
                'recurring' => array(
                    'label' => __( 'Recurring', 'giftflowwp' ),
                    'type'  => 'switch',
                    'description' => __( 'Allow recurring donations', 'giftflowwp' ),
                    'default' => 0,
                ),
                // select recurring interval
                'recurring_interval' => array(
                    'label' => __( 'Recurring Interval', 'giftflowwp' ),
                    'type'  => 'select',
                    'options' => array(
                        'daily' => __( 'Daily', 'giftflowwp' ),
                        'weekly' => __( 'Weekly', 'giftflowwp' ),
                        'monthly' => __( 'Monthly', 'giftflowwp' ),
                        'quarterly' => __( 'Quarterly', 'giftflowwp' ),
                        'yearly' => __( 'Yearly', 'giftflowwp' ),
                    ),
                    'description' => __( 'Select the recurring interval for recurring donations', 'giftflowwp' ),
                    'default' => 'monthly',
                )

            ),
            'advanced' => array(
                // repeater preset donation amounts ($10, $25, $50, $100, $250)
                'preset_donation_amounts' => array(
                    'label' => __( 'Preset Donation Amounts', 'giftflowwp' ),
                    'type'  => 'repeater',
                    'default' => $preset_donation_amounts,
                    'description' => __( 'Enter the preset donation amounts for the campaign', 'giftflowwp' ),
                    'repeater_settings' => array(
                        'fields' => array(
                            'amount' => array(
                                'label' => __( 'Amount', 'giftflowwp' ),
                                'type'  => 'currency',
                                'step'  => '1',
                                'min'   => '0',
                                // 'value' => 10,
                                'description' => __( 'Enter the amount for the donation', 'giftflowwp' ),
                                // 'currency_symbol' => '$',
                            ),
                        ),
                    ),
                ),

                // On / Off switch allow custom donation amounts
                'allow_custom_donation_amounts' => array(
                    'label' => __( 'Allow Custom Donation Amounts', 'giftflowwp' ),
                    'type'  => 'switch',
                    'default' => 1,
                    'description' => __( 'Allow users to enter their own donation amounts', 'giftflowwp' ),
                ),

                'location' => array(
                    'label' => __( 'Location', 'giftflowwp' ),
                    'type'  => 'textfield',
                    'default' => 'United States',
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
        
        ob_start();
        ?>
        <div class="campaign-details-tabs">
            <div class="nav-tab-wrapper">
                <a href="#general-tab" class="nav-tab nav-tab-active"><?php esc_html_e( 'General', 'giftflowwp' ); ?></a>
                <a href="#advanced-tab" class="nav-tab"><?php esc_html_e( 'Advanced', 'giftflowwp' ); ?></a>
            </div>
            
            <div id="general-tab" class="tab-content active">
                <?php
                foreach ( $fields['regular'] as $field_id => $field_args ) {
                    if ( metadata_exists( 'post', $post->ID, '_' . $field_id ) ) {
                        $value = get_post_meta( $post->ID, '_' . $field_id, true );
                    } else {
                        $value = $field_args['default'] ?? '';
                    }

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
                    
                    echo $field->render();
                }
                ?>
            </div>
            
            <div id="advanced-tab" class="tab-content">
                <?php
                foreach ( $fields['advanced'] as $field_id => $field_args ) {
                    if ( metadata_exists( 'post', $post->ID, '_' . $field_id ) ) {
                        $value = get_post_meta( $post->ID, '_' . $field_id, true );
                        
                        if ( $field_args['type'] == 'repeater' && $value ) {
                            $value = unserialize( $value );
                        }
                    } else {
                        $value = $field_args['default'] ?? '';
                    }

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
                    
                    echo $field->render();
                }
                ?>
            </div>
        </div>

        <style>
            .campaign-details-tabs {
                margin-top: 10px;
            }
            .tab-content {
                display: none;
                padding: 20px 0;
            }
            .tab-content.active {
                display: block;
            }
            .nav-tab-wrapper {
                margin-bottom: 20px;
            }
            .nav-tab {
                padding: 8px 12px;
                text-decoration: none;
                border: 1px solid #ccc;
                background: #f1f1f1;
                margin-right: 5px;
                transform: translateY(1px);
                -webkit-transform: translateY(1px);
            }
            .nav-tab-active {
                background: #fff;
                border-bottom: 1px solid #fff;
            }
        </style>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.nav-tab').on('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs and content
                    $('.nav-tab').removeClass('nav-tab-active');
                    $('.tab-content').removeClass('active');
                    
                    // Add active class to clicked tab
                    $(this).addClass('nav-tab-active');
                    
                    // Show corresponding content
                    var target = $(this).attr('href');
                    $(target).addClass('active');
                });
            });
        </script>
        <?php
        echo ob_get_clean();
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