<?php
/**
 * Centralized asset (script/style) registration for GiftFlow.
 *
 * Supports both legacy (assets/js/*.bundle.js, blocks-build/)
 * and new wp-scripts (build/) paths.
 *
 * @package GiftFlow
 * @subpackage Core
 */

namespace GiftFlow\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles all script and style enqueuing with localized data.
 */
class AssetLoader extends AbstractModule {

	/**
	 * Cached asset metadata (dependencies + version) from .asset.php files.
	 *
	 * @var array<string, array{dependencies: string[], version: string}>
	 */
	private array $asset_cache = array();

	/**
	 * Register all enqueuing hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_blocks' ) );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue_admin(): void {
		// New wp-scripts build output.
		$new_admin_js  = $this->plugin_dir . 'build/admin.js';
		$new_admin_css = $this->plugin_dir . 'build/admin.css';
		$new_asset     = $this->plugin_dir . 'build/admin.asset.php';

		// Legacy Laravel Mix output.
		$legacy_admin_js  = $this->plugin_dir . 'assets/js/admin.bundle.js';
		$legacy_admin_css = $this->plugin_dir . 'assets/css/admin.bundle.css';

		if ( file_exists( $new_admin_js ) ) {
			$asset = $this->get_asset_meta( $new_asset );
			wp_enqueue_script(
				'giftflow-admin',
				$this->plugin_url . 'build/admin.js',
				$asset['dependencies'],
				$asset['version'],
				true
			);
		} elseif ( file_exists( $legacy_admin_js ) ) {
			wp_enqueue_script(
				'giftflow-admin',
				$this->plugin_url . 'assets/js/admin.bundle.js',
				array( 'jquery', 'wp-element', 'react-jsx-runtime' ),
				$this->version,
				true
			);
		}

		if ( file_exists( $new_admin_css ) ) {
			wp_enqueue_style(
				'giftflow-admin',
				$this->plugin_url . 'build/admin.css',
				array(),
				$this->version
			);
		} elseif ( file_exists( $legacy_admin_css ) ) {
			wp_enqueue_style(
				'giftflow-admin',
				$this->plugin_url . 'assets/css/admin.bundle.css',
				array(),
				$this->version
			);
		}

		wp_localize_script(
			'giftflow-admin',
			'giftflow_admin',
			array(
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
				'nonce'           => wp_create_nonce( 'giftflow_admin_nonce' ),
				'rest_nonce'      => wp_create_nonce( 'wp_rest' ),
				'admin_url'       => admin_url(),
				'currency_symbol' => giftflow_get_global_currency_symbol(),
				'docs_url'        => trailingslashit( 'https://giftflow-doc.beplus-agency.cloud' ),
				'support_url'     => 'https://giftflow.beplus-agency.cloud/contact',
			)
		);
	}

	/**
	 * Enqueue frontend scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue_frontend(): void {
		$new_common = $this->plugin_dir . 'build/frontend-common.js';

		if ( file_exists( $new_common ) ) {
			$asset = $this->get_asset_meta( $this->plugin_dir . 'build/frontend-common.asset.php' );

			// Gateway scripts depend on the old 'giftflow-donation-forms' handle,
			// so register it as a stub that depends on our bundled common.js.
			wp_register_script(
				'giftflow-donation-forms',
				false,
				array( 'giftflow-common' ),
				$this->version,
				true
			);

			$deps = array_merge( array( 'jquery' ), $asset['dependencies'] );

			wp_enqueue_script(
				'giftflow-common',
				$this->plugin_url . 'build/frontend-common.js',
				$deps,
				$asset['version'],
				true
			);
		} else {
			$legacy_asset = $this->get_asset_meta( $this->plugin_dir . 'assets/js/common.bundle.asset.php' );
			wp_enqueue_script(
				'giftflow-common',
				$this->plugin_url . 'assets/js/common.bundle.js',
				array( 'jquery' ),
				$legacy_asset['version'],
				true
			);
		}

		wp_localize_script(
			'giftflow-common',
			'giftflow_common',
			array(
				'ajax_url'              => admin_url( 'admin-ajax.php' ),
				'nonce'                 => wp_create_nonce( 'giftflow_common_nonce' ),
				'click_to_copy_tooltip' => __( 'Click to copy', 'giftflow' ),
				'ajax_error'            => __( 'An error occurred. Please refresh the page and try again.', 'giftflow' ),
			)
		);
	}

	/**
	 * Enqueue block editor and frontend assets.
	 *
	 * WordPress handles block editor/view scripts defined in block.json.
	 * This method enqueues shared/common block styles only.
	 *
	 * @return void
	 */
	public function enqueue_blocks(): void {
		$this->register_block_editor_scripts();

		// Enqueue Swiper CSS for carousel block (frontend only).
		$swiper_css = $this->plugin_dir . 'build/blocks/campaigns-carousel-view.css';
		if ( file_exists( $swiper_css ) ) {
			wp_enqueue_style(
				'giftflow-block-campaigns-carousel-view',
				$this->plugin_url . 'build/blocks/campaigns-carousel-view.css',
				array(),
				$this->version
			);
		}

		// Enqueue Swiper CSS for sponsors testimonials carousel block (frontend only).
		$st_swiper_css = $this->plugin_dir . 'build/blocks/sponsors-testimonials-carousel-view.css';
		if ( file_exists( $st_swiper_css ) ) {
			wp_enqueue_style(
				'giftflow-block-sponsors-testimonials-carousel-view',
				$this->plugin_url . 'build/blocks/sponsors-testimonials-carousel-view.css',
				array(),
				$this->version
			);
		}

		$new_common_css = $this->plugin_dir . 'build/frontend-common.css';
		$legacy_common_css = $this->plugin_dir . 'assets/css/common.bundle.css';

		if ( file_exists( $new_common_css ) ) {
			wp_enqueue_style(
				'giftflow-common',
				$this->plugin_url . 'build/frontend-common.css',
				array(),
				$this->version
			);
		} elseif ( file_exists( $legacy_common_css ) ) {
			wp_enqueue_style(
				'giftflow-common',
				$this->plugin_url . 'assets/css/common.bundle.css',
				array(),
				$this->version
			);
		}
	}

