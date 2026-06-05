<?php
/**
 * Render callback for the Featured Campaign block.
 *
 * A two-column hero section. The campaign image occupies one column;
 * the other column renders InnerBlocks (headings, progress bars,
 * donate buttons — anything the user adds).
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_campaign_id = (int) ( $attributes['campaignId'] ?? 0 );

if ( 0 === $gf_campaign_id && isset( $block->context['postId'] ) ) {
	$gf_campaign_id = (int) $block->context['postId'];
}

if ( 0 === $gf_campaign_id ) {
	$gf_campaign_id = get_the_ID();
}

$gf_image_on_left = $attributes['imageOnLeft'] ?? false;
$gf_image_height  = max( 200, min( 600, (int) ( $attributes['imageHeight'] ?? 400 ) ) );
$gf_show_badge    = $attributes['showFeaturedBadge'] ?? true;
$gf_accent_color  = $attributes['accentColor'] ?? '';
$gf_border_radius_attr = (float) ( $attributes['borderRadius'] ?? 0 );

$gf_block_style   = $attributes['style'] ?? array();
$gf_border_radius = '';

if ( ! empty( $gf_block_style['border']['radius'] ) ) {
	$gf_border_radius = $gf_block_style['border']['radius'];
} elseif ( $gf_border_radius_attr > 0 ) {
	$gf_border_radius = (string) $gf_border_radius_attr . 'px';
}

$gf_skeleton_img_style = 'min-height:' . $gf_image_height . 'px;';
if ( $gf_border_radius ) {
	$gf_skeleton_img_style .= 'border-radius:' . esc_attr( $gf_border_radius ) . ';';
}

$gf_layout_class = $gf_image_on_left
	? 'giftflow-featured-campaign--image-left'
	: 'giftflow-featured-campaign--image-right';

$gf_post = get_post( $gf_campaign_id );

if ( ! $gf_post || 'campaign' !== $gf_post->post_type || 'publish' !== $gf_post->post_status ) {
	$block_wrapper_attrs = get_block_wrapper_attributes(
		array(
			'class' => 'giftflow-featured-campaign giftflow-featured-campaign--empty giftflow-featured-campaign--image-left',
			'style' => $gf_border_radius_attr > 0 && empty( $gf_block_style['border']['radius'] ) ? 'border-radius:' . (int) $gf_border_radius_attr . 'px;' : '',
		)
	);
	?>
	<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="giftflow-featured-campaign__content-area">
			<div class="giftflow-featured-campaign__skeleton-heading"></div>
			<div class="giftflow-featured-campaign__skeleton-line giftflow-featured-campaign__skeleton-line--desc"></div>
			<div class="giftflow-featured-campaign__skeleton-line giftflow-featured-campaign__skeleton-line--desc"></div>
			<div class="giftflow-featured-campaign__skeleton-line giftflow-featured-campaign__skeleton-line--desc-short"></div>
			<div class="giftflow-featured-campaign__skeleton-bar"></div>
			<div class="giftflow-featured-campaign__skeleton-btn"></div>
		</div>
		<div class="giftflow-featured-campaign__image-area giftflow-featured-campaign__skeleton">
			<div class="giftflow-featured-campaign__skeleton-img" style="<?php echo esc_attr( $gf_skeleton_img_style ); ?>"></div>
			<?php if ( $gf_show_badge ) : ?>
				<span class="giftflow-featured-campaign__badge" style="<?php echo $gf_accent_color ? 'background:' . esc_attr( $gf_accent_color ) . ';color:#fff;' : ''; ?>">
					<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
					<?php esc_html_e( 'Featured', 'giftflow' ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return;
}

$gf_thumb         = get_the_post_thumbnail_url( $gf_campaign_id, 'large' );
$gf_title         = get_the_title( $gf_campaign_id );
$gf_media_type    = $attributes['mediaType'] ?? 'image';
$gf_video_url     = $attributes['videoUrl'] ?? '';
$gf_video_id      = (int) ( $attributes['videoId'] ?? 0 );
$gf_video_poster  = $attributes['videoPoster'] ?? '';

if ( $gf_video_id > 0 ) {
	$gf_attachment_url = wp_get_attachment_url( $gf_video_id );
	if ( $gf_attachment_url ) {
		$gf_video_url = $gf_attachment_url;
	}
}

$gf_is_video = 'video' === $gf_media_type && ! empty( $gf_video_url );
if ( $gf_is_video ) {
	$gf_is_youtube = false !== strpos( $gf_video_url, 'youtube.com' ) || false !== strpos( $gf_video_url, 'youtu.be' );
	$gf_is_vimeo   = false !== strpos( $gf_video_url, 'vimeo.com' );
}

$gf_inline_styles = '';

if ( $gf_accent_color ) {
	$gf_inline_styles .= '--giftflow--featured-accent:' . esc_attr( $gf_accent_color ) . ';';
	$gf_inline_styles .= '--giftflow--featured-badge-bg:' . esc_attr( $gf_accent_color ) . ';';
	$gf_inline_styles .= '--giftflow--featured-badge-fg:#ffffff;';
}

if ( $gf_border_radius_attr > 0 && empty( $gf_block_style['border']['radius'] ) ) {
	$gf_inline_styles .= 'border-radius:' . (int) $gf_border_radius_attr . 'px;';
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => 'giftflow-featured-campaign ' . $gf_layout_class,
		'style' => $gf_inline_styles,
	)
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div
		class="giftflow-featured-campaign__image-area<?php echo $gf_is_video ? ' giftflow-featured-campaign__image-area--video' : ''; ?>"
		style="min-height:<?php echo (int) $gf_image_height; ?>px;<?php echo $gf_border_radius ? 'border-radius:' . esc_attr( $gf_border_radius ) . ';' : ''; ?>"
		<?php if ( $gf_is_video ) : ?>
			data-video-type="<?php echo $gf_is_youtube ? 'youtube' : ( $gf_is_vimeo ? 'vimeo' : 'html5' ); // phpcs:ignore ?>"
		<?php endif; ?>
	>
		<?php if ( $gf_is_video ) : ?>
			<div class="giftflow-featured-campaign__video-controls">
				<button class="giftflow-featured-campaign__play-pause" aria-label="<?php esc_attr_e( 'Play / Pause', 'giftflow' ); ?>">
					<svg class="giftflow-featured-campaign__play-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="8,5 19,12 8,19" /></svg>
					<svg class="giftflow-featured-campaign__pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16" /><rect x="14" y="4" width="4" height="16" /></svg>
				</button>
				<span class="giftflow-featured-campaign__video-time">0:00 / 0:00</span>
			</div>
			<?php

			if ( $gf_is_youtube ) :
				$gf_embed_url = $gf_video_url;
				if ( false !== strpos( $gf_video_url, 'watch?v=' ) ) {
					$gf_embed_url = str_replace( 'watch?v=', 'embed/', $gf_video_url );
					$gf_embed_url = preg_replace( '/&.*$/', '', $gf_embed_url );
				} elseif ( false !== strpos( $gf_video_url, 'youtu.be/' ) ) {
					$gf_embed_url = str_replace( 'youtu.be/', 'youtube.com/embed/', $gf_video_url );
				}
				$gf_embed_url .= ( false === strpos( $gf_embed_url, '?' ) ? '?' : '&' ) . 'autoplay=1&mute=1&loop=1&controls=0&playsinline=1';
				?>
				<iframe
					class="giftflow-featured-campaign__video-iframe"
					src="<?php echo esc_url( $gf_embed_url ); ?>"
					title="<?php echo esc_attr( $gf_title ); ?>"
					frameborder="0"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen
				></iframe>
			<?php elseif ( $gf_is_vimeo ) : ?>
				<?php
				$gf_vimeo_id = '';
				if ( preg_match( '/vimeo\.com\/(\d+)/', $gf_video_url, $gf_matches ) ) {
					$gf_vimeo_id = $gf_matches[1];
				}
				?>
				<iframe
					class="giftflow-featured-campaign__video-iframe"
					src="<?php echo esc_url( 'https://player.vimeo.com/video/' . $gf_vimeo_id . '?autoplay=1&muted=1&loop=1&controls=0' ); ?>"
					title="<?php echo esc_attr( $gf_title ); ?>"
					frameborder="0"
					allow="autoplay; fullscreen; picture-in-picture"
					allowfullscreen
				></iframe>
			<?php else : ?>
				<video
					class="giftflow-featured-campaign__video-tag"
					src="<?php echo esc_url( $gf_video_url ); ?>"
					autoplay
					muted
					loop
					playsinline
					<?php echo $gf_video_poster ? 'poster="' . esc_url( $gf_video_poster ) . '"' : ''; ?>
				></video>
			<?php endif; ?>
		<?php else : ?>
			<a href="<?php echo esc_url( get_permalink( $gf_campaign_id ) ); ?>" aria-label="<?php echo esc_attr( $gf_title ); ?>">
				<?php if ( $gf_thumb ) : ?>
					<img
						src="<?php echo esc_url( $gf_thumb ); ?>"
						alt="<?php echo esc_attr( $gf_title ); ?>"
						loading="lazy"
					/>
				<?php else : ?>
					<div class="giftflow-featured-campaign__placeholder-img">
						<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
					</div>
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<?php if ( $gf_show_badge ) : ?>
			<span class="giftflow-featured-campaign__badge">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
				<?php esc_html_e( 'Featured', 'giftflow' ); ?>
			</span>
		<?php endif; ?>
	</div>

	<div class="giftflow-featured-campaign__content-area">
		<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $content;
		?>
	</div>
</div>
