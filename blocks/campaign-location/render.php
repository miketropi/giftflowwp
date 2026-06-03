<?php
/**
 * Render callback for the Campaign Location block.
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
	$block_wrapper_attrs = get_block_wrapper_attributes();
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html_e( 'No campaign found.', 'giftflow' );
	echo '</div>';
	return;
}

$gf_location = get_post_meta( $gf_post_id, '_location', true );

if ( empty( $gf_location ) ) {
	return;
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-campaign-location' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?>>
	<div class="giftflow-campaign-location__inner">
		<svg class="giftflow-campaign-location__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
			<circle cx="12" cy="10" r="3"/>
		</svg>
		<span class="giftflow-campaign-location__text"><?php echo esc_html( $gf_location ); ?></span>
	</div>
</div>
