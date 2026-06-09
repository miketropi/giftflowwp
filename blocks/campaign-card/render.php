<?php
/**
 * Render callback for the Campaign Card block.
 *
 * IOS/macOS-inspired design with ring progress display,
 * frosted glass overlay mode, segmented presets, and clean typography.
 *
 * @package GiftFlow
 * @subpackage Blocks\CampaignCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_campaign_id = (int) ( $attributes['campaignId'] ?? 0 );

if ( 0 === $gf_campaign_id && isset( $block->context['postId'] ) ) {
	$gf_campaign_id = (int) $block->context['postId'];
}

$gf_card_style      = $attributes['cardStyle'] ?? 'classic';
$gf_show_image      = $attributes['showImage'] ?? true;
$gf_show_excerpt    = $attributes['showExcerpt'] ?? true;
$gf_show_progress   = $attributes['showProgress'] ?? true;
$gf_show_presets    = $attributes['showPresetAmounts'] ?? false;
$gf_show_button     = $attributes['showButton'] ?? true;
$gf_button_text     = $attributes['buttonText'] ?? __( 'Donate Now', 'giftflow' );
$gf_button_style    = $attributes['buttonStyle'] ?? 'filled';
$gf_button_full     = $attributes['buttonFullWidth'] ?? false;
$gf_accent_color    = $attributes['accentColor'] ?? '';
$gf_overlay_opacity = max( 10, min( 90, (int) ( $attributes['overlayOpacity'] ?? 60 ) ) );

$gf_post = $gf_campaign_id > 0 ? get_post( $gf_campaign_id ) : null;

$gf_inline_styles = '';
if ( $gf_accent_color ) {
	$gf_inline_styles .= '--gf-cc-accent:' . esc_attr( $gf_accent_color ) . ';';
	$gf_inline_styles .= '--gf-cc-accent-glow:' . esc_attr( $gf_accent_color ) . '2e;';
	$gf_inline_styles .= '--gf-cc-accent-soft:' . esc_attr( $gf_accent_color ) . '14;';
}
$gf_inline_styles .= '--gf-cc-overlay-opacity:' . ( $gf_overlay_opacity / 100 ) . ';';

$gf_classes = array( 'giftflow-campaign-card' );
$gf_classes[] = 'giftflow-campaign-card--' . $gf_card_style;
if ( ! $gf_show_image ) {
	$gf_classes[] = 'giftflow-campaign-card--no-image';
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => implode( ' ', $gf_classes ),
		'style' => $gf_inline_styles,
	)
);

// --- SKELETON / EMPTY STATE ---
if ( ! $gf_post || 'campaign' !== $gf_post->post_type || 'publish' !== $gf_post->post_status ) :
	?>
	<div <?php echo $block_wrapper_attrs; // phpcs:ignore ?>>
		<?php if ( $gf_show_image ) : ?>
			<div class="giftflow-campaign-card__media">
				<div class="giftflow-campaign-card__image giftflow-campaign-card__image--placeholder">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
				</div>
			</div>
		<?php endif; ?>
		<div class="giftflow-campaign-card__content">
			<div class="giftflow-campaign-card__skeleton-heading"></div>
			<?php if ( $gf_show_excerpt ) : ?>
				<div class="giftflow-campaign-card__skeleton-line"></div>
				<div class="giftflow-campaign-card__skeleton-line giftflow-campaign-card__skeleton-line--short"></div>
			<?php endif; ?>
			<?php if ( $gf_show_progress ) : ?>
				<div class="giftflow-campaign-card__skeleton-ring"></div>
			<?php endif; ?>
			<?php if ( $gf_show_button ) : ?>
				<div class="giftflow-campaign-card__skeleton-btn"></div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return;
endif;

// --- LIVE CAMPAIGN DATA ---
$gf_title        = get_the_title( $gf_campaign_id );
$gf_permalink    = get_permalink( $gf_campaign_id );
$gf_thumb        = get_the_post_thumbnail_url( $gf_campaign_id, 'large' );
$gf_excerpt      = get_the_excerpt( $gf_campaign_id );
$gf_categories   = get_the_terms( $gf_campaign_id, 'campaign-tax' );
$gf_location      = get_post_meta( $gf_campaign_id, '_location', true );
$gf_is_published = 'publish' === get_post_status( $gf_campaign_id );
$gf_is_disabled  = ! $gf_is_published || 0 === $gf_campaign_id;

$gf_status_data = giftflow_prepare_campaign_status_bar_data( $gf_campaign_id );

$gf_preset_amounts = array();
if ( $gf_show_presets ) {
	$gf_preset_amounts = giftflow_get_preset_donation_amounts_by_campaign( $gf_campaign_id );
	if ( empty( $gf_preset_amounts ) ) {
		$gf_global_presets = giftflow_get_preset_donation_amounts();
		$gf_global_presets = array_map( 'trim', explode( ',', $gf_global_presets ) );
		foreach ( $gf_global_presets as $gf_amt ) {
			$gf_preset_amounts[] = array( 'amount' => (float) $gf_amt );
		}
	}
}

// --- Ring progress math ---
$gf_ring_pct      = 0;
$gf_ring_offset   = 100;
$gf_ring_radius   = 36;
$gf_ring_circum   = 2 * M_PI * $gf_ring_radius;

if ( $gf_show_progress && ! empty( $gf_status_data['post_id'] ) ) {
	$gf_goal   = (float) ( $gf_status_data['goal_amount'] ?? 0 );
	$gf_raised = (float) ( $gf_status_data['raised_amount'] ?? 0 );
	if ( $gf_goal <= 0 ) {
		$gf_goal = (float) get_post_meta( $gf_campaign_id, '_goal_amount', true );
	}
	if ( $gf_raised <= 0 && ! empty( $gf_status_data['raised_amount_formatted'] ) ) {
		$gf_raised = (float) giftflow_get_campaign_raised_amount( $gf_campaign_id );
	}
	$gf_ring_pct = $gf_goal > 0 ? (int) round( ( $gf_raised / $gf_goal ) * 100 ) : 0;
	$gf_ring_offset = $gf_ring_circum - ( $gf_ring_circum * $gf_ring_pct / 100 );
}

// Days left.
$gf_days = $gf_status_data['days_left'] ?? '';
if ( '' === $gf_days ) {
	$gf_start = get_post_meta( $gf_campaign_id, '_start_date', true );
	$gf_end   = get_post_meta( $gf_campaign_id, '_end_date', true );
	if ( $gf_start && $gf_end ) {
		$gf_now    = time();
		$gf_end_ts = strtotime( $gf_end );
		if ( $gf_end_ts > $gf_now ) {
			$gf_days = (int) ceil( ( $gf_end_ts - $gf_now ) / DAY_IN_SECONDS );
		} elseif ( $gf_end_ts < $gf_now ) {
			$gf_days = 0;
		}
	}
}
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore ?>>

	<?php if ( $gf_show_image ) : ?>
		<div class="giftflow-campaign-card__media">
			<?php if ( $gf_thumb ) : ?>
				<img
					class="giftflow-campaign-card__image"
					src="<?php echo esc_url( $gf_thumb ); ?>"
					alt="<?php echo esc_attr( $gf_title ); ?>"
					loading="lazy"
				/>
			<?php else : ?>
				<div class="giftflow-campaign-card__image giftflow-campaign-card__image--placeholder">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
				</div>
			<?php endif; ?>
			<?php if ( 'overlay' === $gf_card_style ) : ?>
				<div class="giftflow-campaign-card__overlay"></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="giftflow-campaign-card__content">
		<?php if ( ! empty( $gf_categories ) && ! is_wp_error( $gf_categories ) ) : ?>
			<span class="giftflow-campaign-card__category">
				<?php
				$gf_cat_names = wp_list_pluck( $gf_categories, 'name' );
				echo esc_html( implode( ' · ', array_slice( $gf_cat_names, 0, 2 ) ) );
				?>
			</span>
		<?php endif; ?>

		<h3 class="giftflow-campaign-card__title">
			<a href="<?php echo esc_url( $gf_permalink ); ?>" class="giftflow-campaign-card__title-link">
				<?php echo esc_html( $gf_title ); ?>
			</a>
		</h3>

		<?php if ( ! empty( $gf_location ) ) : ?>
			<span class="giftflow-campaign-card__location">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
				<?php echo esc_html( $gf_location ); ?>
			</span>
		<?php endif; ?>

		<?php if ( $gf_show_excerpt && $gf_excerpt ) : ?>
			<p class="giftflow-campaign-card__excerpt">
				<?php echo esc_html( wp_trim_words( $gf_excerpt, 18 ) ); ?>
			</p>
		<?php endif; ?>

		<?php
		// -------------------------------------------------------
		// RING PROGRESS — circular ring + stat labels
		// -------------------------------------------------------
		if ( $gf_show_progress && ! empty( $gf_status_data['post_id'] ) ) :
			?>
			<div class="giftflow-campaign-card__progress">
				<div class="giftflow-campaign-card__progress-ring">
					<svg class="giftflow-campaign-card__progress-svg" viewBox="0 0 88 88" aria-hidden="true">
						<defs>
							<linearGradient id="gf-cc-ring-gradient-<?php echo (int) $gf_campaign_id; ?>" x1="0%" y1="0%" x2="100%" y2="100%">
								<stop offset="0%" stop-color="<?php echo esc_attr( ! empty( $gf_accent_color ) ? $gf_accent_color : '#2563eb' ); ?>" />
								<stop offset="100%" stop-color="<?php echo esc_attr( ! empty( $gf_accent_color ) ? $gf_accent_color . '99' : '#60a5fa' ); ?>" />
							</linearGradient>
						</defs>
						<circle
							class="giftflow-campaign-card__progress-track"
							cx="42" cy="42" r="<?php echo (int) $gf_ring_radius; ?>"
							fill="none"
							stroke-width="5"
						/>
						<circle
							class="giftflow-campaign-card__progress-fill"
							cx="42" cy="42" r="<?php echo (int) $gf_ring_radius; ?>"
							fill="none"
							stroke="url(#gf-cc-ring-gradient-<?php echo (int) $gf_campaign_id; ?>)"
							stroke-width="5"
							stroke-linecap="round"
							stroke-dasharray="<?php echo esc_attr( (string) $gf_ring_circum ); ?>"
							stroke-dashoffset="<?php echo esc_attr( (string) $gf_ring_offset ); ?>"
							style="transform: rotate(-90deg); transform-origin: 42px 42px;"
						/>
					</svg>
					<div class="giftflow-campaign-card__progress-ring-center">
						<span class="giftflow-campaign-card__progress-pct"><?php echo (int) $gf_ring_pct; ?>%</span>
						<span class="giftflow-campaign-card__progress-label"><?php esc_html_e( 'funded', 'giftflow' ); ?></span>
					</div>
				</div>

				<div class="giftflow-campaign-card__progress-stats">
					<?php
					// Build stats array for consistent rendering.
					$gf_stats = array();

					$gf_stats[] = array(
						'value' => wp_kses_post( $gf_status_data['raised_amount_formatted'] ),
						'label' => __( 'raised', 'giftflow' ),
					);

					$gf_stats[] = array(
						'value' => wp_kses_post( $gf_status_data['goal_amount_formatted'] ),
						'label' => __( 'goal', 'giftflow' ),
					);

					if ( $gf_status_data['donation_count'] > 0 ) {
						$gf_stats[] = array(
							'value' => (int) $gf_status_data['donation_count'],
							/* translators: %d: number of donors */
							'label' => esc_html( _n( 'donor', 'donors', $gf_status_data['donation_count'], 'giftflow' ) ),
						);
					}

					if ( '' !== $gf_days && false !== $gf_days ) {
						if ( 0 === (int) $gf_days ) {
							$gf_days_value = __( 'Ended', 'giftflow' );
							$gf_days_label = '';
						} else {
							$gf_days_value = (int) $gf_days;
							/* translators: %s: number of days */
							$gf_days_label = esc_html( _n( 'day', 'days', (int) $gf_days, 'giftflow' ) );
						}
						$gf_stats[] = array(
							'value' => $gf_days_value,
							'label' => $gf_days_label,
						);
					}

					foreach ( $gf_stats as $gf_i => $gf_stat ) :
						?>
						<span class="giftflow-campaign-card__progress-stat">
							<span class="giftflow-campaign-card__progress-stat-value"><?php echo wp_kses_post( (string) $gf_stat['value'] ); ?></span>
							<?php if ( ! empty( $gf_stat['label'] ) ) : ?>
								<span class="giftflow-campaign-card__progress-stat-label"><?php echo esc_html( $gf_stat['label'] ); ?></span>
							<?php endif; ?>
						</span>
						<?php
						if ( $gf_i < count( $gf_stats ) - 1 ) :
							?>
							<span class="giftflow-campaign-card__progress-stat-sep" aria-hidden="true"></span>
							<?php
						endif;
					endforeach;
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		// -------------------------------------------------------
		// ACTIONS — divider + presets + button
		// -------------------------------------------------------
		if ( $gf_show_presets || $gf_show_button ) :
			?>
			<hr class="giftflow-campaign-card__divider" />
			<div class="giftflow-campaign-card__actions">
				<?php if ( $gf_show_presets && ! empty( $gf_preset_amounts ) ) : ?>
					<div class="giftflow-campaign-card__presets">
						<?php foreach ( $gf_preset_amounts as $gf_preset ) : ?>
							<button
								class="giftflow-campaign-card__preset"
								data-campaign-id="<?php echo esc_attr( $gf_campaign_id ); ?>"
								data-campaign-title="<?php echo esc_attr( $gf_title ); ?>"
								data-preset-amount="<?php echo esc_attr( $gf_preset['amount'] ); ?>"
								<?php if ( ! $gf_is_disabled ) : ?>
									onclick="giftflow.donationButton_Handle(this)"
								<?php endif; ?>
							>
								<?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_preset['amount'] ) ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php
				if ( $gf_show_button ) :
					$gf_btn_classes = array(
						'giftflow-campaign-card__button',
						'giftflow-campaign-card__button--' . $gf_button_style,
					);
					if ( $gf_button_full ) {
						$gf_btn_classes[] = 'giftflow-campaign-card__button--full';
					}
					?>
					<button
						class="<?php echo esc_attr( implode( ' ', $gf_btn_classes ) ); ?>"
						data-campaign-id="<?php echo esc_attr( $gf_campaign_id ); ?>"
						data-campaign-title="<?php echo esc_attr( $gf_title ); ?>"
						<?php echo $gf_is_disabled ? 'disabled' : ''; ?>
						<?php if ( ! $gf_is_disabled ) : ?>
							onclick="giftflow.donationButton_Handle(this)"
						<?php endif; ?>
					>
						<span class="giftflow-campaign-card__button-label"><?php echo esc_html( $gf_button_text ); ?></span>
						<svg class="giftflow-campaign-card__button-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
					</button>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
