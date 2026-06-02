<?php
/**
 * Centralized hook registry for GiftFlow.
 *
 * All plugin hooks are documented here as class constants
 * for discoverability and to prevent typos.
 *
 * @package GiftFlow
 * @subpackage Core
 */

namespace GiftFlow\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook name constants and registration helper.
 */
final class HookManager {

	/**
	 * Fires when a new donation is created.
	 *
	 * @param int $donation_id The donation post ID.
	 */
	public const DONATION_CREATED = 'giftflow/donation.created';

	/**
	 * Fires when a donation status changes to "completed".
	 *
	 * @param int $donation_id The donation post ID.
	 */
	public const DONATION_COMPLETED = 'giftflow/donation.completed';

	/**
	 * Fires when a donation is refunded.
	 *
	 * @param int $donation_id The donation post ID.
	 */
	public const DONATION_REFUNDED = 'giftflow/donation.refunded';

	/**
	 * Fires when a donation fails.
	 *
	 * @param int    $donation_id The donation post ID.
	 * @param string $error       Error message.
	 */
	public const DONATION_FAILED = 'giftflow/donation.failed';

	/**
	 * Fires when a campaign is published.
	 *
	 * @param int $campaign_id The campaign post ID.
	 */
	public const CAMPAIGN_PUBLISHED = 'giftflow/campaign.published';

	/**
	 * Fires when a campaign ends.
	 *
	 * @param int $campaign_id The campaign post ID.
	 */
	public const CAMPAIGN_ENDED = 'giftflow/campaign.ended';

	/**
	 * Filter to register additional services in the container.
	 *
	 * @param array<class-string, callable|object> $services Map of class => factory.
	 */
	public const FILTER_SERVICES = 'giftflow.services';

	/**
	 * Filter to register additional Gutenberg blocks.
	 *
	 * @param array<string, string> $blocks Map of block-name => block-dir.
	 */
	public const FILTER_BLOCKS = 'giftflow.blocks';

	/**
	 * Filter to register additional block templates.
	 *
	 * @param array $templates List of template file paths.
	 */
	public const FILTER_BLOCK_TEMPLATES = 'giftflow.block_templates';

	/**
	 * Filter to register additional payment gateways.
	 *
	 * @param array<string, string> $gateways Map of gateway-id => class-name.
	 */
	public const FILTER_GATEWAYS = 'giftflow.gateways';

	/**
	 * Filter plugin settings before save.
	 *
	 * @param array $settings The settings array.
	 */
	public const FILTER_SETTINGS_SAVE = 'giftflow/settings.save';

	/**
	 * Filter to modify donation form fields.
	 *
	 * @param array $fields The form fields.
	 */
	public const FILTER_DONATION_FORM_FIELDS = 'giftflow/donation_form.fields';

	/**
	 * Action: daily log cleanup cron.
	 */
	public const CRON_LOG_CLEANUP = 'giftflow_cleanup_logs';
}
