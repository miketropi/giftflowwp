<?php
/**
 * Render callback for Campaigns Carousel block.
 *
 * @package GiftFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_per_page  = max( 1, (int) ( $attributes['perPage'] ?? 9 ) );
$gf_orderby   = in_array( $attributes['orderby'] ?? 'date', array( 'date', 'title', 'modified', 'menu_order' ), true ) ? $attributes['orderby'] : 'date';
$gf_order     = in_array( $attributes['order'] ?? 'DESC', array( 'ASC', 'DESC' ), true ) ? $attributes['order'] : 'DESC';
$gf_columns   = max( 1, min( 5, (int) ( $attributes['columns'] ?? 3 ) ) );
$gf_category  = sanitize_text_field( $attributes['category'] ?? '' );
$gf_img_ht    = max( 150, min( 400, (int) ( $attributes['imageHeight'] ?? 240 ) ) );
$gf_img_ratio  = $attributes['imageRatio'] ?? 'auto';
$gf_use_ratio  = $gf_img_ratio && 'auto' !== $gf_img_ratio;
$gf_show_prog = $attributes['showProgress'] ?? true;
$gf_show_meta = $attributes['showMeta'] ?? true;
$gf_autoplay  = $attributes['autoplay'] ?? false;
$gf_delay     = max( 1000, (int) ( $attributes['autoplayDelay'] ?? 4000 ) );
$gf_loop      = $attributes['loop'] ?? true;
$gf_p_color       = $attributes['progressColor'] ?? '';
$gf_c_bg          = $attributes['cardBackground'] ?? '';
$gf_title_color   = $attributes['titleColor'] ?? '';
$gf_meta_color    = $attributes['metaColor'] ?? '';
$gf_desc_color    = $attributes['descriptionColor'] ?? '';
$gf_eyebrow_text  = $attributes['eyebrowTextColor'] ?? '';
$gf_eyebrow_bg    = $attributes['eyebrowBgColor'] ?? '';
$gf_heading_color = $attributes['headingTextColor'] ?? '';
$gf_button_text   = $attributes['buttonTextColor'] ?? '';
$gf_eyebrow       = $attributes['eyebrow'] ?? '';
$gf_heading       = $attributes['heading'] ?? '';
$gf_desc          = $attributes['description'] ?? '';
$gf_align         = in_array( $attributes['headerAlign'] ?? 'center', array( 'left', 'center', 'right' ), true ) ? $attributes['headerAlign'] : 'center';

$gf_args = array(
	'post_type'      => 'campaign',
	'posts_per_page' => $gf_per_page,
	'orderby'        => 'menu_order' === $gf_orderby ? 'menu_order' : $gf_orderby,
	'order'          => $gf_order,
	'post_status'    => 'publish',
);
if ( ! empty( $gf_category ) ) {
	// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	$gf_args['tax_query'] = array(
		array(
			'taxonomy' => 'campaign-tax',
			'field'    => 'term_id',
			'terms'    => absint( $gf_category ),
		),
	);
}
$gf_args  = apply_filters( 'giftflow_campaign_grid_query_args', $gf_args );
$gf_query = new WP_Query( $gf_args );

$gf_config = wp_json_encode(
	array(
		'slidesPerView'       => 1,
		'slidesPerGroup'      => 1,
		'spaceBetween'        => 20,
		'loop'                => $gf_loop,
		'autoplay'            => $gf_autoplay ? array(
			'delay'                => $gf_delay,
			'disableOnInteraction' => false,
		) : false,
		'pagination'          => array(
			'el'        => '.giftflow-carousel__pagination',
			'clickable' => true,
		),
		'navigation'          => array(
			'nextEl' => '.giftflow-carousel__next',
			'prevEl' => '.giftflow-carousel__prev',
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

$gf_accent            = $gf_p_color ? '--gf-carousel-accent:' . esc_attr( $gf_p_color ) . ';' : '';
$gf_card_bg_var       = $gf_c_bg ? '--gf-carousel-card-bg:' . esc_attr( $gf_c_bg ) . ';' : '';
$gf_title_color_var   = $gf_title_color ? '--gf-carousel-title-color:' . esc_attr( $gf_title_color ) . ';' : '';
$gf_meta_color_var    = $gf_meta_color ? '--gf-carousel-meta-color:' . esc_attr( $gf_meta_color ) . ';' : '';
$gf_desc_color_var    = $gf_desc_color ? '--gf-carousel-desc-color:' . esc_attr( $gf_desc_color ) . ';' : '';
$gf_eyebrow_text_var  = $gf_eyebrow_text ? '--gf-carousel-eyebrow-text:' . esc_attr( $gf_eyebrow_text ) . ';' : '';
$gf_eyebrow_bg_var    = $gf_eyebrow_bg ? '--gf-carousel-eyebrow-bg:' . esc_attr( $gf_eyebrow_bg ) . ';' : '';
$gf_heading_color_var = $gf_heading_color ? '--gf-carousel-heading-color:' . esc_attr( $gf_heading_color ) . ';' : '';
$gf_button_text_var   = $gf_button_text ? '--gf-carousel-button-text:' . esc_attr( $gf_button_text ) . ';' : '';
$gf_img_style         = '--gf-carousel-img-height:' . ( $gf_use_ratio ? 'auto' : ( $gf_img_ht . 'px' ) ) . ';';
$gf_img_style        .= '--gf-carousel-img-ratio:' . esc_attr( $gf_use_ratio ? $gf_img_ratio : 'auto' ) . ';';
$gf_modifier          = $gf_use_ratio ? ' giftflow-carousel--has-ratio' : '';

$gf_inline_style = $gf_img_style . $gf_accent . $gf_card_bg_var . $gf_title_color_var . $gf_meta_color_var . $gf_desc_color_var . $gf_eyebrow_text_var . $gf_eyebrow_bg_var . $gf_heading_color_var . $gf_button_text_var;

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => 'giftflow-carousel' . $gf_modifier,
		'style' => $gf_inline_style,
	)
);

if ( ! $gf_query->have_posts() ) {
	echo '<div ' . $block_wrapper_attrs . '><div class="giftflow-carousel__empty">' . esc_html__( 'No campaigns found.', 'giftflow' ) . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore ?>>
	<?php if ( $gf_eyebrow || $gf_heading || $gf_desc ) : ?>
		<div class="giftflow-carousel__header giftflow-carousel__header--<?php echo esc_attr( $gf_align ); ?>">
			<?php if ( $gf_eyebrow ) : ?>
				<span class="giftflow-carousel__eyebrow"><?php echo esc_html( $gf_eyebrow ); ?></span>
			<?php endif; ?>
			<?php if ( $gf_heading ) : ?>
				<h2 class="giftflow-carousel__heading"><?php echo esc_html( $gf_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $gf_desc ) : ?>
				<p class="giftflow-carousel__description"><?php echo esc_html( $gf_desc ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="swiper giftflow-carousel__swiper" data-carousel-config='<?php echo esc_attr( $gf_config ); ?>'>
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
				<article class="giftflow-carousel__item">
					<div class="giftflow-carousel__image">
						<a href="<?php the_permalink(); ?>">
							<?php if ( $gf_thumb ) : ?>
								<img src="<?php echo esc_url( $gf_thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
							<?php else : ?>
								<div class="giftflow-carousel__placeholder">
									<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
								</div>
							<?php endif; ?>
						</a>
						<?php if ( $gf_cats && ! is_wp_error( $gf_cats ) && ! empty( $gf_cats[0] ) ) : ?>
							<span class="giftflow-carousel__category"><?php echo esc_html( $gf_cats[0]->name ); ?></span>
						<?php endif; ?>
					</div>
					<div class="giftflow-carousel__body">
						<h4 class="giftflow-carousel__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

						<?php if ( $gf_excerpt ) : ?>
							<p class="giftflow-carousel__excerpt"><?php echo esc_html( wp_trim_words( $gf_excerpt, 12 ) ); ?></p>
						<?php endif; ?>

						<?php if ( $gf_show_prog ) : ?>
						<div class="giftflow-carousel__progress">
							<div class="giftflow-carousel__progress-bar"><div class="giftflow-carousel__progress-fill" style="width:<?php echo (int) $gf_pct; ?>%"></div></div>
							<span class="giftflow-carousel__progress-text"><?php echo (int) $gf_pct; ?>%</span>
						</div>
						<?php endif; ?>

						<?php if ( $gf_show_meta ) : ?>
						<div class="giftflow-carousel__meta">
							<span class="giftflow-carousel__raised"><?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_raised ) ); ?> <?php esc_html_e( 'raised', 'giftflow' ); ?></span>
							<?php if ( $gf_goal > 0 ) : ?>
								<span class="giftflow-carousel__goal">
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
								<span class="giftflow-carousel__days">
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
								<span class="giftflow-carousel__location"><?php echo esc_html( $gf_location ); ?></span>
							<?php endif; ?>
						</div>
						<?php endif; ?>

						<a href="<?php the_permalink(); ?>" class="giftflow-carousel__read-more">
							<?php esc_html_e( 'Read more', 'giftflow' ); ?> <svg class="giftflow-carousel__read-more-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left:4px;flex-shrink:0"><path d="M9 18l6-6-6-6"/></svg>
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
			<button class="giftflow-carousel__prev" aria-label="<?php esc_attr_e( 'Previous', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
			</button>
			<button class="giftflow-carousel__next" aria-label="<?php esc_attr_e( 'Next', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
			</button>
		<?php endif; ?>
		<div class="giftflow-carousel__pagination"></div>
	</div>
</div>
