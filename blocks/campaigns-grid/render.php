<?php
/**
 * Render callback for the Campaigns Grid block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_per_page = max( 1, (int) ( $attributes['perPage'] ?? 9 ) );
$gf_orderby  = $attributes['orderby'] ?? 'date';
if ( ! in_array( $gf_orderby, array( 'date', 'title', 'modified', 'menu_order' ), true ) ) {
	$gf_orderby = 'date';
}
$gf_order          = isset( $attributes['order'] ) && in_array( $attributes['order'], array( 'ASC', 'DESC' ), true ) ? $attributes['order'] : 'DESC';
$gf_columns        = max( 1, min( 4, (int) ( $attributes['columns'] ?? 3 ) ) );
$gf_category       = sanitize_text_field( $attributes['category'] ?? '' );
$gf_search         = sanitize_text_field( $attributes['search'] ?? '' );
$gf_card_style     = $attributes['cardStyle'] ?? 'shadow';
$gf_img_height     = max( 120, min( 360, (int) ( $attributes['imageHeight'] ?? 200 ) ) );
$gf_show_prog      = $attributes['showProgress'] ?? true;
$gf_show_meta      = $attributes['showMeta'] ?? true;
$gf_progress_color = $attributes['progressColor'] ?? '';
$gf_extra_class    = sanitize_html_class( $attributes['customClass'] ?? '' );
$gf_progress_color = $attributes['progressColor'] ?? '';

$gf_paged = max( 1, (int) get_query_var( 'paged', 1 ) );

$gf_query_args = array(
	'post_type'      => 'campaign',
	'posts_per_page' => $gf_per_page,
	'orderby'        => 'menu_order' === $gf_orderby ? 'menu_order' : $gf_orderby,
	'order'          => $gf_order,
	'paged'          => $gf_paged,
	'post_status'    => 'publish',
);

if ( ! empty( $gf_category ) ) {
	$gf_query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'campaign-tax',
			'field'    => 'term_id',
			'terms'    => absint( $gf_category ),
		),
	);
}

if ( ! empty( $gf_search ) ) {
	$gf_query_args['s'] = $gf_search;
}

$gf_query_args = apply_filters( 'giftflow_campaign_grid_query_args', $gf_query_args );

$gf_query = new \WP_Query( $gf_query_args );

$gf_classes = 'giftflow-campaigns-grid giftflow-campaigns-grid--cols-' . $gf_columns
	. ' giftflow-campaigns-grid--' . esc_attr( $gf_card_style )
	. ( $gf_extra_class ? ' ' . $gf_extra_class : '' );

$gf_style_attr = '--giftflow-grid-columns:' . $gf_columns . ';--gf-grid-img-height:' . $gf_img_height . 'px;';
if ( $gf_progress_color ) {
	$gf_style_attr .= '--gf-grid-accent:' . esc_attr( $gf_progress_color ) . ';';
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => $gf_classes,
		'style' => $gf_style_attr,
	)
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?>>
	<?php if ( ! $gf_query->have_posts() ) : ?>
		<div class="giftflow-campaigns-grid__empty">
			<?php esc_html_e( 'No campaigns found.', 'giftflow' ); ?>
		</div>
	<?php else : ?>
		<div class="giftflow-campaigns-grid__items">
			<?php
			while ( $gf_query->have_posts() ) :
				$gf_query->the_post();
				$gf_campaign_id   = get_the_ID();
				$gf_goal          = (int) get_post_meta( $gf_campaign_id, '_goal_amount', true );
				$gf_raised        = giftflow_get_campaign_raised_amount( $gf_campaign_id );
				$gf_progress      = $gf_goal > 0 ? (int) round( ( $gf_raised / $gf_goal ) * 100 ) : 0;
				$gf_location      = get_post_meta( $gf_campaign_id, '_location', true );
				$gf_thumbnail_url = get_the_post_thumbnail_url( $gf_campaign_id, 'medium_large' );
				$gf_excerpt       = get_the_excerpt( $gf_campaign_id );
				$gf_categories    = get_the_terms( $gf_campaign_id, 'campaign-tax' );
				$gf_days_left     = giftflow_get_campaign_days_left( $gf_campaign_id );
				?>
		<article class="giftflow-campaigns-grid__item">
			<div class="giftflow-campaigns-grid__image">
				<a href="<?php the_permalink(); ?>">
					<?php if ( $gf_thumbnail_url ) : ?>
						<img src="<?php echo esc_url( $gf_thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
					<?php else : ?>
						<div class="giftflow-campaigns-grid__placeholder">
							<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
						</div>
					<?php endif; ?>
				</a>
				<?php if ( $gf_categories && ! is_wp_error( $gf_categories ) && ! empty( $gf_categories[0] ) ) : ?>
					<span class="giftflow-campaigns-grid__category"><?php echo esc_html( $gf_categories[0]->name ); ?></span>
				<?php endif; ?>
			</div>

			<div class="giftflow-campaigns-grid__body">
				<h3 class="giftflow-campaigns-grid__title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>

				<?php if ( $gf_excerpt ) : ?>
					<p class="giftflow-campaigns-grid__excerpt"><?php echo esc_html( wp_trim_words( $gf_excerpt, 15 ) ); ?></p>
				<?php endif; ?>

				<?php if ( $gf_show_prog ) : ?>
				<div class="giftflow-campaigns-grid__progress">
					<div class="giftflow-campaigns-grid__progress-bar">
						<div class="giftflow-campaigns-grid__progress-fill" style="width: <?php echo (int) $gf_progress; ?>%;"></div>
					</div>
					<span class="giftflow-campaigns-grid__progress-text"><?php echo (int) $gf_progress; ?>%</span>
				</div>
				<?php endif; ?>

				<?php if ( $gf_show_meta ) : ?>
				<div class="giftflow-campaigns-grid__meta">
					<span class="giftflow-campaigns-grid__raised"><?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_raised ) ); ?> <?php esc_html_e( 'raised', 'giftflow' ); ?></span>
					<?php if ( $gf_goal > 0 ) : ?>
						<span class="giftflow-campaigns-grid__goal">
							<?php
							printf(
								/* translators: %s: formatted goal amount */
								esc_html__( 'Goal %s', 'giftflow' ),
								wp_kses_post( giftflow_render_currency_formatted_amount( $gf_goal ) )
							);
							?>
						</span>
					<?php endif; ?>
					<?php if ( is_numeric( $gf_days_left ) && (int) $gf_days_left > 0 ) : ?>
						<span class="giftflow-campaigns-grid__days">
							<?php
							printf(
								/* translators: %d: number of days left */
								esc_html( _n( '%d day left', '%d days left', (int) $gf_days_left, 'giftflow' ) ),
								(int) $gf_days_left
							);
							?>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $gf_location ) ) : ?>
						<span class="giftflow-campaigns-grid__location"><?php echo esc_html( $gf_location ); ?></span>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<a href="<?php the_permalink(); ?>" class="giftflow-campaigns-grid__read-more">
					<?php esc_html_e( 'Read more', 'giftflow' ); ?>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
				</a>
			</div>
		</article>
		<?php endwhile; ?>
	</div>

		<?php if ( $gf_query->max_num_pages > 1 ) : ?>
			<nav class="giftflow-campaigns-grid__pagination" aria-label="<?php esc_attr_e( 'Campaigns pagination', 'giftflow' ); ?>">
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'base'      => str_replace( 999999, '%#%', get_pagenum_link( 999999, false ) ),
							'format'    => '?paged=%#%',
							'current'   => $gf_paged,
							'total'     => $gf_query->max_num_pages,
							'prev_text' => __( 'Previous', 'giftflow' ),
							'next_text' => __( 'Next', 'giftflow' ),
						)
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<?php
		wp_reset_postdata();
		?>
	<?php endif; ?>
</div>
