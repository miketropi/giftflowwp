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

$gf_campaign_id = (int) ( $attributes['campaignId'] ?? 0 );

$gf_post_id = $gf_campaign_id > 0
	? $gf_campaign_id
	: ( isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID() );

if ( ! $gf_post_id ) {
	return;
}

$gf_featured_id  = get_post_thumbnail_id( $gf_post_id );
$gf_gallery_meta = get_post_meta( $gf_post_id, '_gallery', true );
$gf_gallery_ids  = ! empty( $gf_gallery_meta )
	? array_filter( array_map( 'intval', explode( ',', $gf_gallery_meta ) ) )
	: array();

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
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore
	esc_html_e( 'No images available.', 'giftflow' );
	echo '</div>';
	return;
}

$gf_images     = array();
$gf_large_size = apply_filters( 'giftflow_campaign_single_images_size', 'large', $gf_post_id );

foreach ( $gf_all_image_ids as $gf_image_id ) {
	$gf_images[] = array(
		'id'        => $gf_image_id,
		'full_url'  => wp_get_attachment_image_url( $gf_image_id, 'full' ),
		'large_url' => wp_get_attachment_image_url( $gf_image_id, $gf_large_size ),
		'thumb_url' => wp_get_attachment_image_url( $gf_image_id, 'thumbnail' ),
		'alt'       => get_post_meta( $gf_image_id, '_wp_attachment_image_alt', true ) ? get_post_meta( $gf_image_id, '_wp_attachment_image_alt', true ) : '',
	);
}

$gf_total      = count( $gf_images );
$gf_is_single  = 1 === $gf_total;
$gf_active_idx = 0;
$gf_active     = $gf_images[0];
// Show first 4 thumbs, hide the rest behind +N.
$gf_visible_thumbs = min( 4, $gf_total );
$gf_hidden_count   = max( 0, $gf_total - $gf_visible_thumbs );

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-campaign-images' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore ?>>
	<?php if ( $gf_is_single ) : ?>
		<div class="giftflow-campaign-images__single">
			<img
				src="<?php echo esc_url( $gf_active['large_url'] ); ?>"
				alt="<?php echo esc_attr( $gf_active['alt'] ); ?>"
				class="giftflow-campaign-images__main-img"
			/>
		</div>
	<?php else : ?>
		<div class="giftflow-campaign-images__main">
			<img
				src="<?php echo esc_url( $gf_active['large_url'] ); ?>"
				alt="<?php echo esc_attr( $gf_active['alt'] ); ?>"
				class="giftflow-campaign-images__main-img"
				data-pswp-src="<?php echo esc_url( $gf_active['full_url'] ); ?>"
			/>
			<div class="giftflow-campaign-images__overlay" aria-hidden="true">
				<div class="giftflow-campaign-images__overlay-icon">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6M8 11h6"/></svg>
				</div>
			</div>
			<div class="giftflow-campaign-images__counter">1 / <?php echo (int) $gf_total; ?></div>
		</div>

		<div class="giftflow-campaign-images__thumbs">
			<?php foreach ( $gf_images as $gf_i => $gf_img ) : ?>
				<button
					class="giftflow-campaign-images__thumb<?php echo 0 === $gf_i ? ' giftflow-campaign-images__thumb--active' : ''; ?><?php echo $gf_i >= $gf_visible_thumbs ? ' giftflow-campaign-images__thumb--hidden' : ''; ?>"
					data-image-url="<?php echo esc_url( $gf_img['large_url'] ); ?>"
					data-image-full-url="<?php echo esc_url( $gf_img['full_url'] ); ?>"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %d: image number */ __( 'View image %d', 'giftflow' ), $gf_i + 1 ) ); ?>"
				>
					<img src="<?php echo esc_url( $gf_img['thumb_url'] ); ?>" alt="<?php echo esc_attr( $gf_img['alt'] ); ?>" loading="lazy" />
				</button>
			<?php endforeach; ?>

			<?php if ( $gf_hidden_count > 0 ) : ?>
				<button class="giftflow-campaign-images__thumb giftflow-campaign-images__thumb--more">
					<span>+<?php echo (int) $gf_hidden_count; ?></span>
				</button>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
