<?php
/**
 * Settings Registry — global, campaign, and block-level settings with inheritance.
 *
 * @package GiftFlow
 * @subpackage Settings
 */

namespace GiftFlow\Settings;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages plugin settings with three-tier inheritance:
 *   Global Defaults → Campaign Settings → Block Instance Attributes
 */
class SettingsRegistry extends AbstractModule {

	/**
	 * Option key for v2 settings.
	 *
	 * @var string
	 */
	private const OPTION_KEY = 'giftflow_v2_settings';

	/**
	 * Register WordPress hooks. No hooks needed — settings are accessed
	 * imperatively via container, not through WP actions.
	 *
	 * @return void
	 */
	public function register(): void {
		// Migrate legacy settings on admin init if needed.
		add_action( 'admin_init', array( $this, 'maybe_migrate_legacy_settings' ) );
	}

	/**
	 * Default settings with schema.
	 *
	 * @var array<string, array>
	 */
	private const DEFAULTS = array(
		'global'  => array(
			'currency'                 => 'USD',
			'currency_template'        => '$1$ — $2$',
			'default_amount'           => 2500,
			'min_amount'               => 100,
			'max_amount'               => 1000000,
			'allow_custom_amount'      => true,
			'preset_amounts'           => array( 1000, 2500, 5000, 10000 ),
			'payment_gateways'         => array( 'stripe', 'paypal', 'direct_bank_transfer' ),
		),
		'display' => array(
			'progress_bar_color'       => '#005ae0',
			'progress_bar_height'      => 12,
			'border_radius'            => 8,
			'primary_color'            => '#005ae0',
			'font_family'              => 'inherit',
			'grid_columns'             => 3,
			'enable_lightbox'          => true,
			'button_full_width'        => false,
		),
		'email'   => array(
			'from_name'                => '',
			'admin_address'            => '',
			'donor_subject_template'   => 'Thank you for your donation, {{donor_name}}!',
			'admin_subject_template'   => 'New donation received: {{amount}} from {{donor_name}}',
		),
		'advanced' => array(
			'enable_recaptcha'         => false,
			'recaptcha_site_key'       => '',
			'recaptcha_secret_key'     => '',
			'google_maps_api_key'      => '',
		),
	);

	/**
	 * Get all settings, merged with defaults.
	 *
	 * @return array
	 */
	public function get_all(): array {
		$stored = get_option( self::OPTION_KEY, array() );
		$stored = is_array( $stored ) ? $stored : array();

		return $this->deep_merge( self::DEFAULTS, $stored );
	}

	/**
	 * Get a specific settings group.
	 *
	 * @param string $group Group key (global, display, email, advanced).
	 * @return array
	 */
	public function get_group( string $group ): array {
		$all = $this->get_all();
		return $all[ $group ] ?? self::DEFAULTS[ $group ] ?? array();
	}

	/**
	 * Get a single setting value with campaign/block override support.
	 *
	 * Resolution order:
	 *   1. Block attributes (via $block_attrs)
	 *   2. Campaign meta (via campaign post meta)
	 *   3. Global settings
	 *
	 * @param string     $key          Setting key (e.g., 'currency', 'progress_bar_color').
	 * @param int|null   $campaign_id  Optional campaign ID for campaign-level overrides.
	 * @param array|null $block_attrs  Optional block instance attributes.
	 * @return mixed
	 */
	public function get( string $key, ?int $campaign_id = null, ?array $block_attrs = null ) {
		// Block-level override.
		if ( null !== $block_attrs && isset( $block_attrs[ $key ] ) ) {
			$inherited = $block_attrs[ '__inherited_' . $key ] ?? false;
			if ( ! $inherited ) {
				return $block_attrs[ $key ];
			}
		}

		// Campaign-level override.
		if ( null !== $campaign_id ) {
			$campaign_value = get_post_meta( $campaign_id, '_giftflow_' . $key, true );
			if ( ! empty( $campaign_value ) ) {
				return $campaign_value;
			}
		}

		// Fall back to global settings (search all groups).
		$all = $this->get_all();
		foreach ( $all as $group ) {
			if ( isset( $group[ $key ] ) ) {
				return $group[ $key ];
			}
		}

		return null;
	}

	/**
	 * Save settings (admin only).
	 *
	 * @param array $settings Full settings array to store.
	 * @return bool
	 */
	public function save( array $settings ): bool {
		$sanitized = $this->sanitize( $settings );
		return update_option( self::OPTION_KEY, $sanitized, false );
	}

	/**
	 * Save campaign-level overrides.
	 *
	 * @param int   $campaign_id Campaign post ID.
	 * @param array $overrides   Key-value pairs to override.
	 * @return void
	 */
	public function save_campaign_overrides( int $campaign_id, array $overrides ): void {
		foreach ( $overrides as $key => $value ) {
			if ( '' === $value || null === $value ) {
				delete_post_meta( $campaign_id, '_giftflow_' . $key );
			} else {
				update_post_meta( $campaign_id, '_giftflow_' . $key, $value );
			}
		}
	}

