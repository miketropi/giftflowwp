<?php
/**
 * Donation Post Type Class
 *
 * @package GiftFlowWp
 * @subpackage Admin
 */

namespace GiftFlowWp\Admin\PostTypes;

/**
 * Donation Post Type Class
 */
class Donation extends Base_Post_Type {
    /**
     * Initialize the donation post type
     */
    public function __construct() {

        parent::__construct();
    }

    protected function init_post_type() {
        $this->post_type = 'donation';
        $this->labels = array(
            'name'                  => _x( 'Donations', 'Post type general name', 'giftflowwp' ),
            'singular_name'         => _x( 'Donation', 'Post type singular name', 'giftflowwp' ),
            'menu_name'            => _x( 'Donations', 'Admin Menu text', 'giftflowwp' ),
            'name_admin_bar'       => _x( 'Donation', 'Add New on Toolbar', 'giftflowwp' ),
            'add_new'              => __( 'Add New', 'giftflowwp' ),
            'add_new_item'         => __( 'Add New Donation', 'giftflowwp' ),
            'new_item'             => __( 'New Donation', 'giftflowwp' ),
            'edit_item'            => __( 'Edit Donation', 'giftflowwp' ),
            'view_item'            => __( 'View Donation', 'giftflowwp' ),
            'all_items'            => __( 'All Donations', 'giftflowwp' ),
            'search_items'         => __( 'Search Donations', 'giftflowwp' ),
            'not_found'            => __( 'No donations found.', 'giftflowwp' ),
            'not_found_in_trash'   => __( 'No donations found in Trash.', 'giftflowwp' ),
        );

        $this->args = array(
            'labels'             => $this->labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'giftflowwp-dashboard',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'donation' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', ),
            'menu_icon'          => 'dashicons-money-alt',
            'show_in_rest'       => true,
        );

        // Add custom columns
        add_filter( 'manage_donation_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_donation_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
        add_filter( 'manage_edit-donation_sortable_columns', array( $this, 'set_sortable_columns' ) );
    }

    /**
     * Set custom columns for donation post type
     *
     * @param array $columns Array of column names
     * @return array Modified array of column names
     */
    public function set_custom_columns( $columns ) {
        $new_columns = array();
        
        foreach ( $columns as $key => $value ) {
            if ( $key === 'title' ) {
                $new_columns['title'] = __( 'Donation ID', 'giftflowwp' );
                $new_columns['amount'] = __( 'Amount', 'giftflowwp' );
                $new_columns['payment_method'] = __( 'Payment Method', 'giftflowwp' );
                $new_columns['status'] = __( 'Status', 'giftflowwp' );
                $new_columns['donor'] = __( 'Donor', 'giftflowwp' );
                $new_columns['campaign'] = __( 'Campaign', 'giftflowwp' );
            } else {
                $new_columns[$key] = $value;
            }
        }
        
        return $new_columns;
    }

    /**
     * Render custom column content
     *
     * @param string $column Column name
     * @param int $post_id Post ID
     */
    public function render_custom_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'amount':
                $amount = get_post_meta( $post_id, '_amount', true );
                echo esc_html( '$' . number_format( floatval( $amount ), 2 ) );
                break;

            case 'payment_method':
                $payment_method = get_post_meta( $post_id, '_payment_method', true );
                echo esc_html( ucfirst( $payment_method ) );
                break;

            case 'status':
                $status = get_post_meta( $post_id, '_status', true );
                $status_class = 'status-' . sanitize_html_class( $status );
                echo '<span class="donation-status ' . esc_attr( $status_class ) . '">' . esc_html( ucfirst( $status ) ) . '</span>';
                break;

            case 'donor':
                $donor_id = get_post_meta( $post_id, '_donor_id', true );
                if ( $donor_id ) {
                    $donor = get_post( $donor_id );
                    if ( $donor ) {
                        $first_name = get_post_meta( $donor_id, '_first_name', true );
                        $last_name = get_post_meta( $donor_id, '_last_name', true );
                        $donor_name = $first_name . ' ' . $last_name;
                        echo '<a href="' . esc_url( get_edit_post_link( $donor_id ) ) . '">' . esc_html( $donor_name ) . '</a>';
                    }
                }
                break;

            case 'campaign':
                $campaign_id = get_post_meta( $post_id, '_campaign_id', true );
                if ( $campaign_id ) {
                    $campaign = get_post( $campaign_id );
                    if ( $campaign ) {
                        echo '<a href="' . esc_url( get_edit_post_link( $campaign_id ) ) . '">' . esc_html( $campaign->post_title ) . '</a>';
                    }
                }
                break;
        }
    }

    /**
     * Set sortable columns
     *
     * @param array $columns Array of sortable columns
     * @return array Modified array of sortable columns
     */
    public function set_sortable_columns( $columns ) {
        $columns['amount'] = 'amount';
        $columns['status'] = 'status';
        $columns['donor'] = 'donor';
        $columns['campaign'] = 'campaign';
        return $columns;
    }
} 