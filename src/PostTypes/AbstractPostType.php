<?php
/**
 * Abstract Post Type — base for all custom post type registrations.
 *
 * @package GiftFlow
 * @subpackage PostTypes
 */

namespace GiftFlow\PostTypes;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template Method pattern: override init_post_type() in subclasses
 * to set post_type, labels, args, taxonomies, and admin columns.
 */
abstract class AbstractPostType extends AbstractModule {

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	protected string $post_type = '';

	/**
	 * Post type labels.
	 *
	 * @var array
	 */
	protected array $labels = array();

	/**
	 * Post type registration arguments.
	 *
	 * @var array
	 */
	protected array $args = array();

	/**
	 * Custom taxonomies to register.
	 *
	 * @var array
	 */
	protected array $taxonomies = array();

	/**
	 * Admin columns configuration.
	 *
	 * @var array
	 */
	protected array $admin_columns = array();

	/**
	 * Sortable admin columns.
	 *
	 * @var array
	 */
	protected array $sortable_columns = array();

	/**
	 * Constructor.
	 *
	 * @param Container $container Service container.
	 */
	public function __construct( Container $container ) {
		parent::__construct( $container );
		$this->init_post_type();
	}

	/**
	 * Initialize post type properties. Override in subclasses.
	 *
	 * @return void
	 */
	abstract protected function init_post_type(): void;

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );

		if ( ! empty( $this->admin_columns ) ) {
			add_filter( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'set_custom_columns' ) );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'render_custom_column' ), 10, 2 );

			if ( ! empty( $this->sortable_columns ) ) {
				add_filter( 'manage_edit-' . $this->post_type . '_sortable_columns', array( $this, 'set_sortable_columns' ) );
			}
		}
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register_post_type(): void {
		if ( empty( $this->post_type ) ) {
			return;
		}

		register_post_type( $this->post_type, $this->args );

		do_action( 'giftflow.post_type.registered', $this->post_type );
	}

	/**
	 * Register custom taxonomies.
	 *
	 * @return void
	 */
	public function register_taxonomies(): void {
		if ( empty( $this->taxonomies ) ) {
			return;
		}

		foreach ( $this->taxonomies as $taxonomy => $tax_args ) {
			register_taxonomy( $taxonomy, $this->post_type, $tax_args );
		}
	}

	/**
	 * Set custom admin columns.
	 *
	 * @param array $columns Default columns.
	 * @return array
	 */
	public function set_custom_columns( array $columns ): array {
		$new_columns = array();

		foreach ( $this->admin_columns as $key => $label ) {
			$new_columns[ $key ] = $label;
		}

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Render custom admin column content.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public function render_custom_column( string $column, int $post_id ): void {
		$method = 'render_column_' . $column;

		if ( method_exists( $this, $method ) ) {
			$this->{$method}( $post_id );
		}

		do_action( 'giftflow.post_type.render_column', $column, $post_id, $this->post_type );
	}

	/**
	 * Set sortable admin columns.
	 *
	 * @param array $columns Default sortable columns.
	 * @return array
	 */
	public function set_sortable_columns( array $columns ): array {
		return array_merge( $columns, $this->sortable_columns );
	}

	/**
	 * Get the post type slug.
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return $this->post_type;
	}
}
