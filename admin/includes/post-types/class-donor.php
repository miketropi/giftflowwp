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
    }
} 