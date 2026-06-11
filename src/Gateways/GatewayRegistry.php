<?php
/**
 * Gateway Registry — discovers and registers payment gateways.
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
 * Collects all gateway instances and provides query methods.
 */
class GatewayRegistry extends AbstractModule {

	/**
	 * Register gateway discovery hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_filter( 'giftflow.gateways', array( $this, 'collect_gateways' ) );
	}

	/**
	 * Collect all registered gateway instances from the static registry.
	 *
	 * @param array $gateways Existing gateways from filter.
	 * @return array<int, AbstractGateway>
	 */
	public function collect_gateways( array $gateways ): array {
		return array_merge( $gateways, array_values( AbstractGateway::get_all() ) );
	}

	/**
	 * Get all enabled gateways sorted by order.
	 *
	 * @return array<string, AbstractGateway>
	 */
	public function get_enabled(): array {
		return AbstractGateway::get_enabled();
	}

	/**
	 * Get a specific gateway by ID.
	 *
	 * @param string $id Gateway ID.
	 * @return AbstractGateway|null
	 */
	public function get_gateway( string $id ): ?AbstractGateway {
		return AbstractGateway::get( $id );
	}

	/**
	 * Check if a gateway is available for a given feature.
	 *
	 * @param string $feature Feature name (e.g., 'recurring', 'webhook').
	 * @return array<int, AbstractGateway>
	 */
	public function get_by_support( string $feature ): array {
		return array_filter(
			AbstractGateway::get_all(),
			function ( AbstractGateway $gateway ) use ( $feature ) {
				return $gateway->is_enabled() && in_array( $feature, $gateway->get_supports(), true );
			}
		);
	}
}
