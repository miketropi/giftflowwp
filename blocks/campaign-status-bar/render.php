<?php
/**
 * Render callback for the Campaign Status Bar block.
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

$gf_data = giftflow_prepare_campaign_status_bar_data( $gf_campaign_id );

if ( empty( $gf_data['post_id'] ) ) {
	$block_wrapper_attrs = get_block_wrapper_attributes(
		array( 'class' => 'giftflow-campaign-status-bar giftflow-campaign-status-bar--empty' )
	);
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html_e( 'No campaign selected.', 'giftflow' );
	echo '</div>';
	return;
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-campaign-status-bar' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?>>
	<div class="giftflow-campaign-status-bar__progress">
		<div
			class="giftflow-campaign-status-bar__progress-fill"
			style="width: <?php echo esc_attr( $gf_data['progress_percentage'] ); ?>%;"
			role="progressbar"
			aria-valuenow="<?php echo esc_attr( $gf_data['progress_percentage'] ); ?>"
			aria-valuemin="0"
			aria-valuemax="100"
		></div>
	</div>

	<div class="giftflow-campaign-status-bar__stats">
		<span class="giftflow-campaign-status-bar__raised">
			<?php echo wp_kses_post( $gf_data['raised_amount_formatted'] ); ?>
			<?php esc_html_e( 'raised of', 'giftflow' ); ?>
			<?php echo wp_kses_post( $gf_data['goal_amount_formatted'] ); ?>
		</span>

		<span class="giftflow-campaign-status-bar__donors">
			<?php if ( $gf_data['donation_count'] > 0 ) : ?>
				<?php
				printf(
					/* translators: %d: number of donors */
					esc_html( _n( '%d donor', '%d donors', $gf_data['donation_count'], 'giftflow' ) ),
					(int) $gf_data['donation_count']
				);
				?>
			<?php else : ?>
				<?php esc_html_e( 'No donors yet', 'giftflow' ); ?>
			<?php endif; ?>
		</span>

		<?php if ( ! empty( $gf_data['days_left'] ) ) : ?>
			<span class="giftflow-campaign-status-bar__days">
				<?php
				printf(
					/* translators: %s: days left string */
					esc_html__( '%s left', 'giftflow' ),
					esc_html( $gf_data['days_left'] )
				);
				?>
			</span>
		<?php endif; ?>
	</div>
</div>
