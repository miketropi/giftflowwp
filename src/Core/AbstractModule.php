<?php
/**
 * Abstract module base class for GiftFlow.
 *
 * Every domain module extends this class and implements register()
 * to add its hooks in one place — no implicit side-effects at require time.
 *
 * @package GiftFlow
 * @subpackage Core
 */

namespace GiftFlow\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for all GiftFlow modules.
 */
abstract class AbstractModule {

	/**
	 * Service container instance.
	 *
	 * @var Container
	 */
	protected Container $container;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected string $version;

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	protected string $plugin_dir;

	/**
	 * Plugin directory URL.
	 *
	 * @var string
	 */
	protected string $plugin_url;

	/**
	 * Constructor.
	 *
	 * @param Container $container Service container.
	 */
	public function __construct( Container $container ) {
		$this->container  = $container;
		$this->version    = GIFTFLOW_VERSION;
		$this->plugin_dir = GIFTFLOW_PLUGIN_DIR;
		$this->plugin_url = GIFTFLOW_PLUGIN_URL;
	}

	/**
	 * Register WordPress hooks for this module.
	 *
	 * Called once during plugin boot. All add_action() / add_filter()
	 * calls should happen here.
	 *
	 * @return void
	 */
	abstract public function register(): void;
}