	/**
	 * Register all block editor scripts with their .asset.php dependencies.
	 *
	 * @return void
	 */
	private function register_block_editor_scripts(): void {
		$blocks = array(
			'donation-button',
			'campaign-status-bar',
			'campaign-single-content',
			'campaign-single-images',
			'campaign-single-images-view',
			'campaigns-grid',
			'campaigns-carousel',
			'campaigns-carousel-view',
			'campaigns-carousel-2',
			'campaigns-carousel-2-view',
			'similar-campaign-carousel',
			'similar-campaign-carousel-view',
			'donor-account',
			'share',
			'thank-donor',
			'campaign-location',
			'volunteer-cta',
			'donation-faqs',
			'featured-campaign',
			'featured-campaign-view',
			'sponsor-logos',
			'campaign-card',
			'campaign-list',
			'sponsors-testimonials-carousel',
			'sponsors-testimonials-carousel-view',
		);

		foreach ( $blocks as $block_name ) {
			$handle      = 'giftflow-block-' . $block_name;
			$js_path     = $this->plugin_dir . 'build/blocks/' . $block_name . '.js';
			$asset_path  = $this->plugin_dir . 'build/blocks/' . $block_name . '.asset.php';
			$css_path    = $this->plugin_dir . 'build/blocks/' . $block_name . '.css';

			if ( ! file_exists( $js_path ) ) {
				continue;
			}

			$asset = $this->get_asset_meta( $asset_path );

			wp_register_script(
				$handle,
				$this->plugin_url . 'build/blocks/' . $block_name . '.js',
				$asset['dependencies'],
				$asset['version'],
				true
			);

			// Register matching CSS if webpack extracted it (e.g. Swiper styles).
			if ( file_exists( $css_path ) ) {
				wp_register_style(
					$handle,
					$this->plugin_url . 'build/blocks/' . $block_name . '.css',
					array(),
					$asset['version']
				);
			}
		}
	}

	/**
	 * Read and cache asset metadata from a .asset.php file.
	 *
	 * @param string $path Path to the .asset.php file.
	 * @return array{dependencies: string[], version: string}
	 */
	private function get_asset_meta( string $path ): array {
		if ( ! isset( $this->asset_cache[ $path ] ) ) {
			$this->asset_cache[ $path ] = file_exists( $path )
				? require $path
				: array(
					'dependencies' => array(),
					'version' => $this->version,
				);
		}

		return $this->asset_cache[ $path ];
	}
}
