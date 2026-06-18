<?php
/**
 * Render callback for Similar Campaign Carousel block.
 *
 * @package GiftFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine current campaign ID from attribute, block context, or the global post.
$gf_source_id = (int) ( $attributes['sourceCampaignId'] ?? 0 );
$gf_current_id = $gf_source_id > 0
	? $gf_source_id
	: (int) ( isset( $block->context['postId'] ) ? $block->context['postId'] : get_the_ID() );

$gf_per_page  = max( 1, (int) ( $attributes['perPage'] ?? 9 ) );
$gf_columns   = max( 1, min( 5, (int) ( $attributes['columns'] ?? 3 ) ) );
$gf_img_ht    = max( 150, min( 400, (int) ( $attributes['imageHeight'] ?? 240 ) ) );
$gf_show_prog = $attributes['showProgress'] ?? true;
$gf_show_meta = $attributes['showMeta'] ?? true;
$gf_autoplay  = $attributes['autoplay'] ?? false;
$gf_delay     = max( 1000, (int) ( $attributes['autoplayDelay'] ?? 4000 ) );
$gf_loop      = $attributes['loop'] ?? true;
$gf_p_color   = $attributes['progressColor'] ?? '';
$gf_eyebrow   = $attributes['eyebrow'] ?? '';
$gf_heading   = $attributes['heading'] ?? '';
$gf_desc      = $attributes['description'] ?? '';
$gf_align     = in_array( $attributes['headerAlign'] ?? 'center', array( 'left', 'center', 'right' ), true ) ? $attributes['headerAlign'] : 'center';
$gf_exclude   = $attributes['excludeCurrent'] ?? true;

// Build query: match by shared campaign-tax terms of the current campaign.
$gf_args = array(
	'post_type'      => 'campaign',
	'posts_per_page' => $gf_per_page,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
);

// Exclude current campaign.
if ( $gf_exclude && $gf_current_id > 0 ) {
	$gf_args['post__not_in'] = array( $gf_current_id );
}

// Match by shared taxonomy terms.
if ( $gf_current_id > 0 ) {
	$gf_terms = get_the_terms( $gf_current_id, 'campaign-tax' );
	if ( $gf_terms && ! is_wp_error( $gf_terms ) ) {
		$gf_term_ids = wp_list_pluck( $gf_terms, 'term_id' );
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		$gf_args['tax_query'] = array(
			array(
				'taxonomy' => 'campaign-tax',
				'field'    => 'term_id',
				'terms'    => $gf_term_ids,
			),
		);
		// Order by number of shared terms (most relevant first).
		// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		$gf_args['meta_query'] = array(
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_key'  => '_goal_amount',
			'meta_type' => 'NUMERIC',
		);
		$gf_args['orderby']    = array(
			'DESC' === $gf_args['order'] ? 'meta_value_num' : 'meta_value_num' => $gf_args['order'],
		);
	}
	// phpcs:enable
}

/**
 * Filter query args for similar campaigns.
 *
 * @param array $gf_args        WP_Query arguments.
 * @param int   $gf_current_id  Current campaign post ID.
 * @param array $attributes     Block attributes.
 */
$gf_args = apply_filters( 'giftflow_similar_campaign_query_args', $gf_args, $gf_current_id, $attributes );

$gf_query = new WP_Query( $gf_args );

$gf_config = wp_json_encode(
	array(
		'slidesPerView'       => 1,
		'slidesPerGroup'      => 1,
		'spaceBetween'        => 20,
		'loop'                => $gf_loop && $gf_query->post_count > 1,
		'autoplay'            => $gf_autoplay ? array(
			'delay'                => $gf_delay,
			'disableOnInteraction' => false,
		) : false,
		'pagination'          => array(
			'el'        => '.giftflow-similar-carousel__pagination',
			'clickable' => true,
		),
		'navigation'          => array(
			'nextEl' => '.giftflow-similar-carousel__next',
			'prevEl' => '.giftflow-similar-carousel__prev',
		),
		'breakpoints'         => array(
			640  => array( 'slidesPerView' => min( 2, $gf_columns ) ),
			1024 => array( 'slidesPerView' => min( 3, $gf_columns ) ),
			1280 => array( 'slidesPerView' => $gf_columns ),
		),
		'grabCursor'          => true,
		'watchSlidesProgress' => true,
	)
);

$gf_accent           = $gf_p_color ? '--gf-carousel-accent:' . esc_attr( $gf_p_color ) . ';' : '';
$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => 'giftflow-similar-carousel',
		'style' => '--gf-carousel-img-height:' . $gf_img_ht . 'px;' . $gf_accent,
	)
);

