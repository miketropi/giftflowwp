<?php
/**
 * Donor Post Type Class
 *
 * @package GiftFlowWp
 * @subpackage Admin
 */

namespace GiftFlowWp\Admin\PostTypes;

/**
 * Donor Post Type Class
 */
class Donor extends Base_Post_Type {
    /**
     * Initialize the donor post type
     */
    public function __construct() {
        

        parent::__construct();
    }

    protected function init_post_type() {
        $this->post_type = 'donor';
        $this->labels = array(
            'name'                  => _x( 'Donors', 'Post type general name', 'giftflowwp' ),
            'singular_name'         => _x( 'Donor', 'Post type singular name', 'giftflowwp' ),
            'menu_name'            => _x( 'Donors', 'Admin Menu text', 'giftflowwp' ),
            'name_admin_bar'       => _x( 'Donor', 'Add New on Toolbar', 'giftflowwp' ),
            'add_new'              => __( 'Add New', 'giftflowwp' ),
            'add_new_item'         => __( 'Add New Donor', 'giftflowwp' ),
            'new_item'             => __( 'New Donor', 'giftflowwp' ),
            'edit_item'            => __( 'Edit Donor', 'giftflowwp' ),
            'view_item'            => __( 'View Donor', 'giftflowwp' ),
            'all_items'            => __( 'All Donors', 'giftflowwp' ),
            'search_items'         => __( 'Search Donors', 'giftflowwp' ),
            'not_found'            => __( 'No donors found.', 'giftflowwp' ),
            'not_found_in_trash'   => __( 'No donors found in Trash.', 'giftflowwp' ),
        );

        $this->args = array(
            'labels'             => $this->labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'giftflowwp-dashboard',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'donor' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', ),
            'menu_icon'          => 'dashicons-groups',
            'show_in_rest'       => true,
        );

        // Add custom columns
        add_filter( 'manage_donor_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_donor_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
        add_filter( 'manage_edit-donor_sortable_columns', array( $this, 'set_sortable_columns' ) );
        
        // Add filters
        add_action( 'restrict_manage_posts', array( $this, 'add_user_filter' ) );
        add_filter( 'parse_query', array( $this, 'filter_donors' ) );
    }

    /**
     * Set custom columns for donor post type
     *
     * @param array $columns Array of column names
     * @return array Modified array of column names
     */
    public function set_custom_columns( $columns ) {
        $new_columns = array();
        
        foreach ( $columns as $key => $value ) {
            if ( $key === 'title' ) {
                $new_columns['title'] = __( 'Donor ID', 'giftflowwp' );
                $new_columns['full_name'] = __( 'Full Name', 'giftflowwp' );
                $new_columns['email'] = __( 'Email', 'giftflowwp' );
                $new_columns['phone'] = __( 'Phone', 'giftflowwp' );
                $new_columns['address'] = __( 'Address', 'giftflowwp' );
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
            case 'full_name':
                $first_name = get_post_meta( $post_id, '_first_name', true );
                $last_name = get_post_meta( $post_id, '_last_name', true );
                echo esc_html( $first_name . ' ' . $last_name );
                break;

            case 'email':
                $email = get_post_meta( $post_id, '_email', true );
                echo esc_html( $email );
                break;

            case 'phone':
                $phone = get_post_meta( $post_id, '_phone', true );
                echo esc_html( $phone );
                break;

            case 'address':
                $address = get_post_meta( $post_id, '_address', true );
                $city = get_post_meta( $post_id, '_city', true );
                $state = get_post_meta( $post_id, '_state', true );
                $postal_code = get_post_meta( $post_id, '_postal_code', true );
                $country = get_post_meta( $post_id, '_country', true );
                
                $full_address = array();
                if ( $address ) $full_address[] = $address;
                if ( $city ) $full_address[] = $city;
                if ( $state ) $full_address[] = $state;
                if ( $postal_code ) $full_address[] = $postal_code;
                if ( $country ) $full_address[] = $country;
                
                echo esc_html( implode( ', ', $full_address ) );
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
        $columns['full_name'] = 'full_name';
        $columns['email'] = 'email';
        return $columns;
    }

    /**
     * Add user filter dropdown
     */
    public function add_user_filter() {
        global $typenow;
        
        if ( $typenow === 'donor' ) {
            $selected = isset( $_GET['donor_user'] ) ? $_GET['donor_user'] : '';
            
            // Get all users who have associated donor records
            global $wpdb;
            $users = $wpdb->get_results( "
                SELECT DISTINCT u.ID, u.display_name, u.user_email
                FROM {$wpdb->users} u
                INNER JOIN {$wpdb->postmeta} pm ON u.user_email = pm.meta_value
                WHERE pm.meta_key = '_email'
                AND pm.post_id IN (
                    SELECT ID FROM {$wpdb->posts} 
                    WHERE post_type = 'donor' 
                    AND post_status = 'publish'
                )
                ORDER BY u.display_name ASC
            " );
            
            if ( !empty( $users ) ) {
                echo '<select name="donor_user">';
                echo '<option value="">' . __( 'All Users', 'giftflowwp' ) . '</option>';
                
                foreach ( $users as $user ) {
                    $user_label = $user->display_name . ' (' . $user->user_email . ')';
                    $selected_attr = selected( $selected, $user->ID, false );
                    echo '<option value="' . esc_attr( $user->ID ) . '" ' . $selected_attr . '>' . esc_html( $user_label ) . '</option>';
                }
                
                echo '</select>';
            }
        }
    }

    /**
     * Filter donors by user
     *
     * @param WP_Query $query The WP_Query instance
     */
    public function filter_donors( $query ) {
        global $pagenow, $typenow;
        
        if ( $pagenow === 'edit.php' && $typenow === 'donor' && isset( $_GET['donor_user'] ) && $_GET['donor_user'] !== '' ) {
            $user_id = intval( $_GET['donor_user'] );
            
            // Get the user's email
            $user = get_user_by( 'ID', $user_id );
            if ( $user ) {
                $user_email = $user->user_email;
                
                // Filter by email meta field
                $query->set( 'meta_key', '_email' );
                $query->set( 'meta_value', $user_email );
            }
        }
    }
} 