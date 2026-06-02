<?php
/**
 * Render callback for the Donation Button block.
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

$gf_button_text   = $attributes['buttonText'] ?? __( 'Donate Now', 'giftflow' );
$gf_is_full_width = ! empty( $attributes['fullWidth'] );

$gf_campaign_status = get_post_status( $gf_campaign_id );
$gf_is_published    = 'publish' === $gf_campaign_status;
$gf_is_disabled     = ! $gf_is_published || 0 === $gf_campaign_id;

$gf_wrapper_classes = array( 'giftflow-donation-button' );
$gf_button_classes  = array( 'giftflow-donation-button__btn' );

if ( $gf_is_full_width ) {
	$gf_button_classes[] = 'giftflow-donation-button__btn--full-width';
}

if ( $gf_is_disabled ) {
	$gf_button_classes[] = 'giftflow-donation-button__btn--disabled';
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => implode( ' ', $gf_wrapper_classes ),
	)
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?>>
	<button
		class="<?php echo esc_attr( implode( ' ', $gf_button_classes ) ); ?>"
		data-campaign-id="<?php echo esc_attr( $gf_campaign_id ); ?>"
		data-campaign-title="<?php echo esc_attr( get_the_title( $gf_campaign_id ) ); ?>"
		<?php echo $gf_is_disabled ? 'disabled' : ''; ?>
		<?php if ( ! $gf_is_disabled ) : ?>
			onclick="giftflow.donationButton_Handle(this)"
		<?php endif; ?>
	>
		<?php echo esc_html( $gf_button_text ); ?>
	</button>
</div>
