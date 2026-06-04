<?php
/**
 * Render callback for the Volunteer CTA block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_heading     = $attributes['heading'] ?? __( 'Become a Volunteer', 'giftflow' );
$gf_description = $attributes['description'] ?? '';
$gf_button_text = $attributes['buttonText'] ?? __( 'Register Now', 'giftflow' );
$gf_button_url  = $attributes['buttonUrl'] ?? '';
$gf_show_icon   = $attributes['showIcon'] ?? true;

$gf_description = wp_kses_post( wpautop( $gf_description ) );

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-volunteer-cta' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?> aria-labelledby="giftflow-volunteer-cta-heading">
	<?php if ( $gf_show_icon ) : ?>
		<div class="giftflow-volunteer-cta__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
			</svg>
		</div>
	<?php endif; ?>

	<h2 id="giftflow-volunteer-cta-heading" class="giftflow-volunteer-cta__heading">
		<?php echo esc_html( $gf_heading ); ?>
	</h2>

	<?php if ( ! empty( $gf_description ) ) : ?>
		<div class="giftflow-volunteer-cta__description">
			<?php echo $gf_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped via wp_kses_post. ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $gf_button_text ) ) : ?>
		<div class="giftflow-volunteer-cta__action">
			<a
				href="<?php echo esc_url( $gf_button_url ? $gf_button_url : '#' ); ?>"
				class="giftflow-volunteer-cta__button"
				<?php if ( ! $gf_button_url ) : ?>
					role="button"
				<?php endif; ?>
			>
				<?php echo esc_html( $gf_button_text ); ?>
			</a>
		</div>
	<?php endif; ?>
</div>
