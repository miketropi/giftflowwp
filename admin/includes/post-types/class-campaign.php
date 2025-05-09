<?php
/**
 * Campaign Post Type Class
 *
 * @package GiftFlowWp
 * @subpackage Admin
 */

namespace GiftFlowWp\Admin\PostTypes;

/**
 * Campaign Post Type Class
 */
class Campaign extends Base_Post_Type {
    /**
     * Initialize the campaign post type
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Initialize post type properties
     */
    protected function init_post_type() {
        $this->post_type = 'campaign';
        $this->labels = array(
            'name'                  => _x( 'Campaigns', 'Post type general name', 'giftflowwp' ),
            'singular_name'         => _x( 'Campaign', 'Post type singular name', 'giftflowwp' ),
            'menu_name'            => _x( 'Campaigns', 'Admin Menu text', 'giftflowwp' ),
            'name_admin_bar'       => _x( 'Campaign', 'Add New on Toolbar', 'giftflowwp' ),
            'add_new'              => __( 'Add New', 'giftflowwp' ),
            'add_new_item'         => __( 'Add New Campaign', 'giftflowwp' ),
            'new_item'             => __( 'New Campaign', 'giftflowwp' ),
            'edit_item'            => __( 'Edit Campaign', 'giftflowwp' ),
            'view_item'            => __( 'View Campaign', 'giftflowwp' ),
            'all_items'            => __( 'All Campaigns', 'giftflowwp' ),
            'search_items'         => __( 'Search Campaigns', 'giftflowwp' ),
            'not_found'            => __( 'No campaigns found.', 'giftflowwp' ),
            'not_found_in_trash'   => __( 'No campaigns found in Trash.', 'giftflowwp' ),
        );

        $this->args = array(
            'labels'             => $this->labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'giftflowwp-dashboard',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'campaign' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'menu_icon'          => 'dashicons-megaphone',
            'show_in_rest'       => true,
        );

        // Define custom taxonomies
        $this->taxonomies = array(
            array(
                'name' => 'campaign-tax',
                'args' => array(
                    'hierarchical'      => true,
                    'labels'            => array(
                        'name'              => _x( 'Campaign Categories', 'taxonomy general name', 'giftflowwp' ),
                        'singular_name'     => _x( 'Campaign Category', 'taxonomy singular name', 'giftflowwp' ),
                        'search_items'      => __( 'Search Campaign Categories', 'giftflowwp' ),
                        'all_items'         => __( 'All Campaign Categories', 'giftflowwp' ),
                        'parent_item'       => __( 'Parent Campaign Category', 'giftflowwp' ),
                        'parent_item_colon' => __( 'Parent Campaign Category:', 'giftflowwp' ),
                        'edit_item'         => __( 'Edit Campaign Category', 'giftflowwp' ),
                        'update_item'       => __( 'Update Campaign Category', 'giftflowwp' ),
                        'add_new_item'      => __( 'Add New Campaign Category', 'giftflowwp' ),
                        'new_item_name'     => __( 'New Campaign Category Name', 'giftflowwp' ),
                        'menu_name'         => __( 'Campaign Categories', 'giftflowwp' ),
                    ),
                    'show_ui'           => true,
                    'show_admin_column' => true,
                    'query_var'         => true,
                    'rewrite'           => array( 'slug' => 'campaign-tax' ),
                    'show_in_rest'      => true, 
                ),
            ),
        );

        // Define custom admin columns
        $this->admin_columns = array(
            'goal_amount' => __( 'Goal Amount', 'giftflowwp' ),
            'raised_amount' => __( 'Raised Amount', 'giftflowwp' ),
            'start_date'  => __( 'Start Date', 'giftflowwp' ),
            'end_date'    => __( 'End Date', 'giftflowwp' ),
            'status'      => __( 'Status', 'giftflowwp' ),
        );

        // Define sortable columns
        $this->sortable_columns = array(
            'goal_amount',
            'raised_amount',
            'start_date',
            'end_date',
            'status',
        );

        // Add filter to highlight parent menu when on taxonomy page
        add_filter( 'parent_file', array( $this, 'highlight_parent_menu' ) );
        add_filter( 'submenu_file', array( $this, 'highlight_submenu' ) );

        // Register submenu page for campaign taxonomy
        add_action( 'admin_menu', array( $this, 'register_campaign_taxonomy_submenu' ) );
    }

