<?php
/**
 * Base Gateway class for GiftFlowWp
 *
 * This class provides a comprehensive foundation for creating payment gateways
 * with automatic registration, settings management, and asset enqueuing.
 *
 * @package GiftFlowWp
 * @subpackage Gateways
 * @since 1.0.0
 * @version 1.0.0
 * 
 * USAGE GUIDE:
 * ============
 * 
 * 1. CREATING A NEW GATEWAY:
 * --------------------------
 * ```php
 * namespace GiftFlowWp\Gateways;
 * 
 * class My_Gateway extends Gateway_Base {
 *     
 *     protected function init_gateway() {
 *         $this->id = 'my_gateway';
 *         $this->title = __('My Gateway', 'giftflowwp');
 *         $this->description = __('Accept payments via My Gateway', 'giftflowwp');
 *         $this->icon = 'path/to/icon.png';
 *         $this->order = 20;
 *         $this->supports = array('refunds', 'subscriptions');
 *         
 *         // Add scripts and styles
 *         $this->add_script('my-gateway-js', array(
 *             'src' => GIFTFLOWWP_PLUGIN_URL . 'assets/js/my-gateway.js',
 *             'deps' => array('jquery'),
 *             'frontend' => true,
 *             'localize' => array(
 *                 'name' => 'myGatewayData',
 *                 'data' => array('apiKey' => $this->get_setting('api_key'))
 *             )
 *         ));
 *     }
 *     
 *     protected function get_settings_fields() {
 *         return array(
 *             'enabled' => array(
 *                 'title' => __('Enable', 'giftflowwp'),
 *                 'type' => 'checkbox',
 *             ),
 *             'api_key' => array(
 *                 'title' => __('API Key', 'giftflowwp'),
 *                 'type' => 'password',
 *             ),
 *         );
 *     }
 *     
 *     public function process_payment($data, $donation_id = 0) {
 *         // Payment processing logic here
 *         return array('success' => true, 'transaction_id' => '123');
 *     }
 * }
 * ```
 * 
 * 2. INITIALIZING GATEWAYS:
 * -------------------------
 * ```php
 * // In your main plugin file or init hook
 * add_action('init', function() {
 *     Gateway_Base::init_gateways();
 * });
 * 
 * // Register gateways
 * add_action('giftflowwp_register_gateways', function() {
 *     new \GiftFlowWp\Gateways\Stripe_Gateway();
 *     new \GiftFlowWp\Gateways\PayPal_Gateway();
 *     new \GiftFlowWp\Gateways\My_Gateway();
 * });
 * ```
 * 
 * 3. GETTING GATEWAYS:
 * --------------------
 * ```php
 * // Get all registered gateways
 * $gateways = Gateway_Base::get_registered_gateways();
 * 
 * // Get specific gateway
 * $stripe = Gateway_Base::get_gateway('stripe');
 * if ($stripe && $stripe->is_enabled()) {
 *     $result = $stripe->process_payment($data, $donation_id);
 * }
 * ```
 * 
 * 4. AVAILABLE HOOKS & FILTERS:
 * -----------------------------
 * Actions:
 * - giftflowwp_register_gateways - Register custom gateways
 * - giftflowwp_gateway_init_hooks - After gateway hooks initialization
 * - giftflowwp_gateway_registered - After gateway registration
 * - giftflowwp_gateway_settings_saved - After settings saved
 * - giftflowwp_gateways_initialized - After all gateways initialized
 * - giftflowwp_gateway_enqueue_frontend_assets - Additional frontend assets
 * - giftflowwp_gateway_enqueue_admin_assets - Additional admin assets
 * 
 * Filters:
 * - giftflowwp_payment_gateways - Modify gateways list
 * - giftflowwp_gateway_settings_fields - Modify settings fields
 * - giftflowwp_gateway_settings_fields_{gateway_id} - Gateway-specific fields
 * - giftflowwp_gateway_save_settings - Filter settings before save
 * - giftflowwp_gateway_save_settings_{gateway_id} - Gateway-specific save
 * - giftflowwp_gateway_settings_template - Modify settings template path
 * - giftflowwp_gateway_should_enqueue_assets - Control asset loading
 * 
 * 5. ASSET MANAGEMENT:
 * --------------------
 * ```php
 * // Add scripts in init_gateway()
 * $this->add_script('my-script', array(
 *     'src' => 'path/to/script.js',
 *     'deps' => array('jquery'),
 *     'version' => '1.0.0',
 *     'frontend' => true,  // Load on frontend
 *     'admin' => false,    // Don't load in admin
 *     'in_footer' => true,
 *     'localize' => array(
 *         'name' => 'myData',
 *         'data' => array('key' => 'value')
 *     )
 * ));
 * 
 * // Add styles
 * $this->add_style('my-style', array(
 *     'src' => 'path/to/style.css',
 *     'deps' => array(),
 *     'frontend' => true,
 *     'admin' => true
 * ));
 * ```
 * 
 * 6. SETTINGS FIELD TYPES:
 * ------------------------
 * - text, password, email, url, textarea
 * - number, checkbox, select, multiselect
 * All fields are automatically sanitized based on type
 * 
 * 7. EXTENDING FUNCTIONALITY:
 * ---------------------------
 * ```php
 * // Add custom hooks in child class
 * protected function init_additional_hooks() {
 *     add_action('wp_ajax_my_gateway_webhook', array($this, 'handle_webhook'));
 *     add_filter('my_gateway_custom_filter', array($this, 'custom_filter'));
 * }
 * 
 * // Control asset loading
 * protected function should_enqueue_assets() {
 *     return is_page('donation') || parent::should_enqueue_assets();
 * }
 * ```
 */

