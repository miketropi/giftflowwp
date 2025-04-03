<?php
/**
 * AJAX Handler Class
 *
 * Handles AJAX requests for the GiftFlowWP plugin.
 *
 * @package GiftFlowWP
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX Handler Class
 *
 * @since 1.0.0
 */
class GiftFlowWP_Ajax {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register AJAX actions
		add_action( 'wp_ajax_giftflowwp_get_gallery_images', array( $this, 'get_gallery_images' ) );
	}

	/**
	 * Get gallery images
	 */
	public function get_gallery_images() {
		// Check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'giftflowwp_gallery_nonce' ) ) {
			wp_send_json_error( __( 'Security check failed', 'giftflowwp' ) );
		}

		// Check if IDs are provided
		if ( ! isset( $_POST['ids'] ) || ! is_array( $_POST['ids'] ) ) {
			wp_send_json_error( __( 'No image IDs provided', 'giftflowwp' ) );
		}

		// Get image size
		$size = isset( $_POST['size'] ) ? sanitize_text_field( wp_unslash( $_POST['size'] ) ) : 'thumbnail';

		// Get image IDs
		$ids = array_map( 'intval', $_POST['ids'] );

		// Get image data
		$images = array();
		foreach ( $ids as $id ) {
			$image_url = wp_get_attachment_image_url( $id, $size );
			if ( $image_url ) {
				$images[ $id ] = array(
					'url' => $image_url,
					'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
				);
			}
		}

		// Send response
		wp_send_json_success( $images );
	}
}

// Initialize the AJAX handler
new GiftFlowWP_Ajax(); 