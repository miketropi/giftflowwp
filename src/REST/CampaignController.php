<?php
/**
 * Campaign REST API Controller.
 *
 * @package GiftFlow
 * @subpackage REST
 */

namespace GiftFlow\REST;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles campaign listing and export REST routes.
 */
class CampaignController extends \WP_REST_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->namespace = 'giftflow/v2';
		$this->rest_base = 'campaigns';
	}

	/**
	 * Register routes.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get campaigns list.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_items( $request ): \WP_REST_Response {
		$args = array(
			'post_type'      => 'campaign',
			'post_status'    => 'publish',
			'posts_per_page' => $request->get_param( 'per_page' ),
			'paged'          => $request->get_param( 'page' ),
			's'              => $request->get_param( 'search' ),
			'order'          => strtoupper( $request->get_param( 'order' ) ),
			'orderby'        => $request->get_param( 'orderby' ),
		);

		$post_in = $request->get_param( 'include' );
		if ( ! empty( $post_in ) ) {
			$args['post__in'] = array_map( 'intval', (array) $post_in );
		}

		$post_not_in = $request->get_param( 'exclude' );
		if ( ! empty( $post_not_in ) ) {
			$args['post__not_in'] = array_map( 'intval', (array) $post_not_in ); // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
		}

		$query = new \WP_Query( $args );
		$campaigns = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();

				$goal_amount   = (float) get_post_meta( $post_id, '_goal_amount', true );
				$raised_amount = (float) giftflow_get_campaign_raised_amount( $post_id );
				$percentage    = giftflow_get_campaign_progress_percentage( $post_id );

				$campaigns[] = array(
					'id'                => $post_id,
					'title'             => get_the_title(),
					'excerpt'           => get_the_excerpt(),
					'thumbnail'         => get_the_post_thumbnail_url( $post_id, 'medium' ),
					'goal_amount'       => $goal_amount,
					'raised_amount'     => $raised_amount,
					'percentage'        => $percentage,
					'goal_formatted'    => giftflow_render_currency_formatted_amount( $goal_amount ),
					'raised_formatted'  => giftflow_render_currency_formatted_amount( $raised_amount ),
					'start_date'        => get_post_meta( $post_id, '_start_date', true ),
					'end_date'          => get_post_meta( $post_id, '_end_date', true ),
					'location'          => get_post_meta( $post_id, '_location', true ),
					'link'              => get_the_permalink(),
				);
			}
			wp_reset_postdata();
		}

		$response = rest_ensure_response( $campaigns );
		$response->header( 'X-WP-Total', $query->found_posts );
		$response->header( 'X-WP-TotalPages', $query->max_num_pages );

		return $response;
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
				__( 'Sorry, you are not allowed to view campaigns.', 'giftflow' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * Collection params for campaign listing.
	 *
	 * @return array
	 */
	public function get_collection_params(): array {
		return array(
			'per_page' => array(
				'type'        => 'integer',
				'default'     => 10,
				'minimum'     => 1,
				'maximum'     => 100,
				'description' => __( 'Number of campaigns per page.', 'giftflow' ),
			),
			'page'     => array(
				'type'        => 'integer',
				'default'     => 1,
				'minimum'     => 1,
				'description' => __( 'Page number.', 'giftflow' ),
			),
			'search'   => array(
				'type'        => 'string',
				'default'     => '',
				'description' => __( 'Search keyword.', 'giftflow' ),
			),
			'order'    => array(
				'type'        => 'string',
				'default'     => 'desc',
				'enum'        => array( 'asc', 'desc' ),
				'description' => __( 'Sort order.', 'giftflow' ),
			),
			'orderby'  => array(
				'type'        => 'string',
				'default'     => 'date',
				'enum'        => array( 'date', 'title', 'modified' ),
				'description' => __( 'Sort field.', 'giftflow' ),
			),
			'include'  => array(
				'type'        => 'array',
				'default'     => array(),
				'items'       => array( 'type' => 'integer' ),
				'description' => __( 'Campaign IDs to include.', 'giftflow' ),
			),
			'exclude'  => array(
				'type'        => 'array',
				'default'     => array(),
				'items'       => array( 'type' => 'integer' ),
				'description' => __( 'Campaign IDs to exclude.', 'giftflow' ),
			),
		);
	}

	/**
	 * Item schema.
	 *
	 * @return array
	 */
	public function get_item_schema(): array {
		if ( $this->schema ) {
			return $this->schema;
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'giftflow_campaign',
			'type'       => 'object',
			'properties' => array(
				'id'               => array(
					'type'        => 'integer',
					'description' => __( 'Campaign post ID.', 'giftflow' ),
				),
				'title'            => array(
					'type'        => 'string',
					'description' => __( 'Campaign title.', 'giftflow' ),
				),
				'excerpt'          => array(
					'type'        => 'string',
					'description' => __( 'Campaign excerpt.', 'giftflow' ),
				),
				'thumbnail'        => array(
					'type'        => 'string',
					'description' => __( 'Featured image URL.', 'giftflow' ),
				),
				'goal_amount'      => array(
					'type'        => 'number',
					'description' => __( 'Fundraising goal.', 'giftflow' ),
				),
				'raised_amount'    => array(
					'type'        => 'number',
					'description' => __( 'Amount raised.', 'giftflow' ),
				),
				'percentage'       => array(
					'type'        => 'number',
					'description' => __( 'Progress percentage.', 'giftflow' ),
				),
				'goal_formatted'   => array(
					'type'        => 'string',
					'description' => __( 'Formatted goal.', 'giftflow' ),
				),
				'raised_formatted' => array(
					'type'        => 'string',
					'description' => __( 'Formatted raised.', 'giftflow' ),
				),
			),
		);

		return $this->schema;
	}
}