if ( ! $gf_query->have_posts() ) {
	echo '<div ' . $block_wrapper_attrs . '><div class="giftflow-similar-carousel__empty">' . esc_html__( 'No similar campaigns found.', 'giftflow' ) . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore ?>>
	<?php if ( $gf_eyebrow || $gf_heading || $gf_desc ) : ?>
		<div class="giftflow-similar-carousel__header giftflow-similar-carousel__header--<?php echo esc_attr( $gf_align ); ?>">
			<?php if ( $gf_eyebrow ) : ?>
				<span class="giftflow-similar-carousel__eyebrow"><?php echo esc_html( $gf_eyebrow ); ?></span>
			<?php endif; ?>
			<?php if ( $gf_heading ) : ?>
				<h2 class="giftflow-similar-carousel__heading"><?php echo esc_html( $gf_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $gf_desc ) : ?>
				<p class="giftflow-similar-carousel__description"><?php echo esc_html( $gf_desc ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="swiper giftflow-similar-carousel__swiper" data-carousel-config='<?php echo esc_attr( $gf_config ); ?>'>
		<div class="swiper-wrapper">
			<?php
			while ( $gf_query->have_posts() ) :
				$gf_query->the_post();
				$gf_id       = get_the_ID();
				$gf_goal     = (int) get_post_meta( $gf_id, '_goal_amount', true );
				$gf_raised   = giftflow_get_campaign_raised_amount( $gf_id );
				$gf_pct      = $gf_goal > 0 ? (int) round( ( $gf_raised / $gf_goal ) * 100 ) : 0;
				$gf_thumb    = get_the_post_thumbnail_url( $gf_id, 'medium_large' );
				$gf_excerpt  = get_the_excerpt( $gf_id );
				$gf_cats     = get_the_terms( $gf_id, 'campaign-tax' );
				$gf_days     = giftflow_get_campaign_days_left( $gf_id );
				$gf_location = get_post_meta( $gf_id, '_location', true );
				?>
			<div class="swiper-slide">
				<article class="giftflow-similar-carousel__item">
					<div class="giftflow-similar-carousel__image">
						<a href="<?php the_permalink(); ?>">
							<?php if ( $gf_thumb ) : ?>
								<img src="<?php echo esc_url( $gf_thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
							<?php else : ?>
								<div class="giftflow-similar-carousel__placeholder">
									<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
								</div>
							<?php endif; ?>
						</a>
						<?php if ( $gf_cats && ! is_wp_error( $gf_cats ) && ! empty( $gf_cats[0] ) ) : ?>
							<span class="giftflow-similar-carousel__category"><?php echo esc_html( $gf_cats[0]->name ); ?></span>
						<?php endif; ?>
					</div>
					<div class="giftflow-similar-carousel__body">
						<h3 class="giftflow-similar-carousel__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<?php if ( $gf_excerpt ) : ?>
							<p class="giftflow-similar-carousel__excerpt"><?php echo esc_html( wp_trim_words( $gf_excerpt, 12 ) ); ?></p>
						<?php endif; ?>

						<?php if ( $gf_show_prog ) : ?>
						<div class="giftflow-similar-carousel__progress">
							<div class="giftflow-similar-carousel__progress-bar"><div class="giftflow-similar-carousel__progress-fill" style="width:<?php echo (int) $gf_pct; ?>%"></div></div>
							<span class="giftflow-similar-carousel__progress-text"><?php echo (int) $gf_pct; ?>%</span>
						</div>
						<?php endif; ?>

						<?php if ( $gf_show_meta ) : ?>
						<div class="giftflow-similar-carousel__meta">
							<span class="giftflow-similar-carousel__raised"><?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_raised ) ); ?> <?php esc_html_e( 'raised', 'giftflow' ); ?></span>
							<?php if ( $gf_goal > 0 ) : ?>
								<span class="giftflow-similar-carousel__goal">
									<?php
									printf(
										/* translators: %s: formatted goal amount */
										esc_html__( 'Goal %s', 'giftflow' ),
										wp_kses_post( giftflow_render_currency_formatted_amount( $gf_goal ) )
									);
									?>
								</span>
							<?php endif; ?>
							<?php if ( is_numeric( $gf_days ) && (int) $gf_days > 0 ) : ?>
								<span class="giftflow-similar-carousel__days">
									<?php
									printf(
										/* translators: %d: number of days left */
										esc_html( _n( '%d day left', '%d days left', (int) $gf_days, 'giftflow' ) ),
										(int) $gf_days
									);
									?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $gf_location ) ) : ?>
								<span class="giftflow-similar-carousel__location"><?php echo esc_html( $gf_location ); ?></span>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<a href="<?php the_permalink(); ?>" class="giftflow-similar-carousel__read-more">
							<?php esc_html_e( 'Read more', 'giftflow' ); ?> →
						</a>
					</div>
				</article>
			</div>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<?php if ( $gf_query->post_count > $gf_columns ) : ?>
			<button class="giftflow-similar-carousel__prev" aria-label="<?php esc_attr_e( 'Previous', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
			</button>
			<button class="giftflow-similar-carousel__next" aria-label="<?php esc_attr_e( 'Next', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
			</button>
		<?php endif; ?>
		<div class="giftflow-similar-carousel__pagination"></div>
	</div>
</div>
