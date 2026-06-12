<?php
/**
 * Block pattern categories for GiftFlow.
 *
 * Return an array of categories to register via register_block_pattern_category().
 *
 * @package GiftFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	array(
		'slug'  => 'giftflow',
		'label' => __( 'GiftFlow', 'giftflow' ),
	),
	array(
		'slug'  => 'giftflow-campaigns',
		'label' => __( 'GiftFlow Campaigns', 'giftflow' ),
	),
	array(
		'slug'  => 'giftflow-donations',
		'label' => __( 'GiftFlow Donations', 'giftflow' ),
	),
);
