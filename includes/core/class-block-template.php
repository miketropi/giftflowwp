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
        add_action( 'init', array( $this, 'register_block_templates' ) );
    }

    /**
     * Register block templates
     */
    public function register_block_templates() {
        

        // Register templates for pages
        $templates = array(
            'archive-campaign' => array(
                'title' => 'Campaign Archive',
                'postTypes' => array( 'page' ),
                'template' => 'archive-campaign'
            ),
            'single-campaign' => array(
                'title' => 'Single Campaign',
                'postTypes' => array( 'campaign' ),
                'template' => 'single-campaign'
            ),
            'donor-dashboard' => array(
                'title' => 'Donor Dashboard',
                'postTypes' => array( 'page' ),
                'template' => 'donor-dashboard'
            ),
            'thank-you' => array(
                'title' => 'Thank You',
                'postTypes' => array( 'page' ),
                'template' => 'thank-you'
            )
        );

        foreach ( $templates as $slug => $template ) {

            $content = file_get_contents(GIFTFLOWWP_PLUGIN_DIR . 'block-templates/' . $template['template'] . '.html');
            // add hook filter to the content
            $content = apply_filters('giftflowwp_block_template_content', $content, $template);

            register_block_template(
                'giftflowwp//' . $slug,
                array(
                    'title' => $template['title'],
                    'postTypes' => $template['postTypes'],
                    'template' => $template['template'],
                    'content' => $content
                )
            );
        }
    }
}
