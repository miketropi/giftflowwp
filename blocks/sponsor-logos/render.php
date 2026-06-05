<?php
/**
 * Render callback for the Sponsor Logos block.
 *
 * Outputs an auto-scrolling marquee of sponsor/partner logos.
 * Logos are duplicated so the scroll loops seamlessly via CSS.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_logos       = $attributes['logos'] ?? array();
$gf_scroll_speed = max( 5, min( 60, (int) ( $attributes['scrollSpeed'] ?? 20 ) ) );
$gf_gap         = max( 8, min( 120, (int) ( $attributes['gap'] ?? 48 ) ) );
$gf_logo_height = max( 24, min( 160, (int) ( $attributes['logoHeight'] ?? 60 ) ) );
$gf_direction   = $attributes['direction'] ?? 'left';

if ( empty( $gf_logos ) || ! is_array( $gf_logos ) ) {
	$block_wrapper_attrs = get_block_wrapper_attributes(
		array( 'class' => 'giftflow-sponsor-logos giftflow-sponsor-logos--empty' )
	);
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html_e( 'Add sponsor logos in the block sidebar.', 'giftflow' );
	echo '</div>';
	return;
}

$gf_dir_class = 'right' === $gf_direction
	? 'giftflow-sponsor-logos--dir-right'
	: '';

$gf_inline_styles = sprintf(
	'--gf-sponsor-speed:%ds;--gf-sponsor-gap:%dpx;--gf-sponsor-height:%dpx;',
	$gf_scroll_speed,
	$gf_gap,
	$gf_logo_height
);

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => 'giftflow-sponsor-logos ' . $gf_dir_class,
		'style' => $gf_inline_styles,
	)
);

$gf_images = array();

foreach ( $gf_logos as $gf_logo ) {
	$gf_url = '';

	if ( ! empty( $gf_logo['id'] ) ) {
		$gf_url = wp_get_attachment_image_url( (int) $gf_logo['id'], 'medium' );
	}

	if ( empty( $gf_url ) && ! empty( $gf_logo['url'] ) ) {
		$gf_url = esc_url( $gf_logo['url'] );
	}

	if ( empty( $gf_url ) ) {
		continue;
	}

	$gf_alt = ! empty( $gf_logo['alt'] )
		? esc_attr( $gf_logo['alt'] )
		: esc_attr__( 'Sponsor logo', 'giftflow' );

	$gf_images[] = array(
		'url' => $gf_url,
		'alt' => $gf_alt,
	);
}

if ( empty( $gf_images ) ) {
	$block_wrapper_attrs = get_block_wrapper_attributes(
		array( 'class' => 'giftflow-sponsor-logos giftflow-sponsor-logos--empty' )
	);
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html_e( 'Add sponsor logos in the block sidebar.', 'giftflow' );
	echo '</div>';
	return;
}
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="giftflow-sponsor-logos__track">
		<?php
		foreach ( $gf_images as $gf_img ) :
			?>
			<div class="giftflow-sponsor-logos__item">
				<img
					src="<?php echo esc_url( $gf_img['url'] ); ?>"
					alt="<?php echo esc_attr( $gf_img['alt'] ); ?>"
					loading="lazy"
				/>
			</div>
			<?php
		endforeach;

		foreach ( $gf_images as $gf_img ) :
			?>
			<div class="giftflow-sponsor-logos__item" aria-hidden="true">
				<img
					src="<?php echo esc_url( $gf_img['url'] ); ?>"
					alt="<?php echo esc_attr( $gf_img['alt'] ); ?>"
					loading="lazy"
				/>
			</div>
			<?php
		endforeach;
		?>
	</div>
</div>
