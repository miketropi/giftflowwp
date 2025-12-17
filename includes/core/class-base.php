<?php
/**
 * Base class for GiftFlow
 *
 * @package GiftFlow
 * @subpackage Core
 */

namespace GiftFlow\Core;

/**
 * Base class that other classes can extend
 */
class Base {
    /**
     * Plugin version
     *
     * @var string
     */
    protected $version;

    /**
     * Plugin directory path
     *
     * @var string
     */
    protected $plugin_dir;

    /**
     * Plugin directory URL
     *
     * @var string
     */
    protected $plugin_url;

    /**
     * Constructor
     */
    public function __construct() {
        $this->version = GIFTFLOW_VERSION;
        $this->plugin_dir = GIFTFLOW_PLUGIN_DIR;
        $this->plugin_url = GIFTFLOW_PLUGIN_URL;
    }

    /**
     * Get plugin version
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Get plugin directory path
     *
     * @return string
     */
    public function get_plugin_dir() {
        return $this->plugin_dir;
    }

    /**
     * Get plugin directory URL
     *
     * @return string
     */
    public function get_plugin_url() {
        return $this->plugin_url;
    }
} 