namespace GiftFlowWp\Gateways;

use GiftFlowWp\Core\Base;

/**
 * Base class for payment gateways
 */
abstract class Gateway_Base extends Base {
    /**
     * Gateway ID
     *
     * @var string
     */
    protected $id;

    /**
     * Gateway title
     *
     * @var string
     */
    protected $title;

    /**
     * Gateway description
     *
     * @var string
     */
    protected $description;

    /**
     * Gateway icon URL
     *
     * @var string
     */
    protected $icon;

    /**
     * Gateway enabled status
     *
     * @var bool
     */
    protected $enabled = false;

    /**
     * Gateway settings
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Gateway supports
     *
     * @var array
     */
    protected $supports = array();

    /**
     * Order of gateway display
     *
     * @var int
     */
    protected $order = 10;

    /**
     * Gateway scripts
     *
     * @var array
     */
    protected $scripts = array();

    /**
     * Gateway styles
     *
     * @var array
     */
    protected $styles = array();

    /**
     * Static registry for all gateways
     *
     * @var array
     */
    private static $gateway_registry = array();

    /**
     * Initialize gateway
     */
    public function __construct() {
        parent::__construct();
        $this->init_gateway();
        $this->init_settings();
        $this->init_hooks();
        $this->register_gateway();
    }

    /**
     * Initialize gateway properties
     * Child classes should override this method
     */
    protected function init_gateway() {
        // Override in child classes
    }

    /**
     * Initialize gateway settings
     */
    protected function init_settings() {
        $opts = get_option('giftflowwp_payment_options', []); // all options of payment gateways
        $this->settings = $opts[$this->id]; // get_option('giftflowwp_gateway_' . $this->id, array());
        $this->enabled = isset($this->settings[$this->id . '_enabled']) ? $this->settings[$this->id . '_enabled'] == '1' : false;
    }

    /**
     * Initialize WordPress hooks
     */
    protected function init_hooks() {
        // Core gateway hooks
        add_filter('giftflowwp_payment_gateways', array($this, 'add_gateway_to_list'));
        // add_action('giftflowwp_settings_gateways', array($this, 'render_settings'));
        // add_action('giftflowwp_save_gateway_settings', array($this, 'save_settings'));
        
        // Asset hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Additional hooks for child classes
        $this->init_additional_hooks();
        
        // Allow third parties to add hooks
        do_action('giftflowwp_gateway_init_hooks', $this);
    }

    /**
     * Additional hooks for child classes
     */
    protected function init_additional_hooks() {
        // Override in child classes
    }

    /**
     * Register this gateway in the global registry
     */
    protected function register_gateway() {
        if (!empty($this->id)) {
            self::$gateway_registry[$this->id] = $this;
            
            // Fire action after gateway registration
            do_action('giftflowwp_gateway_registered', $this->id, $this);
        }
    }

    /**
     * Add gateway to the gateways list
     *
     * @param array $gateways List of gateways
     * @return array
     */
    public function add_gateway_to_list($gateways) {
        $gateways[$this->id] = array(
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'icon' => $this->icon,
            'enabled' => $this->enabled,
            'order' => $this->order,
            'supports' => $this->supports,
            'instance' => $this,
        );
        
        return $gateways;
    }

