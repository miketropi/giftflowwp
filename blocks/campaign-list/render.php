<?php
/**
 * Render callback for the Campaign List block.
 *
 * Flat, refined vertical list. Image on the right, content left.
 * Classes use the gf-cl-* prefix matching the block style tokens.
 *
 * @package GiftFlow
 * @subpackage Blocks\CampaignList
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_campaign_id  = (int) ( $attributes['campaignId'] ?? 0 );
$gf_per_page     = max( 1, (int) ( $attributes['perPage'] ?? 6 ) );
$gf_orderby      = $attributes['orderby'] ?? 'date';
if ( ! in_array( $gf_orderby, array( 'date', 'title', 'modified' ), true ) ) {
	$gf_orderby = 'date';
}
$gf_order         = isset( $attributes['order'] ) && in_array( $attributes['order'], array( 'ASC', 'DESC' ), true ) ? $attributes['order'] : 'DESC';
$gf_category      = sanitize_text_field( $attributes['category'] ?? '' );
$gf_show_image    = $attributes['showImage'] ?? true;
$gf_show_excerpt  = $attributes['showExcerpt'] ?? true;
$gf_show_prog     = $attributes['showProgress'] ?? true;
$gf_show_meta     = $attributes['showMeta'] ?? true;
$gf_accent        = $attributes['accentColor'] ?? '';
$gf_card_bg       = $attributes['cardBackground'] ?? '';
$gf_title_color   = $attributes['titleColor'] ?? '';
$gf_meta_color    = $attributes['metaColor'] ?? '';
$gf_category_color = $attributes['categoryColor'] ?? '';
$gf_caption_color  = $attributes['captionColor'] ?? '';
$gf_show_pag      = $attributes['showPagination'] ?? true;
$gf_img_ratio     = $attributes['imageRatio'] ?? '4/3';

// Build query.
$gf_query_args = array(
	'post_type'      => 'campaign',
	'posts_per_page' => $gf_per_page,
	'orderby'        => $gf_orderby,
	'order'          => $gf_order,
	'post_status'    => 'publish',
);

if ( $gf_campaign_id > 0 ) {
	$gf_query_args['p']              = $gf_campaign_id;
	$gf_query_args['posts_per_page'] = 1;
}

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

// Inline custom properties.
$gf_style_attr = '';
if ( $gf_accent ) {
	$gf_style_attr .= '--gf-cl-accent:' . esc_attr( $gf_accent ) . ';';
}
if ( $gf_card_bg ) {
	$gf_style_attr .= '--gf-cl-card-bg:' . esc_attr( $gf_card_bg ) . ';';
}
if ( $gf_title_color ) {
	$gf_style_attr .= '--gf-cl-title-color:' . esc_attr( $gf_title_color ) . ';';
}
if ( $gf_meta_color ) {
	$gf_style_attr .= '--gf-cl-meta-color:' . esc_attr( $gf_meta_color ) . ';';
}
if ( $gf_category_color ) {
	$gf_style_attr .= '--gf-cl-category-color:' . esc_attr( $gf_category_color ) . ';';
}
if ( $gf_caption_color ) {
	$gf_style_attr .= '--gf-cl-caption-color:' . esc_attr( $gf_caption_color ) . ';';
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array(
		'class' => 'gf-cl',
		'style' => $gf_style_attr,
	)
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! $gf_query->have_posts() ) : ?>
		<div class="giftflow-campaign-list__empty">
			<?php esc_html_e( 'No campaigns found.', 'giftflow' ); ?>
		</div>
	<?php else : ?>
		<div class="gf-cl-list">
			<?php
			$gf_index = 0;
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
				$gf_excerpt       = get_the_excerpt( $gf_post_id );
				$gf_cats          = get_the_terms( $gf_post_id, 'campaign-tax' );
				$gf_days_left     = giftflow_get_campaign_days_left( $gf_post_id );
				$gf_i             = $gf_index;
				$gf_tilts          = array( -1.4, 1.2, -1.0 );
				$gf_tilt_deg       = $gf_tilts[ $gf_index % 3 ];
				++$gf_index;
				?>
		<article class="gf-cl-card" style="--gf-cl-i: <?php echo (int) $gf_i; ?>">
				<?php if ( $gf_show_image ) : ?>
			<div class="gf-cl-media" style="--gf-cl-tilt: <?php echo esc_attr( $gf_tilt_deg ); ?>deg; --gf-cl-back: url('<?php echo esc_url( $gf_thumbnail_url ); ?>'); aspect-ratio: <?php echo esc_attr( $gf_img_ratio ); ?>;">
				<div class="gf-cl-photo">
					<a href="<?php echo esc_url( $gf_permalink ); ?>" aria-hidden="true" tabindex="-1">
						<?php if ( $gf_thumbnail_url ) : ?>
							<img
								src="<?php echo esc_url( $gf_thumbnail_url ); ?>"
								alt="<?php the_title_attribute(); ?>"
								loading="lazy"
							/>
						<?php else : ?>
							<svg viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice"
								xmlns="http://www.w3.org/2000/svg" role="img"
								aria-label="<?php the_title_attribute(); ?>">
								<rect width="400" height="300" fill="var(--gf-cl-track)"/>
							</svg>
						<?php endif; ?>
					</a>
					<?php if ( $gf_thumbnail_url ) : ?>
						<small class="gf-cl-caption" aria-hidden="true"><?php echo esc_html( $gf_title ); ?></small>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>

			<div class="gf-cl-body">
				<?php if ( $gf_cats && ! is_wp_error( $gf_cats ) && ! empty( $gf_cats[0] ) ) : ?>
					<small class="gf-cl-category"><?php echo esc_html( $gf_cats[0]->name ); ?></small>
				<?php endif; ?>

				<h4 class="gf-cl-title">
					<a href="<?php echo esc_url( $gf_permalink ); ?>"><?php echo esc_html( $gf_title ); ?></a>
				</h4>

				<?php if ( $gf_show_excerpt && $gf_excerpt ) : ?>
					<p class="gf-cl-excerpt"><?php echo esc_html( wp_trim_words( $gf_excerpt, 20 ) ); ?></p>
				<?php endif; ?>

				<?php if ( $gf_show_prog ) : ?>
				<div class="gf-cl-progress-row">
					<div class="gf-cl-progress" role="progressbar" aria-label="<?php echo (int) $gf_pct; ?> percent funded"
						aria-valuenow="<?php echo (int) $gf_pct; ?>" aria-valuemin="0" aria-valuemax="100">
						<span class="gf-cl-progress-fill" style="--gf-cl-p: <?php echo (int) $gf_pct; ?>%"></span>
					</div>
					<span class="gf-cl-progress-value" aria-hidden="true"><?php echo (int) $gf_pct; ?>%</span>
				</div>
				<?php endif; ?>

				<?php if ( $gf_show_meta ) : ?>
				<ul class="gf-cl-meta">
					<li class="gf-cl-meta-item"><small><strong><?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_raised ) ); ?></strong> <?php esc_html_e( 'raised', 'giftflow' ); ?></small></li>
					<?php if ( $gf_goal > 0 ) : ?>
					<li class="gf-cl-meta-item"><small><?php esc_html_e( 'of', 'giftflow' ); ?> <strong><?php echo wp_kses_post( giftflow_render_currency_formatted_amount( $gf_goal ) ); ?></strong> <?php esc_html_e( 'goal', 'giftflow' ); ?></small></li>
					<?php endif; ?>
					<?php if ( is_numeric( $gf_days_left ) && (int) $gf_days_left > 0 ) : ?>
					<li class="gf-cl-meta-item"><small><strong><?php echo (int) $gf_days_left; ?> <?php echo esc_html( _n( 'day', 'days', (int) $gf_days_left, 'giftflow' ) ); ?></strong> <?php esc_html_e( 'left', 'giftflow' ); ?></small></li>
					<?php endif; ?>
					<?php if ( ! empty( $gf_location ) ) : ?>
					<li class="gf-cl-meta-item"><small><svg class="gf-cl-map-pin" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><?php echo esc_html( $gf_location ); ?></small></li>
					<?php endif; ?>
				</ul>
				<?php endif; ?>
			</div>
		</article>
		<?php endwhile; ?>
	</div>

		<?php if ( $gf_show_pag && 0 === $gf_campaign_id && $gf_query->max_num_pages > 1 ) : ?>
			<nav class="giftflow-campaign-list__pagination" aria-label="<?php esc_attr_e( 'Campaigns pagination', 'giftflow' ); ?>">
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'base'      => str_replace( 999999, '%#%', get_pagenum_link( 999999, false ) ),
							'format'    => '?paged=%#%',
							'current'   => max( 1, (int) get_query_var( 'paged', 1 ) ),
							'total'     => $gf_query->max_num_pages,
							'prev_text' => __( 'Previous', 'giftflow' ),
							'next_text' => __( 'Next', 'giftflow' ),
						)
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</div>

<?php
if ( ! wp_script_is( 'giftflow-campaign-list-observer', 'done' ) && ! wp_script_is( 'giftflow-campaign-list-observer', 'enqueued' ) ) :
	wp_register_script( 'giftflow-campaign-list-observer', false, array(), GIFTFLOW_VERSION, true );
	wp_enqueue_script( 'giftflow-campaign-list-observer' );
	wp_add_inline_script(
		'giftflow-campaign-list-observer',
		'(function(){' .
		'document.documentElement.classList.add("gf-js");' .
		'var cards=document.querySelectorAll(".gf-cl-card");' .
		'if(!cards.length)return;' .
		'var reduce=window.matchMedia&&window.matchMedia("(prefers-reduced-motion: reduce)").matches;' .
		'if(!("IntersectionObserver" in window)||reduce){' .
		'for(var i=0;i<cards.length;i++)cards[i].classList.add("gf-cl-visible");' .
		'return;' .
		'}' .
		'var io=new IntersectionObserver(function(entries){' .
		'entries.forEach(function(entry){if(entry.isIntersecting){' .
		'entry.target.classList.add("gf-cl-visible");' .
		'io.unobserve(entry.target);' .
		'}});' .
		'},{threshold:0.12,rootMargin:"0px 0px -8% 0px"});' .
		'for(var j=0;j<cards.length;j++)io.observe(cards[j]);' .
		'})();'
	);
endif;
