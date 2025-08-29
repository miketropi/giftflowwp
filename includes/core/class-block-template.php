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
                'title' => 'Campaign Archive',
                'description' => 'A template for the campaign archive page.',
                'postTypes' => array( 'page' ),
                'template' => 'archive-campaign'
            ),
            'taxonomy-campaign-tax' => array(
                'title' => 'Category Campaign Archive',
                'description' => 'A template for the category campaign archive page.',
                'postTypes' => array( 'campaign' ),
                'categories' => array( 'campaign-tax' ),
                'template' => 'category-campaign-archive'
            ),
            'single-campaign' => array(
                'title' => 'Single Campaign',
                'description' => 'A template for the single campaign page.',
                'postTypes' => array( 'campaign' ),
                'template' => 'single-campaign'
            ),
            'donor-dashboard' => array(
                'title' => 'Donor Dashboard',
                'description' => 'A template for the donor dashboard page.',
                'postTypes' => array( 'page' ),
                'template' => 'donor-dashboard'
            ),
            'thank-you' => array(
                'title' => 'Thank You',
                'description' => 'A template for the thank you page.',
                'postTypes' => array( 'page' ),
                'template' => 'thank-you'
            )
        );

        foreach ( $templates as $slug => $template ) {

            $content = file_get_contents(GIFTFLOWWP_PLUGIN_DIR . 'block-templates/' . $template['template'] . '.html');
            $template['content'] = apply_filters('giftflowwp_block_template_content', $content, $template);

            register_block_template(
                'giftflowwp//' . $slug,
                $template
                // array(
                //     'title' => $template['title'],
                //     'description' => $template['description'] ?? '',
                //     'postTypes' => $template['postTypes'],
                //     'taxonomies' => $template['taxonomies'] ?? '',
                //     'template' => $template['template'],
                //     'content' => apply_filters('giftflowwp_block_template_content', $content, $template)
                // )
            );
        }
    }
}
