<?php
/**
 * Currency Service — centralizes currency data and formatting.
 *
 * @package GiftFlow
 * @subpackage Services
 */

namespace GiftFlow\Services;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides currency lookup, symbol resolution, and amount formatting.
 */
class CurrencyService extends AbstractModule {

	/**
	 * Cached currency data.
	 *
	 * @var array|null
	 */
	private static $currencies = null;

	/**
	 * Cached currency index by code.
	 *
	 * @var array<string, array>|null
	 */
	private static $currency_index = null;

	/**
	 * No hooks needed — pure service.
	 *
	 * @return void
	 */
	public function register(): void {}

	/**
	 * Load currency data file.
	 *
	 * @return array
	 */
	private function load_currencies(): array {
		if ( null === self::$currencies ) {
			$currency_file = GIFTFLOW_PLUGIN_DIR . 'includes/currency.php';
			self::$currencies = file_exists( $currency_file )
				? require $currency_file
				: array();

			self::$currency_index = array();
			foreach ( self::$currencies as $currency ) {
				self::$currency_index[ $currency['code'] ] = $currency;
			}
		}

		return self::$currencies;
	}

	/**
	 * Get all currencies.
	 *
	 * @return array
	 */
	public function get_all(): array {
		return $this->load_currencies();
	}

	/**
	 * Get currency by code.
	 *
	 * @param string $code ISO 4217 currency code.
	 * @return array|null
	 */
	public function get_by_code( string $code ): ?array {
		$this->load_currencies();
		return self::$currency_index[ $code ] ?? null;
	}

	/**
	 * Get currency symbol by code.
	 *
	 * @param string $code ISO 4217 currency code.
	 * @return string
	 */
	public function get_symbol( string $code ): string {
		$currency = $this->get_by_code( $code );
		return $currency['symbol'] ?? '$';
	}

	/**
	 * Get currency name by code.
	 *
	 * @param string $code ISO 4217 currency code.
	 * @return string
	 */
	public function get_name( string $code ): string {
		$currency = $this->get_by_code( $code );
		return $currency['name'] ?? '';
	}

	/**
	 * Format a cent amount to display string (e.g., "$25.00").
	 *
	 * @param int         $amount_cents  Amount in cents.
	 * @param string|null $currency_code Currency code (null = global default).
	 * @return string
	 */
	public function format_amount( int $amount_cents, ?string $currency_code = null ): string {
		if ( null === $currency_code ) {
			$currency_code = giftflow_get_global_currency();
		}

		$currency = $this->get_by_code( $currency_code );
		$symbol   = $currency['symbol'] ?? '$';
		$decimal  = (float) ( $amount_cents / 100 );

		return $symbol . number_format_i18n( $decimal, 2 );
	}

	/**
	 * Convert a cent amount to a float for calculations.
	 *
	 * @param int $amount_cents Amount in cents.
	 * @return float
	 */
	public function to_float( int $amount_cents ): float {
		return round( $amount_cents / 100, 2 );
	}

	/**
	 * Convert a float amount to cents.
	 *
	 * @param float $amount Amount in float.
	 * @return int
	 */
	public function to_cents( float $amount ): int {
		return (int) round( $amount * 100 );
	}

	/**
	 * Get currency codes for SelectControl options.
	 *
	 * @return array<int, array{label: string, value: string}>
	 */
	public function get_option_list(): array {
		$this->load_currencies();
		$options = array();

		foreach ( self::$currencies as $currency ) {
			$options[] = array(
				'label' => $currency['code'] . ' (' . $currency['symbol'] . ')',
				'value' => $currency['code'],
			);
		}

		return $options;
	}
}
