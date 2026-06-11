<?php
/**
 * General helper functions.
 *
 * Thin wrappers around existing giftflow_* global functions
 * with typed signatures for future refactoring.
 *
 * @package GiftFlow
 * @subpackage Functions
 */

namespace GiftFlow\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the global currency code.
 *
 * @return string
 */
function get_global_currency(): string {
	return function_exists( 'giftflow_get_global_currency' )
		? giftflow_get_global_currency()
		: 'USD';
}

/**
 * Get the global currency symbol.
 *
 * @return string
 */
function get_global_currency_symbol(): string {
	return function_exists( 'giftflow_get_global_currency_symbol' )
		? giftflow_get_global_currency_symbol()
		: '$';
}

/**
 * Render an amount with currency formatting.
 *
 * @param float  $amount        Amount in the currency unit (not cents).
 * @param string $currency_code Optional currency code override.
 * @return string
 */
function render_currency_formatted_amount( float $amount, string $currency_code = '' ): string {
	return function_exists( 'giftflow_render_currency_formatted_amount' )
		? giftflow_render_currency_formatted_amount( $amount, $currency_code )
		: '$' . number_format_i18n( $amount, 2 );
}

/**
 * Recursively sanitize an array.
 *
 * @param array $array Array to sanitize.
 * @return array
 */
function sanitize_array( array $array ): array {
	return function_exists( 'giftflow_sanitize_array' )
		? giftflow_sanitize_array( $array )
		: array_map( 'sanitize_text_field', $array );
}

/**
 * Get a file's contents safely.
 *
 * @param string $file_path File path.
 * @return string
 */
function get_file_content( string $file_path ): string {
	if ( ! file_exists( $file_path ) ) {
		return '';
	}

	if ( ! function_exists( 'giftflow_get_file_content' ) ) {
		return (string) file_get_contents( $file_path );
	}

	return giftflow_get_file_content( $file_path );
}

/**
 * Get all plugin options.
 *
 * @param string $option_name Option name.
 * @return array
 */
function get_options( string $option_name ): array {
	if ( ! function_exists( 'giftflow_get_options' ) ) {
		return (array) get_option( $option_name, array() );
	}

	return giftflow_get_options( $option_name );
}

/**
 * Get total donations amount across all campaigns.
 *
 * @return float
 */
function get_total_donations_amount(): float {
	return function_exists( 'giftflow_get_total_donations_amount' )
		? (float) giftflow_get_total_donations_amount()
		: 0.0;
}

/**
 * Get total number of donors.
 *
 * @return int
 */
function get_total_donors_count(): int {
	return function_exists( 'giftflow_get_total_donors_count' )
		? (int) giftflow_get_total_donors_count()
		: 0;
}
