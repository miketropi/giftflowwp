<?php
/**
 * Render callback for the Campaign Single Images block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_post_id = isset( $block->context['postId'] )
	? (int) $block->context['postId']
	: get_the_ID();

if ( ! $gf_post_id ) {
	return;
}

$gf_featured_id   = get_post_thumbnail_id( $gf_post_id );
$gf_gallery_meta  = get_post_meta( $gf_post_id, '_gallery', true );
$gf_gallery_ids   = ! empty( $gf_gallery_meta ) ? array_filter( array_map( 'intval', explode( ',', $gf_gallery_meta ) ) ) : array();

$gf_all_image_ids = array();
if ( $gf_featured_id ) {
	$gf_all_image_ids[] = $gf_featured_id;
}
$gf_all_image_ids = array_unique( array_merge( $gf_all_image_ids, $gf_gallery_ids ) );

$gf_all_image_ids = apply_filters( 'giftflow_campaign_single_images', $gf_all_image_ids, $gf_post_id );

if ( empty( $gf_all_image_ids ) ) {
	$block_wrapper_attrs = get_block_wrapper_attributes(
		array( 'class' => 'giftflow-campaign-images giftflow-campaign-images--empty' )
	);
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html_e( 'No images available.', 'giftflow' );
	echo '</div>';
	return;
}

$gf_images        = array();
$gf_large_to_full = apply_filters( 'giftflow_campaign_single_images_size', 'large', $gf_post_id );

foreach ( $gf_all_image_ids as $gf_image_id ) {
	$gf_full_url  = wp_get_attachment_image_url( $gf_image_id, 'full' );
	$gf_large_url = wp_get_attachment_image_url( $gf_image_id, $gf_large_to_full );
	$gf_thumb_url = wp_get_attachment_image_url( $gf_image_id, 'thumbnail' );
	$gf_alt       = get_post_meta( $gf_image_id, '_wp_attachment_image_alt', true );

	$gf_images[] = array(
		'id'        => $gf_image_id,
		'full_url'  => $gf_full_url,
		'large_url' => $gf_large_url,
		'thumb_url' => $gf_thumb_url,
		'alt'       => $gf_alt ? $gf_alt : '',
	);
}

$gf_total_images   = count( $gf_images );
$gf_is_single      = 1 === $gf_total_images;
$gf_visible_thumbs = min( 3, $gf_total_images - 1 );
$gf_hidden_count   = max( 0, $gf_total_images - 1 - $gf_visible_thumbs );

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-campaign-images' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?> data-wp-interactive="giftflow/campaign-images">
	<?php if ( $gf_is_single ) : ?>
		<div class="giftflow-campaign-images__single">
			<img
				src="<?php echo esc_url( $gf_images[0]['large_url'] ); ?>"
				alt="<?php echo esc_attr( $gf_images[0]['alt'] ); ?>"
				class="giftflow-campaign-images__main-img"
				data-pswp-src="<?php echo esc_url( $gf_images[0]['full_url'] ); ?>"
				data-pswp-width="1200"
				data-pswp-height="800"
			/>
		</div>
	<?php else : ?>
		<div class="giftflow-campaign-images__main">
			<img
				src="<?php echo esc_url( $gf_images[0]['large_url'] ); ?>"
				alt="<?php echo esc_attr( $gf_images[0]['alt'] ); ?>"
				class="giftflow-campaign-images__main-img"
				data-pswp-src="<?php echo esc_url( $gf_images[0]['full_url'] ); ?>"
				data-pswp-width="1200"
				data-pswp-height="800"
			/>
		</div>

		<div class="giftflow-campaign-images__thumbs">
			<?php for ( $gf_i = 1; $gf_i <= $gf_visible_thumbs; $gf_i++ ) : ?>
				<button
					class="giftflow-campaign-images__thumb"
					data-wp-on--click="actions.openGallery"
					data-image-url="<?php echo esc_url( $gf_images[ $gf_i ]['large_url'] ); ?>"
					data-image-full-url="<?php echo esc_url( $gf_images[ $gf_i ]['full_url'] ); ?>"
					data-image-alt="<?php echo esc_attr( $gf_images[ $gf_i ]['alt'] ); ?>"
				>
					<img
						src="<?php echo esc_url( $gf_images[ $gf_i ]['thumb_url'] ); ?>"
						alt="<?php echo esc_attr( $gf_images[ $gf_i ]['alt'] ); ?>"
						data-pswp-src="<?php echo esc_url( $gf_images[ $gf_i ]['full_url'] ); ?>"
						data-pswp-width="1200"
						data-pswp-height="800"
					/>
				</button>
			<?php endfor; ?>

			<?php if ( $gf_hidden_count > 0 ) : ?>
				<button class="giftflow-campaign-images__thumb giftflow-campaign-images__thumb--more" data-wp-on--click="actions.expandGallery">
					<span>+<?php echo (int) $gf_hidden_count; ?></span>
				</button>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
