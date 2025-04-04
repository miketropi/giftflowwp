<?php
/**
 * Template loader class
 *
 * @package GiftFlowWP
 * @since 1.0.0
 */

namespace GiftFlowWP\Frontend;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Template loader class
 */
class Template {
    /**
     * Plugin template directory
     *
     * @var string
     */
    private $plugin_template_dir;

    /**
     * Constructor
     */
    public function __construct() {
        $this->plugin_template_dir = GIFTFLOWWP_PLUGIN_DIR . 'templates/';
    }

    /**
     * Get template path
     *
     * @param string $template_name Template name.
     * @param string $template_path Optional. Template path. Default empty.
     * @return string
     */
    public function get_template_path($template_name, $template_path = '') {
        // Allow theme developers to filter the template path
        $template_path = apply_filters('giftflowwp_template_path', $template_path, $template_name);

        // Check theme directory first
        $theme_template = locate_template([
            $template_path . $template_name,
            'giftflowwp/' . $template_name,
        ]);

        if ($theme_template) {
            return $theme_template;
        }

        // Return plugin template path
        return $this->plugin_template_dir . $template_path . $template_name;
    }

    /**
     * Load template
     *
     * @param string $template_name Template name.
     * @param array  $args Optional. Arguments to pass to template. Default empty array.
     * @param string $template_path Optional. Template path. Default empty.
     */
    public function load_template($template_name, $args = [], $template_path = '') {
        $template_file = $this->get_template_path($template_name, $template_path);

        // Allow theme developers to filter the template file
        $template_file = apply_filters('giftflowwp_template_file', $template_file, $template_name, $args);

        if (!file_exists($template_file)) {
            _doing_it_wrong(
                __FUNCTION__,
                sprintf(
                    /* translators: %s: Template file name */
                    __('Template file %s does not exist.', 'giftflowwp'),
                    '<code>' . $template_file . '</code>'
                ),
                '1.0.0'
            );
            return;
        }

        // Extract args if they exist
        if (!empty($args) && is_array($args)) {
            extract($args); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        }

        // Allow theme developers to do something before template is loaded
        do_action('giftflowwp_before_template_load', $template_name, $template_file, $args);

        include $template_file;

        // Allow theme developers to do something after template is loaded
        do_action('giftflowwp_after_template_load', $template_name, $template_file, $args);
    }

    /**
     * Get template part
     *
     * @param string $slug Template slug.
     * @param string $name Optional. Template name. Default empty.
     * @param array  $args Optional. Arguments to pass to template. Default empty array.
     */
    public function get_template_part($slug, $name = '', $args = []) {
        $template = '';

        // Look in yourtheme/slug-name.php and yourtheme/giftflowwp/slug-name.php
        if ($name) {
            $template = locate_template([
                "{$slug}-{$name}.php",
                "giftflowwp/{$slug}-{$name}.php",
            ]);
        }

        // Get default slug-name.php
        if (!$template && $name && file_exists($this->plugin_template_dir . "{$slug}-{$name}.php")) {
            $template = $this->plugin_template_dir . "{$slug}-{$name}.php";
        }

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/giftflowwp/slug.php
        if (!$template) {
            $template = locate_template([
                "{$slug}.php",
                "giftflowwp/{$slug}.php",
            ]);
        }

        // Allow 3rd party plugins to filter template file from their plugin
        $template = apply_filters('giftflowwp_get_template_part', $template, $slug, $name);

        if ($template) {
            $this->load_template($template, $args);
        }
    }
}