    /**
     * Render custom column content
     *
     * @param string $column The column name.
     * @param int $post_id The post ID.
     */
    public function render_custom_columns( $column, $post_id ) {
        if ( ! isset( $this->admin_columns[$column] ) ) {
            return;
        }

        // Get the meta key for this column
        $meta_key = '_' . $column;
        $meta_value = get_post_meta( $post_id, $meta_key, true );

        // if a raised amount column, get the raised amount
        if ( $column === 'raised_amount' ) {
            $meta_value = giftflowwp_get_campaign_raised_amount( $post_id );
        }

        // Default display for empty values
        if ( empty( $meta_value ) ) {
            echo '—';
            return;
        }

        // Special handling for different column types
        switch ( $column ) {
            case 'goal_amount':
                // get currency symbol
                // $currency_symbol = giftflowwp_get_currency_symbol(giftflowwp_get_current_currency());
                // echo esc_html( $currency_symbol . number_format( $meta_value, 2 ) );

                echo giftflowwp_render_currency_formatted_amount($meta_value);
                break;
                
            case 'raised_amount':
                // get currency symbol
                // $currency_symbol = giftflowwp_get_currency_symbol(giftflowwp_get_current_currency());
                // echo esc_html( $currency_symbol . number_format( $meta_value, 2 ) );

                echo giftflowwp_render_currency_formatted_amount($meta_value);
                
                echo sprintf( ' (%s%s)', giftflowwp_get_campaign_progress_percentage( $post_id ), '%' );
                break;
                
            case 'start_date':
            case 'end_date':
                echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $meta_value ) ) );
                break;
                
            case 'status':
                $status_class = '';
                
                switch ( $meta_value ) {
                    case 'active':
                        $status_text = __( 'Active', 'giftflowwp' );
                        $status_class = 'status-active';
                        break;
                    case 'completed':
                        $status_text = __( 'Completed', 'giftflowwp' );
                        $status_class = 'status-completed';
                        break;
                    // pending
                    case 'pending':
                        $status_text = __( 'Pending', 'giftflowwp' );
                        $status_class = 'status-pending';
                        break;
                    default:
                        $status_text = __( 'Unknown', 'giftflowwp' );
                        $status_class = 'status-unknown';
                }
                
                echo '<span class="campaign-status ' . esc_attr( $status_class ) . '">' . esc_html( $status_text ) . '</span>';
                break;
                
            default:
                // Default display for other columns
                echo esc_html( $meta_value );
        }
    }

    /**
     * Register the campaign taxonomy submenu
     */
    public function register_campaign_taxonomy_submenu() {
        // add submenu page for campaign taxonomy
        add_submenu_page(
            'giftflowwp-dashboard',
            __( 'Campaign Categories', 'giftflowwp' ),
            __( 'Campaign Categories', 'giftflowwp' ),
            'manage_options',
            'edit-tags.php?taxonomy=campaign-tax&post_type=campaign',
            null,
            20
        );
    }

    /**
     * Highlight the parent menu when on the campaign taxonomy page
     *
     * @param string $parent_file The parent file.
     * @return string The modified parent file.
     */
    public function highlight_parent_menu( $parent_file ) {
        global $current_screen;
        
        if ( $current_screen->taxonomy === 'campaign-tax' ) {
            $parent_file = 'giftflowwp-dashboard';
        }
        
        return $parent_file;
    }

    /**
     * Highlight the submenu when on the campaign taxonomy page
     *
     * @param string $submenu_file The submenu file.
     * @return string The modified submenu file.
     */
    public function highlight_submenu( $submenu_file ) {
        global $current_screen;
        
        if ( $current_screen->taxonomy === 'campaign-tax' ) {
            $submenu_file = 'edit-tags.php?taxonomy=campaign-tax&post_type=campaign';
        }
        
        return $submenu_file;
    }
} 