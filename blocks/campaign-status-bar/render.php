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
	<?php
		$gf_goal   = (float) ( $gf_data['goal_amount'] ?? 0 );
		$gf_raised = (float) ( $gf_data['raised_amount'] ?? 0 );

		// Fallback: if raw values are 0 but formatted display shows amounts,
		//           re-fetch directly from the campaign meta.
		if ( $gf_goal <= 0 && ! empty( $gf_data['goal_amount_formatted'] ) ) {
			$gf_goal = (float) get_post_meta( $gf_campaign_id, '_goal_amount', true );
		}
		if ( $gf_raised <= 0 && ! empty( $gf_data['raised_amount_formatted'] ) ) {
			$gf_raised = (float) giftflow_get_campaign_raised_amount( $gf_campaign_id );
		}

		$gf_pct = $gf_goal > 0 ? (int) round( ( $gf_raised / $gf_goal ) * 100 ) : 0;
		$gf_progress_color = $attributes['progressColor'] ?? '';
		$gf_fill_style = 'width:' . $gf_pct . '%;';
		if ( $gf_progress_color ) {
			$gf_fill_style .= 'background-color:' . esc_attr( $gf_progress_color ) . ';';
		}
	?>
	<div class="giftflow-campaign-status-bar__progress">
		<div
			class="giftflow-campaign-status-bar__progress-fill"
			style="<?php echo esc_attr( $gf_fill_style ); ?>"
			role="progressbar"
			aria-valuenow="<?php echo $gf_pct; ?>"
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

		<?php
			// Fallback: compute days_left directly if helper didn't provide it.
			$gf_days = $gf_data['days_left'] ?? '';
			if ( '' === $gf_days ) {
				$gf_end   = get_post_meta( $gf_campaign_id, '_end_date', true );
				$gf_start = get_post_meta( $gf_campaign_id, '_start_date', true );
				if ( $gf_start && $gf_end ) {
					$gf_now   = current_time( 'timestamp' );
					$gf_end_ts = strtotime( $gf_end );
					if ( $gf_end_ts > $gf_now ) {
						$gf_days = (int) ceil( ( $gf_end_ts - $gf_now ) / DAY_IN_SECONDS );
					} elseif ( $gf_end_ts < $gf_now ) {
						$gf_days = 0;
					}
				}
			}
		?>
		<?php if ( '' !== $gf_days && false !== $gf_days ) : ?>
			<span class="giftflow-campaign-status-bar__days">
				<?php if ( 0 === (int) $gf_days ) : ?>
					<?php esc_html_e( 'Ended', 'giftflow' ); ?>
				<?php else : ?>
					<?php
					printf(
						/* translators: %s: days left string */
						esc_html( _n( '%s day left', '%s days left', (int) $gf_days, 'giftflow' ) ),
						esc_html( (string) $gf_days )
					);
					?>
				<?php endif; ?>
			</span>
		<?php endif; ?>
	</div>
</div>
