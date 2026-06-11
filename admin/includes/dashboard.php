<?php
/**
 * GiftFlow Dashboard
 *
 * @package GiftFlow
 * @subpackage Admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include dashboard functions.
require_once GIFTFLOW_PLUGIN_DIR . 'admin/includes/dashboard-functions.php';

/**
 * Register the GiftFlow dashboard page.

 * @return void
 */
function giftflow_register_dashboard_page() {

	// add icon to the menu page.
	// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	$icon = 'data:image/svg+xml;base64,' . base64_encode( giftflow_svg_icon( 'plgicon' ) );

	add_menu_page(
		apply_filters( 'giftflow_dashboard_page_title', __( 'GiftFlow Dashboard', 'giftflow' ) ),
		apply_filters( 'giftflow_menu_title', __( 'GiftFlow', 'giftflow' ) ),
		'manage_options',
		'giftflow-dashboard',
		'giftflow_dashboard_page',
		$icon,
		30
	);

	add_submenu_page(
		'giftflow-dashboard',
		__( 'Dashboard', 'giftflow' ),
		__( 'Dashboard', 'giftflow' ),
		'manage_options',
		'giftflow-dashboard',
		'giftflow_dashboard_page',
		0
	);
}
add_action( 'admin_menu', 'giftflow_register_dashboard_page' );

/**
 * Display the GiftFlow dashboard page content.
 *
 * @return void
 */
function giftflow_dashboard_page() {
	// Check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Tabs handled client-side by React components.
	// Backwards-compatible: still supports ?tab=overview|help via React router.
	// Filter for extensibility.
	$tabs = array(
		'overview' => array(
			'label'    => __( 'Overview', 'giftflow' ),
			'callback' => 'giftflow_dashboard_overview_tab',
		),
		'help'     => array(
			'label'    => __( 'Help', 'giftflow' ),
			'callback' => 'giftflow_dashboard_help_tab',
		),
	);

	$tabs = apply_filters( 'giftflow_dashboard_tabs', $tabs );

	giftflow_load_template( 'admin/dashboard-view.php' );
}

/**
 * Display the overview tab content.
 *
 * @return void
 */
function giftflow_dashboard_overview_tab() {
	giftflow_load_template( 'admin/dashboard-view.php' );
}

/**
 * Display the help tab content.
 *
 * @return void
 */
function giftflow_dashboard_help_tab() {
	giftflow_load_template( 'admin/dashboard-helps.php' );
}
