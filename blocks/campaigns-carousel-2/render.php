<?php
/**
 * Render callback for the Campaign Carousel 2 block.
 *
 * Horizontal editorial carousel with polaroid photo-stack cards,
 * scroll-snap, and IntersectionObserver entrance animations.
 *
 * @package GiftFlow
 * @subpackage Blocks\CampaignsCarousel2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_per_page        = max( 1, (int) ( $attributes['perPage'] ?? 6 ) );
$gf_orderby         = $attributes['orderby'] ?? 'date';
if ( ! in_array( $gf_orderby, array( 'date', 'title', 'modified' ), true ) ) {
	$gf_orderby = 'date';
}
$gf_order            = isset( $attributes['order'] ) && in_array( $attributes['order'], array( 'ASC', 'DESC' ), true ) ? $attributes['order'] : 'DESC';
$gf_category         = sanitize_text_field( $attributes['category'] ?? '' );
$gf_show_prog        = $attributes['showProgress'] ?? true;
$gf_show_meta        = $attributes['showMeta'] ?? true;
$gf_accent           = $attributes['accentColor'] ?? '';
$gf_card_bg          = $attributes['cardBackground'] ?? '';
$gf_title_color      = $attributes['titleColor'] ?? '';
$gf_meta_color       = $attributes['metaColor'] ?? '';
$gf_category_color   = $attributes['categoryColor'] ?? '';
$gf_heading          = $attributes['heading'] ?? '';
$gf_gap              = (int) ( $attributes['gap'] ?? 24 );
$gf_slides           = (int) ( $attributes['slidesPerView'] ?? 3 );
$gf_show_arrows      = $attributes['showArrows'] ?? true;
$gf_show_dots        = $attributes['showDots'] ?? true;

$gf_query_args = array(
	'post_type'      => 'campaign',
	'posts_per_page' => $gf_per_page,
	'orderby'        => $gf_orderby,
	'order'          => $gf_order,
	'post_status'    => 'publish',
);

if ( ! empty( $gf_category ) ) {
	if ( is_numeric( $gf_category ) ) {
		$gf_query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'campaign-tax',
				'field'    => 'term_id',
				'terms'    => absint( $gf_category ),
			),
		);
	} else {
		$gf_query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'campaign-tax',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $gf_category ),
			),
		);
	}
}

$gf_query = new \WP_Query( $gf_query_args );

$gf_style_attr = '';
if ( $gf_accent ) {
	$gf_style_attr .= '--gf-cc-accent:' . esc_attr( $gf_accent ) . ';';
}
if ( $gf_card_bg ) {
	$gf_style_attr .= '--gf-cc-card-bg:' . esc_attr( $gf_card_bg ) . ';';
}
if ( $gf_title_color ) {
	$gf_style_attr .= '--gf-cc-title-color:' . esc_attr( $gf_title_color ) . ';';
}
if ( $gf_meta_color ) {
	$gf_style_attr .= '--gf-cc-meta-color:' . esc_attr( $gf_meta_color ) . ';';
}
if ( $gf_category_color ) {
	$gf_style_attr .= '--gf-cc-category-color:' . esc_attr( $gf_category_color ) . ';';
}
$gf_style_attr .= '--gf-cc-gap:' . $gf_gap . 'px;';
$gf_style_attr .= '--gf-cc-peek:' . (int) round( $gf_gap * 0.67 ) . 'px;';

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => 'gf-cc',
		'style' => $gf_style_attr,
	)
);
?>
<section <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php esc_attr_e( 'Campaigns', 'giftflow' ); ?>">

	<?php if ( '' !== $gf_heading || $gf_show_arrows ) : ?>
	<div class="gf-cc-header">
		<?php if ( '' !== $gf_heading ) : ?>
		<h3 class="gf-cc-heading"><?php echo esc_html( $gf_heading ); ?></h3>
		<?php endif; ?>
		<?php if ( $gf_show_arrows ) : ?>
		<div class="gf-cc-nav">
			<button type="button" class="gf-cc-btn gf-cc-prev" aria-label="<?php esc_attr_e( 'Previous campaigns', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="m15 18-6-6 6-6"/>
				</svg>
			</button>
			<button type="button" class="gf-cc-btn gf-cc-next" aria-label="<?php esc_attr_e( 'Next campaigns', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="m9 18 6-6-6-6"/>
				</svg>
			</button>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="gf-cc-viewport swiper" data-slides-per-view="<?php echo (int) $gf_slides; ?>" tabindex="0" role="region" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Campaigns', 'giftflow' ); ?>">
		<div class="gf-cc-track swiper-wrapper">
			<?php
			$gf_i = 0;
			while ( $gf_query->have_posts() ) :
				$gf_query->the_post();
				$gf_post_id       = get_the_ID();
				$gf_title         = get_the_title();
				$gf_permalink     = get_permalink();
				$gf_goal          = (float) get_post_meta( $gf_post_id, '_goal_amount', true );
				$gf_raised        = giftflow_get_campaign_raised_amount( $gf_post_id );
				$gf_pct           = $gf_goal > 0 ? (int) round( ( $gf_raised / $gf_goal ) * 100 ) : 0;
				$gf_location      = get_post_meta( $gf_post_id, '_location', true );
				$gf_thumbnail_url = get_the_post_thumbnail_url( $gf_post_id, 'medium_large' );
				$gf_cats          = get_the_terms( $gf_post_id, 'campaign-tax' );
				$gf_days_left     = giftflow_get_campaign_days_left( $gf_post_id );
				$gf_index         = $gf_i;
				++$gf_i;
				?>
			<article class="gf-cc-card swiper-slide" style="--gf-cc-i: <?php echo (int) $gf_index; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( '%1$d of %2$d', $gf_i, $gf_query->post_count ) ); ?>">
					<div class="gf-cc-polaroid">
						<div class="gf-cc-photo">
							<a href="<?php echo esc_url( $gf_permalink ); ?>" aria-hidden="true" tabindex="-1">
								<?php if ( $gf_thumbnail_url ) : ?>
									<img
										src="<?php echo esc_url( $gf_thumbnail_url ); ?>"
										alt="<?php the_title_attribute(); ?>"
										loading="lazy"
									/>
								<?php else : ?>
									<svg viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="<?php the_title_attribute(); ?>">
										<rect width="400" height="300" fill="var(--gf-cc-track-color)"/>
									</svg>
								<?php endif; ?>
							</a>
							<?php if ( $gf_cats && ! is_wp_error( $gf_cats ) && ! empty( $gf_cats[0] ) ) : ?>
								<span class="gf-cc-badge"><?php echo esc_html( $gf_cats[0]->name ); ?></span>
							<?php endif; ?>
						</div>
						<small class="gf-cc-caption" aria-hidden="true"><?php echo esc_html( $gf_title ); ?></small>
						<div class="gf-cc-body">
							<h4 class="gf-cc-title"><a href="<?php echo esc_url( $gf_permalink ); ?>"><?php echo esc_html( $gf_title ); ?></a></h4>
							<?php if ( $gf_show_prog ) : ?>
							<div class="gf-cc-progress-row">
								<div class="gf-cc-progress" role="progressbar" aria-label="<?php echo (int) $gf_pct; ?> percent funded"
									aria-valuenow="<?php echo (int) $gf_pct; ?>" aria-valuemin="0" aria-valuemax="100">
									<span class="gf-cc-progress-fill" style="--gf-cc-p: <?php echo (int) $gf_pct; ?>%"></span>
								</div>
								<span class="gf-cc-progress-value" aria-hidden="true"><?php echo (int) $gf_pct; ?>%</span>
							</div>
							<?php endif; ?>
							<?php if ( $gf_show_meta ) : ?>
							<ul class="gf-cc-meta">
								<li class="gf-cc-meta-item">
									<span class="gf-cc-meta-label"><?php esc_html_e( 'Raised', 'giftflow' ); ?></span>
									<strong class="gf-cc-meta-value"><?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_raised ) ); ?></strong>
								</li>
								<li class="gf-cc-meta-item">
									<span class="gf-cc-meta-label"><?php esc_html_e( 'Goal', 'giftflow' ); ?></span>
									<strong class="gf-cc-meta-value"><?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_goal ) ); ?></strong>
								</li>
								<li class="gf-cc-meta-item">
									<span class="gf-cc-meta-label"><?php esc_html_e( 'Days left', 'giftflow' ); ?></span>
									<strong class="gf-cc-meta-value"><?php echo is_numeric( $gf_days_left ) ? (int) $gf_days_left : '—'; ?></strong>
								</li>
								<li class="gf-cc-meta-item">
									<span class="gf-cc-meta-label"><?php esc_html_e( 'Location', 'giftflow' ); ?></span>
									<strong class="gf-cc-meta-value">
										<?php if ( $gf_location ) : ?>
											<svg class="gf-cc-map-pin" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><?php echo esc_html( $gf_location ); ?>
										<?php else : ?>
											—
										<?php endif; ?>
									</strong>
								</li>
							</ul>
							<?php endif; ?>
						</div>
					<a class="gf-cc-more" href="<?php echo esc_url( $gf_permalink ); ?>">
						<?php esc_html_e( 'View More', 'giftflow' ); ?>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="m9 18 6-6-6-6"/>
						</svg>
					</a>
				</div>
			</article>
			<?php endwhile; ?>
		</div>
	</div>

	<?php if ( $gf_show_dots ) : ?>
	<div class="gf-cc-dots" role="group" aria-label="<?php esc_attr_e( 'Choose a campaign', 'giftflow' ); ?>"></div>
	<?php endif; ?>
</section>
<?php
wp_reset_postdata();
