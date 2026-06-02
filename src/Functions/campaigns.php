<?php
/**
 * Campaign helper functions.
 *
 * These wrap the legacy giftflow_* functions from common.php
 * while providing a namespace for future migration.
 *
 * @package GiftFlow
 * @subpackage Functions
 */

namespace GiftFlow\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get total amount raised for a campaign.
 *
 * @param int $campaign_id Campaign post ID.
 * @return float
 */
function get_campaign_raised_amount( int $campaign_id ): float {
	return function_exists( 'giftflow_get_campaign_raised_amount' )
		? (float) giftflow_get_campaign_raised_amount( $campaign_id )
		: 0.0;
}

/**
 * Get campaign progress percentage.
 *
 * @param int $campaign_id Campaign post ID.
 * @return float
 */
function get_campaign_progress_percentage( int $campaign_id ): float {
	return function_exists( 'giftflow_get_campaign_progress_percentage' )
		? (float) giftflow_get_campaign_progress_percentage( $campaign_id )
		: 0.0;
}

/**
 * Get donations for a campaign.
 *
 * @param int   $campaign_id Campaign post ID.
 * @param array $args        Query args.
 * @param int   $paged       Page number.
 * @return array
 */
function get_campaign_donations( int $campaign_id, array $args = array(), int $paged = 1 ): array {
	return function_exists( 'giftflow_get_campaign_donations' )
		? giftflow_get_campaign_donations( $campaign_id, $args, $paged )
		: array();
}

/**
 * Prepare campaign status bar data.
 *
 * @param int $campaign_id Campaign post ID.
 * @return array
 */
function prepare_campaign_status_bar_data( int $campaign_id ): array {
	return function_exists( 'giftflow_prepare_campaign_status_bar_data' )
		? giftflow_prepare_campaign_status_bar_data( $campaign_id )
		: array();
}
