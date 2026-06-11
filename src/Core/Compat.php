<?php
/**
 * Backward Compatibility Layer.
 *
 * Provides shims from legacy global functions/classes to the new
 * container-based architecture. Third-party plugins that use the
 * old API continue working without changes.
 *
 * @package GiftFlow
 * @subpackage Core
 */

namespace GiftFlow\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Backward compatibility shims.
 *
 * Loaded during boot to ensure legacy APIs map to new implementations.
 */
class Compat extends AbstractModule {

	/**
	 * Register compatibility filters and actions.
	 *
	 * @return void
	 */
	public function register(): void {
		// ======================================================
		// Settings backwards compatibility
		// ======================================================

		/**
		 * Map legacy giftflow_get_options() to SettingsRegistry.
		 *
		 * Third-party code: $opts = giftflow_get_options('giftflow_general_options');
		 * New: $settings = $container->get(SettingsRegistry::class)->get_group('global');
		 */
		if ( ! function_exists( 'giftflow_get_v2_settings' ) ) {
			/**
			 * Get settings from the new v2 registry, falling back to legacy options.
			 *
			 * @param string|null $group Settings group key (null = all settings).
			 * @return array
			 */
			function giftflow_get_v2_settings( ?string $group = null ): array {
				$plugin = giftflow_boot();
				if ( ! $plugin ) {
					return array();
				}

				$settings = $plugin->container()->get( \GiftFlow\Settings\SettingsRegistry::class );

				if ( null === $group ) {
					return $settings->get_all();
				}

				return $settings->get_group( $group );
			}
		}

		/**
		 * Filter to detect if v2 settings are active.
		 *
		 * @param bool $is_v2 Whether v2 is active.
		 * @return bool
		 */
		add_filter( 'giftflow.is_v2', '__return_true' );

		// ======================================================
		// Block backwards compatibility
		// ======================================================

		/**
		 * Filter to register additional blocks.
		 *
		 * Third-party code:
		 *   add_filter('giftflow.blocks', function($blocks) {
		 *       $blocks['my-block'] = __DIR__ . '/blocks/my-block';
		 *       return $blocks;
		 *   });
		 */
		add_filter(
			'giftflow.blocks',
			function ( array $blocks ): array {
				return apply_filters( 'giftflow_load_files', $blocks );
			}
		);

		/**
		 * Deprecated: giftflow_register_block().
		 *
		 * @param string $block_name Block name.
		 * @param array  $args       Registration args.
		 */
		if ( ! function_exists( 'giftflow_register_block_v2' ) ) {
			function giftflow_register_block_v2( string $block_name, array $args ): void {
				_deprecated_function( 'giftflow_register_block_v2', '2.0', 'block.json + register_block_type_from_metadata()' );
				register_block_type( $block_name, $args );
			}
		}

		// ======================================================
		// Gateway backwards compatibility
		// ======================================================

		/**
		 * Map legacy Gateway_Base::$gateway_registry to AbstractGateway::get_all().
		 */
		add_filter(
			'giftflow.gateways',
			function ( array $gateways ): array {
				if ( class_exists( \GiftFlow\Gateways\AbstractGateway::class ) ) {
					return array_merge( $gateways, array_values( \GiftFlow\Gateways\AbstractGateway::get_all() ) );
				}

				if ( class_exists( \GiftFlow\Gateways\Gateway_Base::class ) ) {
					$legacy = \GiftFlow\Gateways\Gateway_Base::$gateway_registry ?? array();
					return array_merge( $gateways, array_values( $legacy ) );
				}

				return $gateways;
			}
		);
	}
}
