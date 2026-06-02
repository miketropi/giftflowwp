<?php
/**
 * Gateway interfaces for GiftFlow.
 *
 * Implements Interface Segregation — each gateway implements only
 * the interfaces relevant to its capabilities.
 *
 * @package GiftFlow
 * @subpackage Gateways
 */

namespace GiftFlow\Gateways;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core gateway identity contract.
 */
interface GatewayInterface {

	/**
	 * Get the gateway machine ID.
	 *
	 * @return string
	 */
	public function get_id(): string;

	/**
	 * Get the human-readable gateway title.
	 *
	 * @return string
	 */
	public function get_title(): string;

	/**
	 * Get the gateway description shown to users.
	 *
	 * @return string
	 */
	public function get_description(): string;

	/**
	 * Check if the gateway is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool;

	/**
	 * Get the gateway icon URL.
	 *
	 * @return string
	 */
	public function get_icon(): string;

	/**
	 * Get the display order (lower = first).
	 *
	 * @return int
	 */
	public function get_order(): int;

	/**
	 * Get the list of features this gateway supports.
	 *
	 * @return array<int, string>
	 */
	public function get_supports(): array;

	/**
	 * Render the gateway's payment form HTML.
	 *
	 * @param array $data Context data (campaign, amounts, etc.).
	 * @return string
	 */
	public function render_form( array $data ): string;
}

/**
 * Contract for gateways that can process payments.
 */
interface PaymentProcessorInterface {

	/**
	 * Process a payment for a donation.
	 *
	 * @param array $data        Payment data from the donation form.
	 * @param int   $donation_id The donation post ID.
	 * @return PaymentResult
	 */
	public function process_payment( array $data, int $donation_id ): PaymentResult;
}

/**
 * Contract for gateways that handle webhooks.
 */
interface WebhookHandlerInterface {

	/**
	 * Process an incoming webhook request.
	 *
	 * @return void
	 */
	public function handle_webhook(): void;

	/**
	 * Get the webhook endpoint URL.
	 *
	 * @return string
	 */
	public function get_webhook_url(): string;
}

/**
 * Contract for gateways that provide admin settings fields.
 */
interface SettingsProviderInterface {

	/**
	 * Get the settings fields for this gateway.
	 *
	 * @return array
	 */
	public function get_settings_fields(): array;

	/**
	 * Get the settings option key used by this gateway.
	 *
	 * @return string
	 */
	public function get_settings_key(): string;

	/**
	 * Get the current settings values.
	 *
	 * @return array
	 */
	public function get_settings(): array;
}
