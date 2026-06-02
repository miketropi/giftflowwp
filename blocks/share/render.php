<?php
/**
 * Render callback for the Share block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_title        = $attributes['title'] ?? __( 'Share this', 'giftflow' );
$gf_custom_url   = $attributes['customUrl'] ?? '';
$gf_show_socials = $attributes['showSocials'] ?? true;
$gf_show_email   = $attributes['showEmail'] ?? true;
$gf_show_copy    = $attributes['showCopyUrl'] ?? true;

if ( ! empty( $gf_custom_url ) ) {
	$gf_share_url = esc_url( $gf_custom_url );
} elseif ( is_singular() ) {
	$gf_share_url = get_permalink();
} elseif ( is_home() || is_front_page() ) {
	$gf_share_url = home_url( '/' );
} elseif ( is_category() || is_tag() ) {
	$gf_share_url = get_term_link( get_queried_object_id() );
} else {
	$gf_share_url = home_url( add_query_arg( array() ) );
}

$gf_share_title = is_singular()
	? get_the_title()
	: get_bloginfo( 'name' );

$gf_share_description = is_singular()
	? wp_trim_words( get_the_excerpt(), 30 )
	: get_bloginfo( 'description' );

$gf_social_links = array(
	'facebook'  => array(
		'url'    => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $gf_share_url ),
		'label'  => __( 'Facebook', 'giftflow' ),
	),
	'x'         => array(
		'url'    => 'https://x.com/intent/tweet?url=' . rawurlencode( $gf_share_url ) . '&text=' . rawurlencode( $gf_share_title ),
		'label'  => __( 'X', 'giftflow' ),
	),
	'linkedin'  => array(
		'url'    => 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $gf_share_url ),
		'label'  => __( 'LinkedIn', 'giftflow' ),
	),
);

$gf_email_url = 'mailto:?subject=' . rawurlencode( $gf_share_title ) . '&body=' . rawurlencode( $gf_share_description . "\n\n" . $gf_share_url );

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-share' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?>>
	<?php if ( ! empty( $gf_title ) ) : ?>
		<span class="giftflow-share__title"><?php echo esc_html( $gf_title ); ?></span>
	<?php endif; ?>

	<div class="giftflow-share__buttons">
		<?php if ( $gf_show_socials ) : ?>
			<?php foreach ( $gf_social_links as $gf_platform => $gf_link ) : ?>
				<a
					href="<?php echo esc_url( $gf_link['url'] ); ?>"
					class="giftflow-share__btn giftflow-share__btn--<?php echo esc_attr( $gf_platform ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					title="<?php echo esc_attr( $gf_link['label'] ); ?>"
				>
					<?php echo esc_html( $gf_link['label'] ); ?>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( $gf_show_email ) : ?>
			<a
				href="<?php echo esc_url( $gf_email_url ); ?>"
				class="giftflow-share__btn giftflow-share__btn--email"
				title="<?php esc_attr_e( 'Share via email', 'giftflow' ); ?>"
			>
				<?php esc_html_e( 'Email', 'giftflow' ); ?>
			</a>
		<?php endif; ?>

		<?php if ( $gf_show_copy ) : ?>
			<button
				class="giftflow-share__btn giftflow-share__btn--copy"
				data-url="<?php echo esc_url( $gf_share_url ); ?>"
				onclick="giftflow.copyShareUrl(this)"
				title="<?php esc_attr_e( 'Copy link', 'giftflow' ); ?>"
			>
				<?php esc_html_e( 'Copy Link', 'giftflow' ); ?>
			</button>
			<span class="giftflow-share__copied" hidden>
				<?php esc_html_e( 'Copied!', 'giftflow' ); ?>
			</span>
		<?php endif; ?>
	</div>
</div>
