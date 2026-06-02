<?php
/**
 * Abstract Gateway — self-registering base for payment gateways.
 *
 * @package GiftFlow
 * @subpackage Gateways
 */

namespace GiftFlow\Gateways;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for all payment gateways with auto-registration.
 *
 * Implements GatewayInterface and SettingsProviderInterface.
 * Gateways requiring payment processing also implement
 * PaymentProcessorInterface and/or WebhookHandlerInterface.
 */
abstract class AbstractGateway extends AbstractModule implements GatewayInterface, SettingsProviderInterface {

	/**
	 * Static registry of all gateway instances.
	 *
	 * @var array<string, AbstractGateway>
	 */
	protected static array $registry = array();

	/**
	 * Gateway machine ID (e.g., 'stripe', 'paypal').
	 *
	 * @var string
	 */
	protected string $gateway_id = '';

	/**
	 * Gateway display title.
	 *
	 * @var string
	 */
	protected string $gateway_title = '';

	/**
	 * Gateway description for users.
	 *
	 * @var string
	 */
	protected string $gateway_description = '';

	/**
	 * Gateway icon URL.
	 *
	 * @var string
	 */
	protected string $gateway_icon = '';

	/**
	 * Display order (lower = first).
	 *
	 * @var int
	 */
	protected int $gateway_order = 10;

	/**
	 * Enabled status.
	 *
	 * @var bool
	 */
	protected bool $enabled = false;

	/**
	 * Supported features.
	 *
	 * @var array<int, string>
	 */
	protected array $supports = array( 'one-time' );

	/**
	 * Gateway settings values.
	 *
	 * @var array
	 */
	protected array $settings = array();

	/**
	 * Constructor.
	 *
	 * @param Container $container Service container.
	 */
	public function __construct( Container $container ) {
		parent::__construct( $container );
		$this->init();

		static::$registry[ $this->gateway_id ] = $this;
	}

	/**
	 * Initialize gateway properties. Override in subclasses.
	 *
	 * @return void
	 */
	abstract protected function init(): void;

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		$this->load_settings();
		add_filter( 'giftflow.gateways', array( $this, 'register_gateway' ) );
		add_filter( 'giftflow_payment_methods_settings', array( $this, 'register_settings_fields' ) );
	}

	/**
	 * Load settings from the database.
	 *
	 * @return void
	 */
	protected function load_settings(): void {
		$payment_options = get_option( 'giftflow_payment_options', array() );
		$payment_options = is_array( $payment_options ) ? $payment_options : array();

		$this->settings = $payment_options[ $this->gateway_id ] ?? array();
		$this->enabled  = ! empty( $this->settings['enabled'] );
	}

	/**
	 * Add this gateway to the filtered registry.
	 *
	 * @param array $gateways Existing gateways.
	 * @return array
	 */
	public function register_gateway( array $gateways ): array {
		$gateways[] = $this;
		return $gateways;
	}

	/**
	 * Register settings fields for this gateway.
	 *
	 * @param array $fields Existing fields.
	 * @return array
	 */
	public function register_settings_fields( array $fields ): array {
		$fields = array_merge( $fields, $this->get_settings_fields() );
		return $fields;
	}

	/**
	 * Get all registered gateway instances.
	 *
	 * @return array<string, AbstractGateway>
	 */
	public static function get_all(): array {
		return static::$registry;
	}

	/**
	 * Get a specific gateway instance by ID.
	 *
	 * @param string $id Gateway ID.
	 * @return AbstractGateway|null
	 */
	public static function get( string $id ): ?self {
		return static::$registry[ $id ] ?? null;
	}

	/**
	 * Get enabled gateways, sorted by order.
	 *
	 * @return array<string, AbstractGateway>
	 */
	public static function get_enabled(): array {
		$enabled = array_filter(
			static::$registry,
			function ( self $gateway ) {
				return $gateway->is_enabled();
			}
		);

		uasort(
			$enabled,
			function ( self $a, self $b ) {
				return $a->get_order() <=> $b->get_order();
			}
		);

		return $enabled;
	}

	// GatewayInterface implementation

	/**
	 * Get gateway ID.
	 *
	 * @return string
	 */
	public function get_id(): string {
		return $this->gateway_id;
	}

	/**
	 * Get gateway title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return $this->gateway_title;
	}

	/**
	 * Get gateway description.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return $this->gateway_description;
	}

	/**
	 * Check if enabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return $this->enabled;
	}

	/**
	 * Get gateway icon URL.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return $this->gateway_icon;
	}

	/**
	 * Get display order.
	 *
	 * @return int
	 */
	public function get_order(): int {
		return $this->gateway_order;
	}

	/**
	 * Get supported features.
	 *
	 * @return array<int, string>
	 */
	public function get_supports(): array {
		return $this->supports;
	}

	// SettingsProviderInterface implementation

	/**
	 * Get settings option key.
	 *
	 * @return string
	 */
	public function get_settings_key(): string {
		return 'giftflow_payment_options';
	}

	/**
	 * Get current settings.
	 *
	 * @return array
	 */
	public function get_settings(): array {
		return $this->settings;
	}
}
