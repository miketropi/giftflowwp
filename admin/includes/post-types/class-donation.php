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
            'supports'           => array( 'title' ),
            'menu_icon'          => 'dashicons-money-alt',
            'show_in_rest'       => true,
        );

        // Add custom columns
        add_filter( 'manage_donation_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_donation_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
        add_filter( 'manage_edit-donation_sortable_columns', array( $this, 'set_sortable_columns' ) );
        
        // Add filters
        add_action( 'restrict_manage_posts', array( $this, 'add_status_filter' ) );
        add_action( 'restrict_manage_posts', array( $this, 'add_donor_filter' ) );
        add_action( 'restrict_manage_posts', array( $this, 'add_campaign_filter' ) );
        add_filter( 'parse_query', array( $this, 'filter_donations' ) );
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
                // get currency symbol
                // $currency_symbol = giftflowwp_get_currency_symbol(giftflowwp_get_current_currency());
                // $amount = get_post_meta( $post_id, '_amount', true );
                // echo esc_html( $currency_symbol . number_format( floatval( $amount ), 2 ) );
                $amount = get_post_meta( $post_id, '_amount', true );
                echo giftflowwp_render_currency_formatted_amount($amount);
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

    /**
     * Add status filter dropdown
     */
    public function add_status_filter() {
        global $typenow;
        
        if ( $typenow === 'donation' ) {
            $selected = isset( $_GET['donation_status'] ) ? $_GET['donation_status'] : '';
            $statuses = array( 'pending', 'completed', 'failed', 'refunded' );
            
            echo '<select name="donation_status">';
            echo '<option value="">' . __( 'All Statuses', 'giftflowwp' ) . '</option>';
            
            foreach ( $statuses as $status ) {
                $status_label = ucfirst( $status );
                $selected_attr = selected( $selected, $status, false );
                echo '<option value="' . esc_attr( $status ) . '" ' . $selected_attr . '>' . esc_html( $status_label ) . '</option>';
            }
            
            echo '</select>';
        }
    }

    /**
     * Add donor filter dropdown
     */
    public function add_donor_filter() {
        global $typenow;
        
        if ( $typenow === 'donation' ) {
            $selected = isset( $_GET['donation_donor'] ) ? $_GET['donation_donor'] : '';
            
            // Get all unique donors from donations
            global $wpdb;
            $donors = $wpdb->get_results( "
                SELECT DISTINCT pm.meta_value as donor_id, 
                       CONCAT(pm2.meta_value, ' ', pm3.meta_value) as donor_name
                FROM {$wpdb->postmeta} pm
                LEFT JOIN {$wpdb->postmeta} pm2 ON pm.meta_value = pm2.post_id AND pm2.meta_key = '_first_name'
                LEFT JOIN {$wpdb->postmeta} pm3 ON pm.meta_value = pm3.post_id AND pm3.meta_key = '_last_name'
                WHERE pm.meta_key = '_donor_id' 
                AND pm.meta_value != '' 
                AND pm.meta_value != '0'
                ORDER BY donor_name ASC
            " );
            
            echo '<select name="donation_donor">';
            echo '<option value="">' . __( 'All Donors', 'giftflowwp' ) . '</option>';
            
            foreach ( $donors as $donor ) {
                $donor_name = trim( $donor->donor_name );
                if ( empty( $donor_name ) ) {
                    $donor_name = __( 'Donor ID: ', 'giftflowwp' ) . $donor->donor_id;
                }
                $selected_attr = selected( $selected, $donor->donor_id, false );
                echo '<option value="' . esc_attr( $donor->donor_id ) . '" ' . $selected_attr . '>' . esc_html( $donor_name ) . '</option>';
            }
            
            echo '</select>';
        }
    }

    /**
     * Add campaign filter dropdown
     */
    public function add_campaign_filter() {
        global $typenow;
        
        if ( $typenow === 'donation' ) {
            $selected = isset( $_GET['donation_campaign'] ) ? $_GET['donation_campaign'] : '';
            
            // Get all unique campaigns from donations
            global $wpdb;
            $campaigns = $wpdb->get_results( "
                SELECT DISTINCT pm.meta_value as campaign_id, p.post_title as campaign_title
                FROM {$wpdb->postmeta} pm
                LEFT JOIN {$wpdb->posts} p ON pm.meta_value = p.ID
                WHERE pm.meta_key = '_campaign_id' 
                AND pm.meta_value != '' 
                AND pm.meta_value != '0'
                AND p.post_status = 'publish'
                ORDER BY campaign_title ASC
            " );
            
            echo '<select name="donation_campaign">';
            echo '<option value="">' . __( 'All Campaigns', 'giftflowwp' ) . '</option>';
            
            foreach ( $campaigns as $campaign ) {
                $campaign_title = !empty( $campaign->campaign_title ) ? $campaign->campaign_title : __( 'Campaign ID: ', 'giftflowwp' ) . $campaign->campaign_id;
                $selected_attr = selected( $selected, $campaign->campaign_id, false );
                echo '<option value="' . esc_attr( $campaign->campaign_id ) . '" ' . $selected_attr . '>' . esc_html( $campaign_title ) . '</option>';
            }
            
            echo '</select>';
        }
    }

    /**
     * Filter donations by status, donor, and campaign
     *
     * @param WP_Query $query The WP_Query instance
     */
    public function filter_donations( $query ) {
        global $pagenow, $typenow;
        
        if ( $pagenow === 'edit.php' && $typenow === 'donation' ) {
            $meta_query = array();
            
            // Filter by status
            if ( isset( $_GET['donation_status'] ) && $_GET['donation_status'] !== '' ) {
                $status = sanitize_text_field( $_GET['donation_status'] );
                $meta_query[] = array(
                    'key'     => '_status',
                    'value'   => $status,
                    'compare' => '='
                );
            }
            
            // Filter by donor
            if ( isset( $_GET['donation_donor'] ) && $_GET['donation_donor'] !== '' ) {
                $donor_id = intval( $_GET['donation_donor'] );
                $meta_query[] = array(
                    'key'     => '_donor_id',
                    'value'   => $donor_id,
                    'compare' => '='
                );
            }
            
            // Filter by campaign
            if ( isset( $_GET['donation_campaign'] ) && $_GET['donation_campaign'] !== '' ) {
                $campaign_id = intval( $_GET['donation_campaign'] );
                $meta_query[] = array(
                    'key'     => '_campaign_id',
                    'value'   => $campaign_id,
                    'compare' => '='
                );
            }
            
            // Apply meta query if we have filters
            if ( !empty( $meta_query ) ) {
                $query->set( 'meta_query', $meta_query );
            }
        }
    }
} 