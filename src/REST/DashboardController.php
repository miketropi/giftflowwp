<?php
/**
 * Dashboard REST API Controller.
 *
 * @package GiftFlow
 * @subpackage REST
 */

namespace GiftFlow\REST;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles dashboard overview and statistics REST routes.
 */
class DashboardController extends \WP_REST_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->namespace = 'giftflow/v2';
		$this->rest_base = 'dashboard';
	}

	/**
	 * Register routes.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/overview',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_overview' ),
					'permission_callback' => array( $this, 'get_permissions_check' ),
				),
				'schema' => array( $this, 'get_overview_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/charts',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_charts' ),
					'permission_callback' => array( $this, 'get_permissions_check' ),
					'args'                => array(
						'period' => array(
							'type'        => 'string',
							'default'     => '30d',
							'enum'        => array( '7d', '30d', '90d', '12m', 'all' ),
							'description' => __( 'Time period for chart data.', 'giftflow' ),
						),
					),
				),
			)
		);
	}

	/**
	 * Get dashboard overview stats.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_overview(): \WP_REST_Response {
		$total_raised = function_exists( 'giftflow_get_total_donations_amount' )
			? giftflow_get_total_donations_amount()
			: 0;

		$total_campaigns = function_exists( 'giftflow_get_total_campaigns_by_status' )
			? giftflow_get_total_campaigns_by_status( 'active' )
			: 0;

		$total_donors = function_exists( 'giftflow_get_total_donors_count' )
			? giftflow_get_total_donors_count()
			: 0;

		$recent_donations = function_exists( 'giftflow_get_recent_donations' )
			? giftflow_get_recent_donations()
			: array();

		$data = array(
			'total_raised'            => $total_raised,
			'total_raised_formatted'  => giftflow_render_currency_formatted_amount( $total_raised ),
			'total_active_campaigns'  => $total_campaigns,
			'total_donors'            => $total_donors,
			'recent_donations'        => $recent_donations,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Get dashboard chart data.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_charts( \WP_REST_Request $request ): \WP_REST_Response {
		$period = $request->get_param( 'period' );

		$donations_overview = function_exists( 'giftflow_get_donations_overview_stats_by_period' )
			? giftflow_get_donations_overview_stats_by_period( $period )
			: array();

		return rest_ensure_response(
			array(
				'donations_overview' => $donations_overview,
				'period'             => $period,
			)
		);
	}

	/**
	 * Check permissions.
	 *
	 * @return bool|\WP_Error
	 */
	public function get_permissions_check() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to view dashboard data.', 'giftflow' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * Overview response schema.
	 *
	 * @return array
	 */
	public function get_overview_schema(): array {
		return array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'giftflow_dashboard_overview',
			'type'       => 'object',
			'properties' => array(
				'total_raised'            => array(
					'type'        => 'number',
					'description' => __( 'Total amount raised in cents.', 'giftflow' ),
				),
				'total_raised_formatted'  => array(
					'type'        => 'string',
					'description' => __( 'Formatted total raised.', 'giftflow' ),
				),
				'total_active_campaigns'  => array(
					'type'        => 'integer',
					'description' => __( 'Number of active campaigns.', 'giftflow' ),
				),
				'total_donors'            => array(
					'type'        => 'integer',
					'description' => __( 'Total unique donors.', 'giftflow' ),
				),
				'recent_donations'        => array(
					'type'        => 'array',
					'description' => __( 'Recent donations list.', 'giftflow' ),
				),
			),
		);
	}
}
