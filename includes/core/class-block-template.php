<?php
/**
 * Block Template Handler
 *
 * @package GiftflowWP
 */

namespace GiftflowWP\Core;

/**
 * Class Block_Template
 *
 * Handles registration of block templates for the theme.
 */
class Block_Template {

    /**
     * Initialize the class
     */
    public function __construct() {
        // add_action( 'init', array( $this, 'register_block_templates' ) );
        $this->register_block_templates();
    }

    /**
     * Register block templates
     */
    public function register_block_templates() {
        

        // Register templates for pages
        $templates = array(
            'archive-campaign' => array(
                'title' => esc_html__('Campaign Archive', 'giftflowwp'),
                'description' => esc_html__('A template for the campaign archive page.', 'giftflowwp'),
                // 'post_types' => array( 'page' ),
                'template' => 'archive-campaign'
            ),
            'taxonomy-campaign-tax' => array(
                'title' => esc_html__('Category Campaign Archive', 'giftflowwp'),
                'description' => esc_html__('A template for the category campaign archive page.', 'giftflowwp'),
                'template' => 'category-campaign-archive'
            ),
            'single-campaign' => array(
                'title' => esc_html__('Single Campaign', 'giftflowwp'),
                'description' => esc_html__('A template for the single campaign page.', 'giftflowwp'),
                'post_types' => array( 'campaign' ),
                'template' => 'single-campaign'
            ),
            'donor-account' => array(
                'title' => esc_html__('Donor Account', 'giftflowwp'),
                'description' => esc_html__('A template for the donor account page.', 'giftflowwp'),
                'post_types' => array( 'page' ),
                'template' => 'donor-account'
            ),
            'thank-donor' => array(
                'title' => esc_html__('Thank Donor', 'giftflowwp'),
                'description' => esc_html__('A template for the thank donor page.', 'giftflowwp'),
                'post_types' => array( 'page' ),
                'template' => 'thank-donor'
            )
        );

        foreach ( $templates as $slug => $template ) {

            $content = file_get_contents(GIFTFLOWWP_PLUGIN_DIR . 'block-templates/' . $template['template'] . '.html');
            $template['content'] = apply_filters('giftflowwp_block_template_content', $content, $template);

            register_block_template(
                'giftflowwp//' . $slug,
                $template
            );
        }
    }
}
