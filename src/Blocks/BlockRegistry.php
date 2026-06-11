<?php
/**
 * Block Registry — auto-discovers and registers Gutenberg blocks from block.json.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

namespace GiftFlow\Blocks;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Scans the blocks/ directory for block.json files and registers
 * each block via register_block_type_from_metadata().
 */
class BlockRegistry extends AbstractModule {

	/**
	 * Blocks directory path.
	 *
	 * @var string
	 */
	private string $blocks_dir;

	/**
	 * Constructor.
	 *
	 * @param Container $container Service container.
	 */
	public function __construct( Container $container ) {
		parent::__construct( $container );
		$this->blocks_dir = $this->plugin_dir . 'blocks/';
	}

	/**
	 * Register the init hook for block registration.
	 *
	 * Priority 9 ensures block.json-based registration runs before
	 * the legacy block.php registrations (priority 10), so block.json
	 * metadata takes precedence.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_blocks' ), 9 );
	}

	/**
	 * Discover and register all blocks from block.json files.
	 *
	 * Also processes third-party blocks registered via the
	 * `giftflow.blocks` filter. Skips blocks already registered
	 * by the legacy loader (block.php files loaded via blocks/index.php).
	 *
	 * @return void
	 */
	public function register_blocks(): void {
		$block_dirs = glob( $this->blocks_dir . '*/', GLOB_ONLYDIR );

		if ( empty( $block_dirs ) ) {
			return;
		}

		foreach ( $block_dirs as $block_dir ) {
			$block_json = $block_dir . 'block.json';

			if ( ! file_exists( $block_json ) ) {
				continue;
			}

			register_block_type_from_metadata( $block_dir );
		}

		// Register third-party blocks from the filter.
		$third_party_blocks = apply_filters( 'giftflow.blocks', array() );

		if ( ! empty( $third_party_blocks ) && is_array( $third_party_blocks ) ) {
			foreach ( $third_party_blocks as $block_dir ) {
				if ( is_string( $block_dir ) && file_exists( $block_dir . '/block.json' ) ) {
					register_block_type_from_metadata( $block_dir );
				}
			}
		}

		do_action( 'giftflow.blocks_registered' );
	}

	/**
	 * Get all registered GiftFlow block names.
	 *
	 * @return array<int, string>
	 */
	public function get_registered_blocks(): array {
		$blocks    = array();
		$block_dirs = glob( $this->blocks_dir . '*/', GLOB_ONLYDIR );

		if ( empty( $block_dirs ) ) {
			return $blocks;
		}

		foreach ( $block_dirs as $block_dir ) {
			$block_json = $block_dir . 'block.json';

			if ( ! file_exists( $block_json ) ) {
				continue;
			}

			$json = json_decode( file_get_contents( $block_json ), true );

			if ( isset( $json['name'] ) ) {
				$blocks[] = $json['name'];
			}
		}

		return $blocks;
	}
}
