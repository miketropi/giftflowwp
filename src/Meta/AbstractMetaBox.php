<?php
/**
 * Abstract Meta Box — base for all admin meta box registrations.
 *
 * @package GiftFlow
 * @subpackage Meta
 */

namespace GiftFlow\Meta;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for meta boxes with nonce verification and field rendering.
 */
abstract class AbstractMetaBox extends AbstractModule {

	/**
	 * Meta box ID.
	 *
	 * @var string
	 */
	protected string $meta_id = '';

	/**
	 * Meta box title.
	 *
	 * @var string
	 */
	protected string $title = '';

	/**
	 * Target post type.
	 *
	 * @var string
	 */
	protected string $screen = '';

	/**
	 * Meta box context (normal, side, advanced).
	 *
	 * @var string
	 */
	protected string $context = 'normal';

	/**
	 * Meta box priority (high, core, default, low).
	 *
	 * @var string
	 */
	protected string $priority = 'high';

	/**
	 * Constructor.
	 *
	 * @param Container $container Service container.
	 */
	public function __construct( Container $container ) {
		parent::__construct( $container );
		$this->init();
	}

	/**
	 * Initialize meta box properties. Override in subclasses.
	 *
	 * @return void
	 */
	abstract protected function init(): void;

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'handle_save' ) );
	}

	/**
	 * Register the meta box.
	 *
	 * @return void
	 */
	public function add_meta_box(): void {
		if ( empty( $this->meta_id ) || empty( $this->screen ) ) {
			return;
		}

		add_meta_box(
			$this->meta_id,
			$this->title,
			array( $this, 'render' ),
			$this->screen,
			$this->context,
			$this->priority
		);
	}

	/**
	 * Render the meta box content.
	 *
	 * @param \WP_Post $post Post object.
	 * @return void
	 */
	abstract public function render( \WP_Post $post ): void;

	/**
	 * Handle save post action.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function handle_save( int $post_id ): void {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! $this->verify_nonce() ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$this->save( $post_id );
	}

	/**
	 * Save meta box data. Override in subclasses.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	abstract protected function save( int $post_id ): void;

	/**
	 * Get the fields configuration for this meta box.
	 *
	 * @return array
	 */
	abstract public function get_fields(): array;

	/**
	 * Verify the nonce for this meta box.
	 *
	 * @return bool
	 */
	protected function verify_nonce(): bool {
		$nonce_name = 'giftflow_meta_' . $this->meta_id . '_nonce';

		if ( ! isset( $_POST[ $nonce_name ] ) ) {
			return false;
		}

		return (bool) wp_verify_nonce(
			sanitize_key( wp_unslash( $_POST[ $nonce_name ] ) ),
			'giftflow_save_meta_' . $this->meta_id
		);
	}

	/**
	 * Render the nonce field.
	 *
	 * @return void
	 */
	protected function render_nonce(): void {
		wp_nonce_field(
			'giftflow_save_meta_' . $this->meta_id,
			'giftflow_meta_' . $this->meta_id . '_nonce'
		);
	}

	/**
	 * Get the meta box ID.
	 *
	 * @return string
	 */
	public function get_id(): string {
		return $this->meta_id;
	}

	/**
	 * Get the screen this meta box is registered for.
	 *
	 * @return string
	 */
	public function get_screen(): string {
		return $this->screen;
	}
}
