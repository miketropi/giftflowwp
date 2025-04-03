<?php
/**
 * Base Gateway class for GiftFlowWp
 *
 * @package GiftFlowWp
 * @subpackage Gateways
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
     * Gateway settings
     *
     * @var array
     */
    protected $settings;

    /**
     * Initialize gateway
     */
    public function __construct() {
        parent::__construct();
        $this->init_settings();
        $this->init_hooks();
    }

    /**
     * Initialize gateway settings
     */
    protected function init_settings() {
        $this->settings = get_option( 'giftflowwp_gateway_' . $this->id, array() );
    }

    /**
     * Initialize WordPress hooks
     */
    protected function init_hooks() {
        add_filter( 'giftflowwp_payment_gateways', array( $this, 'register_gateway' ) );
        add_action( 'giftflowwp_settings_gateways', array( $this, 'render_settings' ) );
        add_action( 'giftflowwp_save_settings', array( $this, 'save_settings' ) );
    }

    /**
     * Register gateway
     *
     * @param array $gateways List of gateways
     * @return array
     */
    public function register_gateway( $gateways ) {
        $gateways[ $this->id ] = array(
            'title' => $this->title,
            'description' => $this->description,
        );
        return $gateways;
    }

    /**
     * Render gateway settings
     */
    public function render_settings() {
        include $this->plugin_dir . 'templates/admin/gateway-settings.php';
    }

    /**
     * Save gateway settings
     */
    public function save_settings() {
        if ( ! isset( $_POST['giftflowwp_gateway_nonce'] ) || 
             ! wp_verify_nonce( $_POST['giftflowwp_gateway_nonce'], 'giftflowwp_save_gateway_settings' ) ) {
            return;
        }

        $settings = array();
        foreach ( $this->get_settings_fields() as $key => $field ) {
            if ( isset( $_POST[ $key ] ) ) {
                $settings[ $key ] = sanitize_text_field( $_POST[ $key ] );
            }
        }

        update_option( 'giftflowwp_gateway_' . $this->id, $settings );
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
     * @return mixed
     */
    abstract public function process_payment( $data );

    /**
     * Get gateway ID
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get gateway title
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Get gateway description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }
} 