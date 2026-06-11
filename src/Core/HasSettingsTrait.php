<?php
/**
 * HasSettingsTrait — provides settings integration for any class.
 *
 * @package GiftFlow
 * @subpackage Core
 */

namespace GiftFlow\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait for classes that need access to the SettingsRegistry.
 */
trait HasSettingsTrait {

	/**
	 * SettingsRegistry instance.
	 *
	 * @var \GiftFlow\Settings\SettingsRegistry|null
	 */
	protected $settings_registry = null;

	/**
	 * Get a setting value with campaign/block override resolution.
	 *
	 * @param string     $key          Setting key.
	 * @param int|null   $campaign_id  Optional campaign ID.
	 * @param array|null $block_attrs  Optional block instance attributes.
	 * @return mixed
	 */
	protected function get_setting( string $key, ?int $campaign_id = null, ?array $block_attrs = null ) {
		if ( null === $this->settings_registry ) {
			return null;
		}

		return $this->settings_registry->get( $key, $campaign_id, $block_attrs );
	}

	/**
	 * Get all settings groups.
	 *
	 * @return array
	 */
	protected function get_all_settings(): array {
		if ( null === $this->settings_registry ) {
			return array();
		}

		return $this->settings_registry->get_all();
	}

	/**
	 * Get a settings group.
	 *
	 * @param string $group Group key.
	 * @return array
	 */
	protected function get_settings_group( string $group ): array {
		if ( null === $this->settings_registry ) {
			return array();
		}

		return $this->settings_registry->get_group( $group );
	}

	/**
	 * Inject the settings registry.
	 *
	 * @param \GiftFlow\Settings\SettingsRegistry $registry Settings registry.
	 * @return void
	 */
	public function set_settings_registry( \GiftFlow\Settings\SettingsRegistry $registry ): void {
		$this->settings_registry = $registry;
	}
}
