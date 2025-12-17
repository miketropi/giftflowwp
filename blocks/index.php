<?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.InvalidPrefixPassed
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
/**
 * Block Loader
 */


class GiftFlowWp_Block_Loader {
    /**
     * Load all block files from subdirectories
     */
    public static function load_blocks() {
        foreach (glob(__DIR__ . '/*/block.php') as $block_file) {
            if (is_file($block_file)) {
                require_once $block_file;
            }
        }
    }
}

// Initialize the block loader
GiftFlowWp_Block_Loader::load_blocks();