    /**
     * Render gateway settings
     */
    public function render_settings() {
        $fields = $this->get_settings_fields();
        
        // Allow filtering of settings fields
        $fields = apply_filters('giftflowwp_gateway_settings_fields', $fields, $this->id);
        $fields = apply_filters('giftflowwp_gateway_settings_fields_' . $this->id, $fields);
        
        include $this->get_settings_template();
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        if (!$this->enabled || !$this->should_enqueue_assets()) {
            return;
        }

        // Enqueue scripts
        foreach ($this->scripts as $handle => $script) {
            if (isset($script['frontend']) && $script['frontend']) {
                wp_enqueue_script(
                    $handle,
                    $script['src'],
                    $script['deps'] ?? array(),
                    $script['version'] ?? $this->version,
                    $script['in_footer'] ?? true
                );
                
                // Localize script if data provided
                if (isset($script['localize'])) {
                    wp_localize_script($handle, $script['localize']['name'], $script['localize']['data']);
                }
            }
        }

        // Enqueue styles
        foreach ($this->styles as $handle => $style) {
            if (isset($style['frontend']) && $style['frontend']) {
                wp_enqueue_style(
                    $handle,
                    $style['src'],
                    $style['deps'] ?? array(),
                    $style['version'] ?? $this->version,
                    $style['media'] ?? 'all'
                );
            }
        }
        
        // Allow additional frontend assets
        do_action('giftflowwp_gateway_enqueue_frontend_assets', $this->id);
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        if (!$this->enabled) {
            return;
        }

        // Enqueue admin scripts
        foreach ($this->scripts as $handle => $script) {
            if (isset($script['admin']) && $script['admin']) {
                wp_enqueue_script(
                    $handle,
                    $script['src'],
                    $script['deps'] ?? array(),
                    $script['version'] ?? $this->version,
                    $script['in_footer'] ?? true
                );
                
                // Localize script if data provided
                if (isset($script['localize'])) {
                    wp_localize_script($handle, $script['localize']['name'], $script['localize']['data']);
                }
            }
        }

        // Enqueue admin styles
        foreach ($this->styles as $handle => $style) {
            if (isset($style['admin']) && $style['admin']) {
                wp_enqueue_style(
                    $handle,
                    $style['src'],
                    $style['deps'] ?? array(),
                    $style['version'] ?? $this->version,
                    $style['media'] ?? 'all'
                );
            }
        }
        
        // Allow additional admin assets
        do_action('giftflowwp_gateway_enqueue_admin_assets', $this->id);
    }

    /**
     * Check if assets should be enqueued
     *
     * @return bool
     */
    protected function should_enqueue_assets() {
        // Check if on donation page or relevant pages
        global $post;
        
        if (is_admin()) {
            return false;
        }
        
        // Check for donation forms or shortcodes
        if ($post && (
            has_shortcode($post->post_content, 'giftflow_donation_form') ||
            has_block('giftflowwp/donation-form', $post->post_content)
        )) {
            return true;
        }
        
        // Allow filtering
        return apply_filters('giftflowwp_gateway_should_enqueue_assets', false, $this->id);
    }

    /**
     * Add script to be enqueued
     *
     * @param string $handle
     * @param array $script_args
     */
    protected function add_script($handle, $script_args) {
        $this->scripts[$handle] = $script_args;
    }

    /**
     * Add style to be enqueued
     *
     * @param string $handle
     * @param array $style_args
     */
    protected function add_style($handle, $style_args) {
        $this->styles[$handle] = $style_args;
    }

    /**
     * Get all registered gateways
     *
     * @return array
     */
    public static function get_registered_gateways() {
        return self::$gateway_registry;
    }

    /**
     * Get gateway by ID
     *
     * @param string $gateway_id
     * @return Gateway_Base|null
     */
    public static function get_gateway($gateway_id) {
        return isset(self::$gateway_registry[$gateway_id]) ? self::$gateway_registry[$gateway_id] : null;
    }

    /**
     * Initialize all gateways
     */
    public static function init_gateways() {
        // Allow plugins to register gateways
        do_action('giftflowwp_register_gateways');
        
        // Sort gateways by order
        uasort(self::$gateway_registry, function($a, $b) {
            return $a->get_order() - $b->get_order();
        });
        
        // Fire action after all gateways initialized
        do_action('giftflowwp_gateways_initialized', self::$gateway_registry);
    }

    /**
     * Get gateway settings fields
     *
     * @return array
     */
    abstract protected function get_settings_fields();

    /**
     * Process payment
     *
     * @param array $data Payment data
     * @param int $donation_id Donation ID
     * @return mixed
     */
    abstract public function process_payment($data, $donation_id = 0);

    // Getters
    public function get_id() { return $this->id; }
    public function get_title() { return $this->title; }
    public function get_description() { return $this->description; }
    public function get_icon() { return $this->icon; }
    public function is_enabled() { return $this->enabled; }
    public function get_order() { return $this->order; }
    public function get_supports() { return $this->supports; }
    public function get_settings() { return $this->settings; }
    public function get_setting($key, $default = '') {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
}