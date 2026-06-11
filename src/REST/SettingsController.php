<?php
/**
 * Settings REST API Controller.
 *
 * @package GiftFlow
 * @subpackage REST
 */

namespace GiftFlow\REST;

use GiftFlow\Settings\SettingsRegistry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles REST API routes for GiftFlow settings.
 */
class SettingsController extends \WP_REST_Controller {

	/**
	 * Settings registry instance.
	 *
	 * @var SettingsRegistry
	 */
	private SettingsRegistry $settings;

	/**
	 * Constructor.
	 *
	 * @param SettingsRegistry $settings Settings registry.
	 */
	public function __construct( SettingsRegistry $settings ) {
		$this->namespace = 'giftflow/v2';
		$this->rest_base = 'settings';
		$this->settings  = $settings;
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
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'get_settings_permissions_check' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'update_settings_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/defaults',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_defaults' ),
					'permission_callback' => array( $this, 'get_settings_permissions_check' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/campaign/(?P<id>\d+)/settings',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_campaign_overrides' ),
					'permission_callback' => array( $this, 'get_settings_permissions_check' ),
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_campaign_overrides' ),
					'permission_callback' => array( $this, 'update_settings_permissions_check' ),
					'args'                => array(
						'id'         => array(
							'required'          => true,
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
						),
						'overrides'  => array(
							'required' => true,
							'type'     => 'object',
						),
					),
				),
			)
		);
	}

	/**
	 * Get all settings.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_settings(): \WP_REST_Response {
		return rest_ensure_response( $this->settings->get_all() );
	}

	/**
	 * Get default settings.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_defaults(): \WP_REST_Response {
		return rest_ensure_response( $this->settings->get_defaults() );
	}

	/**
	 * Update settings.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function update_settings( \WP_REST_Request $request ) {
		$data     = $request->get_json_params();
		$settings = is_array( $data ) ? $data : array();

		$saved = $this->settings->save( $settings );

		if ( ! $saved ) {
			return new \WP_Error(
				'giftflow_settings_save_failed',
				__( 'Failed to save settings.', 'giftflow' ),
				array( 'status' => 500 )
			);
		}

		return rest_ensure_response(
			array(
				'success'  => true,
				'settings' => $this->settings->get_all(),
			)
		);
	}

	/**
	 * Get campaign-level overrides.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_campaign_overrides( \WP_REST_Request $request ): \WP_REST_Response {
		$campaign_id = $request->get_param( 'id' );
		return rest_ensure_response(
			array(
				'campaign_id' => $campaign_id,
				'overrides'   => $this->settings->get_campaign_overrides( $campaign_id ),
				'defaults'    => $this->settings->get_defaults(),
			)
		);
	}

	/**
	 * Update campaign-level overrides.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function update_campaign_overrides( \WP_REST_Request $request ) {
		$campaign_id = $request->get_param( 'id' );
		$overrides   = $request->get_param( 'overrides' );

		if ( ! is_array( $overrides ) ) {
			return new \WP_Error(
				'giftflow_invalid_overrides',
				__( 'Overrides must be an object.', 'giftflow' ),
				array( 'status' => 400 )
			);
		}

		$this->settings->save_campaign_overrides( $campaign_id, $overrides );

		return rest_ensure_response(
			array(
				'success'     => true,
				'campaign_id' => $campaign_id,
				'overrides'   => $this->settings->get_campaign_overrides( $campaign_id ),
			)
		);
	}

	/**
	 * Check permissions for reading settings.
	 *
	 * @return bool|\WP_Error
	 */
	public function get_settings_permissions_check() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to view settings.', 'giftflow' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * Check permissions for updating settings.
	 *
	 * @return bool|\WP_Error
	 */
	public function update_settings_permissions_check() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to modify settings.', 'giftflow' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * Schema for settings endpoint.
	 *
	 * @return array
	 */
	public function get_item_schema(): array {
		if ( $this->schema ) {
			return $this->schema;
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'giftflow_settings',
			'type'       => 'object',
			'properties' => array(
				'global'   => array(
					'type'       => 'object',
					'description' => __( 'Global donation settings.', 'giftflow' ),
				),
				'display'  => array(
					'type'       => 'object',
					'description' => __( 'Display and styling settings.', 'giftflow' ),
				),
				'email'    => array(
					'type'       => 'object',
					'description' => __( 'Email notification settings.', 'giftflow' ),
				),
				'advanced' => array(
					'type'       => 'object',
					'description' => __( 'Advanced settings.', 'giftflow' ),
				),
			),
		);

		return $this->schema;
	}
}