	/**
	 * Get campaign overrides for all settings.
	 *
	 * @param int $campaign_id Campaign post ID.
	 * @return array
	 */
	public function get_campaign_overrides( int $campaign_id ): array {
		$overrides = array();
		$all       = $this->get_all();

		foreach ( $all as $group ) {
			foreach ( array_keys( $group ) as $key ) {
				$value = get_post_meta( $campaign_id, '_giftflow_' . $key, true );
				if ( ! empty( $value ) || '0' === (string) $value ) {
					$overrides[ $key ] = $value;
				}
			}
		}

		return $overrides;
	}

	/**
	 * Get defaults (for the settings UI).
	 *
	 * @return array
	 */
	public function get_defaults(): array {
		return self::DEFAULTS;
	}

	/**
	 * Auto-migrate legacy settings (giftflow_general_options, etc.) into
	 * the new v2 settings structure on first access.
	 *
	 * @return void
	 */
	public function maybe_migrate_legacy_settings(): void {
		if ( get_option( self::OPTION_KEY, false ) !== false ) {
			return;
		}

		$legacy_general = get_option( 'giftflow_general_options', array() );
		$legacy_general = is_array( $legacy_general ) ? $legacy_general : array();
		$legacy_email   = get_option( 'giftflow_email_options', array() );
		$legacy_email   = is_array( $legacy_email ) ? $legacy_email : array();
		$legacy_api     = get_option( 'giftflow_options_with_api_keys_options', array() );
		$legacy_api     = is_array( $legacy_api ) ? $legacy_api : array();

		$migrated = array(
			'global'   => array(
				'currency'            => $legacy_general['currency'] ?? 'USD',
				'default_amount'      => (int) ( $legacy_general['min_amount'] ?? 2500 ),
				'min_amount'          => (int) ( $legacy_general['min_amount'] ?? 100 ),
				'max_amount'          => (int) ( $legacy_general['max_amount'] ?? 1000000 ),
				'allow_custom_amount' => ! empty( $legacy_general['allow_custom_donation_amounts'] ),
				'preset_amounts'      => $this->parse_preset_amounts( $legacy_general['preset_donation_amounts'] ?? '' ),
			),
			'display'  => array(),
			'email'    => array(
				'from_name'              => $legacy_email['email_from_name'] ?? '',
				'admin_address'          => $legacy_email['email_admin_address'] ?? '',
			),
			'advanced' => array(
				'enable_recaptcha'     => ! empty( $legacy_api['enable_recaptcha'] ),
				'recaptcha_site_key'   => $legacy_api['recaptcha_site_key'] ?? '',
				'recaptcha_secret_key' => $legacy_api['recaptcha_secret_key'] ?? '',
				'google_maps_api_key'  => $legacy_api['google_maps_api_key'] ?? '',
			),
		);

		update_option( self::OPTION_KEY, $migrated, false );
	}

	/**
	 * Parse legacy preset amounts (comma-separated string) into array of ints.
	 *
	 * @param string $raw Comma-separated amounts.
	 * @return array<int, int>
	 */
	private function parse_preset_amounts( string $raw ): array {
		if ( empty( $raw ) ) {
			return array( 1000, 2500, 5000, 10000 );
		}

		$amounts = explode( ',', $raw );
		$parsed  = array();

		foreach ( $amounts as $amount ) {
			$int_amount = (int) trim( $amount );
			if ( $int_amount > 0 ) {
				$parsed[] = $int_amount;
			}
		}

		return ! empty( $parsed ) ? $parsed : array( 1000, 2500, 5000, 10000 );
	}

	/**
	 * Recursive settings sanitization.
	 *
	 * @param array $settings Raw settings.
	 * @return array
	 */
	private function sanitize( array $settings ): array {
		$sanitized = array();

		foreach ( $settings as $key => $value ) {
			if ( is_array( $value ) ) {
				$sanitized[ $key ] = $this->sanitize( $value );
			} elseif ( is_string( $value ) ) {
				$sanitized[ $key ] = sanitize_text_field( $value );
			} elseif ( is_numeric( $value ) ) {
				$sanitized[ $key ] = is_int( $value ) ? absint( $value ) : floatval( $value );
			} elseif ( is_bool( $value ) ) {
				$sanitized[ $key ] = (bool) $value;
			} else {
				$sanitized[ $key ] = sanitize_text_field( (string) $value );
			}
		}

		return $sanitized;
	}

	/**
	 * Deep merge arrays with defaults taking priority for missing keys.
	 *
	 * @param array $defaults Default values.
	 * @param array $stored   Stored values.
	 * @return array
	 */
	private function deep_merge( array $defaults, array $stored ): array {
		foreach ( $defaults as $key => $default_value ) {
			if ( ! isset( $stored[ $key ] ) ) {
				$stored[ $key ] = $default_value;
			} elseif ( is_array( $default_value ) && is_array( $stored[ $key ] ) ) {
				$stored[ $key ] = $this->deep_merge( $default_value, $stored[ $key ] );
			}
		}

		return $stored;
	}
}
