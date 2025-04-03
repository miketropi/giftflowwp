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
    }
} 