<?php
/**
 * Template helper functions.
 *
 * @package GiftFlow
 * @subpackage Functions
 */

namespace GiftFlow\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load a template file from the plugin's templates directory.
 *
 * Falls back to theme override (giftflow/{template}) first,
 * then plugin's templates/{template}.
 *
 * @param string $template Template path relative to templates/.
 * @param array  $args     Variables to pass to the template.
 * @return void
 */
function load_template( string $template, array $args = array() ): void {
	if ( function_exists( 'giftflow_load_template' ) ) {
		giftflow_load_template( $template, $args );
	}
}

/**
 * Get a template file path with theme override support.
 *
 * @param string $template Template path.
 * @return string|null
 */
function get_template_path( string $template ): ?string {
	if ( ! function_exists( 'giftflow_get_template_path' ) ) {
		return null;
	}

	$path = giftflow_get_template_path( $template );
	return $path ?: null;
}

/**
 * Check if current page is the campaigns page.
 *
 * @return bool
 */
function is_campaigns_page(): bool {
	return function_exists( 'is_campaigns_page' ) && is_campaigns_page();
}

/**
 * Check if current page is the donor account page.
 *
 * @return bool
 */
function is_donor_account_page(): bool {
	return function_exists( 'is_my_account_page' ) && is_my_account_page();
}

/**
 * Check if current page is the thank donor page.
 *
 * @return bool
 */
function is_thank_donor_page(): bool {
	return function_exists( 'is_thank_donor_page' ) && is_thank_donor_page();
}
