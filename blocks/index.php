<?php 

class BlockLoader {
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
BlockLoader::load_blocks();


