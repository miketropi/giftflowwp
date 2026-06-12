<?php
/**
 * Pattern Registry for GiftFlow.
 *
 * Auto-discovers and registers WordPress block patterns from the
 * patterns/ directory at the plugin root.
 *
 * @package GiftFlow
 * @subpackage Patterns
 */

namespace GiftFlow\Patterns;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Auto-discovers .php pattern files and registers them as block patterns.
 *
 * Each file in patterns/ should contain a WordPress-style header comment
 * followed by the block markup. Categories can be registered via a
 * patterns/categories.php file that returns an array of category arrays.
 */
class PatternRegistry extends AbstractModule {

	/**
	 * Patterns directory path.
	 *
	 * @var string
	 */
	private string $patterns_dir;

	/**
	 * Constructor.
	 *
	 * @param Container $container Service container.
	 */
	public function __construct( Container $container ) {
		parent::__construct( $container );
		$this->patterns_dir = $this->plugin_dir . 'patterns/';
	}

	/**
	 * Register the init hook. Skips if the patterns directory is missing.
	 *
	 * @return void
	 */
	public function register(): void {
		if ( ! is_dir( $this->patterns_dir ) ) {
			return;
		}

		add_action( 'init', array( $this, 'register_patterns' ) );
	}

	/**
	 * Discover and register all block patterns and categories.
	 *
	 * @return void
	 */
	public function register_patterns(): void {
		$this->register_pattern_categories();

		$pattern_files = glob( $this->patterns_dir . '*.php' );

		if ( empty( $pattern_files ) ) {
			return;
		}

		foreach ( $pattern_files as $file ) {
			if ( basename( $file ) === 'categories.php' ) {
				continue;
			}

			$pattern_data = $this->parse_pattern_file( $file );

			if ( empty( $pattern_data ) ) {
				continue;
			}

			register_block_pattern( $pattern_data['slug'], $pattern_data['args'] );
		}

		do_action( 'giftflow_patterns_registered' );
	}

	/**
	 * Register block pattern categories from patterns/categories.php.
	 *
	 * @return void
	 */
	private function register_pattern_categories(): void {
		$categories_file = $this->patterns_dir . 'categories.php';

		if ( ! file_exists( $categories_file ) ) {
			return;
		}

		$categories = require $categories_file;

		if ( ! is_array( $categories ) ) {
			return;
		}

		foreach ( $categories as $category ) {
			if ( ! isset( $category['slug'], $category['label'] ) ) {
				continue;
			}

			register_block_pattern_category( $category['slug'], $category );
		}
	}

	/**
	 * Parse a pattern PHP file for headers and content.
	 *
	 * @param string $file Absolute path to the pattern file.
	 * @return array{slug: string, args: array}|null Pattern data or null on failure.
	 */
	private function parse_pattern_file( string $file ): ?array {
		$headers = array(
			'title'         => 'Title',
			'slug'          => 'Slug',
			'description'   => 'Description',
			'categories'    => 'Categories',
			'keywords'      => 'Keywords',
			'viewportWidth' => 'Viewport Width',
			'blockTypes'    => 'Block Types',
			'inserter'      => 'Inserter',
		);

		$pattern_meta = get_file_data( $file, $headers );

		if ( empty( $pattern_meta['slug'] ) || empty( $pattern_meta['title'] ) ) {
			return null;
		}

		$content = $this->extract_pattern_content( $file );

		$args = array(
			'title'   => $pattern_meta['title'],
			'content' => $content,
		);

		if ( ! empty( $pattern_meta['description'] ) ) {
			$args['description'] = $pattern_meta['description'];
		}

		if ( ! empty( $pattern_meta['categories'] ) ) {
			$args['categories'] = array_map( 'trim', explode( ',', $pattern_meta['categories'] ) );
		}

		if ( ! empty( $pattern_meta['keywords'] ) ) {
			$args['keywords'] = array_map( 'trim', explode( ',', $pattern_meta['keywords'] ) );
		}

		if ( ! empty( $pattern_meta['viewportWidth'] ) ) {
			$args['viewportWidth'] = (int) $pattern_meta['viewportWidth'];
		}

		if ( ! empty( $pattern_meta['blockTypes'] ) ) {
			$args['blockTypes'] = array_map( 'trim', explode( ',', $pattern_meta['blockTypes'] ) );
		}

		if ( ! empty( $pattern_meta['inserter'] ) ) {
			$args['inserter'] = 'yes' === strtolower( $pattern_meta['inserter'] );
		}

		return array(
			'slug' => $pattern_meta['slug'],
			'args' => $args,
		);
	}

	/**
	 * Extract pattern content from a PHP file, stripping opening tag and header.
	 *
	 * @param string $file Absolute path to the pattern file.
	 * @return string Pattern HTML content.
	 */
	private function extract_pattern_content( string $file ): string {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$content = file_get_contents( $file );

		if ( empty( $content ) ) {
			return '';
		}

		$content = preg_replace( '/^<\?php\s*/', '', $content );
		$content = preg_replace( '/\/\*\*.*?\*\/\s*/s', '', $content, 1 );
		$content = preg_replace( '/^\?>\s*/', '', $content );

		return trim( $content );
	}
}
