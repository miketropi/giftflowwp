<?php
/**
 * Render callback for the Sponsors Testimonials Carousel block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_eyebrow    = $attributes['eyebrowText'] ?? __( 'Trusted Partners', 'giftflow' );
$gf_heading    = $attributes['heading'] ?? __( 'Real stories from real partners', 'giftflow' );
$gf_subheading = $attributes['subheading'] ?? '';
$gf_show_stats = $attributes['showStats'] ?? true;
$gf_stats       = $attributes['stats'] ?? array();

if ( empty( $gf_stats ) || ! is_array( $gf_stats ) ) {
	$gf_stats = array(
		array(
			'value' => '$4.2M+',
			'label' => __( 'Donations', 'giftflow' ),
		),
		array(
			'value' => '180+',
			'label' => __( 'Partners', 'giftflow' ),
		),
		array(
			'value' => '98%',
			'label' => __( 'Satisfaction', 'giftflow' ),
		),
	);
}

$gf_testimonials = $attributes['testimonials'] ?? array();

if ( empty( $gf_testimonials ) || ! is_array( $gf_testimonials ) ) {
	$gf_testimonials = array(
		array(
			'sponsorName'  => 'TechCorp',
			'color'        => '#6366f1',
			'impactBadge'  => '+65%',
			'quote'        => __( 'GiftFlow made it effortless to launch our corporate giving program. Employee participation doubled and the real-time reporting is a game-changer.', 'giftflow' ),
			'metrics'      => array(
				array(
					'value' => '$380K',
					'label' => __( 'Annual', 'giftflow' ),
				),
				array(
					'value' => '2,400',
					'label' => __( 'Donors', 'giftflow' ),
				),
				array(
					'value' => '45',
					'label' => __( 'Campaigns', 'giftflow' ),
				),
			),
			'authorName'   => 'Jane Dawson',
			'authorRole'   => __( 'VP of CSR', 'giftflow' ),
			'stars'        => 5,
		),
		array(
			'sponsorName'  => 'GreenFund',
			'color'        => '#10b981',
			'impactBadge'  => '+42%',
			'quote'        => __( "Donor trust is everything. GiftFlow's transparent tracking shows exactly where every dollar goes. Our monthly donor base has grown 42%.", 'giftflow' ),
			'metrics'      => array(
				array(
					'value' => '$156K',
					'label' => __( 'Quarterly', 'giftflow' ),
				),
				array(
					'value' => '890',
					'label' => __( 'Monthly', 'giftflow' ),
				),
				array(
					'value' => '12',
					'label' => __( 'Projects', 'giftflow' ),
				),
			),
			'authorName'   => 'Michael Santos',
			'authorRole'   => __( 'Executive Director', 'giftflow' ),
			'stars'        => 5,
		),
		array(
			'sponsorName'  => 'HopeAlliance',
			'color'        => '#8b5cf6',
			'impactBadge'  => '+78% rec.',
			'quote'        => __( 'Switching to GiftFlow was our best decision this year. Recurring donations are up 78% and volunteer coordination has never been easier.', 'giftflow' ),
			'metrics'      => array(
				array(
					'value' => '$240K',
					'label' => __( 'Annual', 'giftflow' ),
				),
				array(
					'value' => '1,250',
					'label' => __( 'Volunteers', 'giftflow' ),
				),
				array(
					'value' => '28',
					'label' => __( 'Events', 'giftflow' ),
				),
			),
			'authorName'   => 'Amanda Liu',
			'authorRole'   => __( 'Founder & CEO', 'giftflow' ),
			'stars'        => 5,
		),
		array(
			'sponsorName'  => 'CityRelief',
			'color'        => '#06b6d4',
			'impactBadge'  => __( '1-day setup', 'giftflow' ),
			'quote'        => __( 'We needed speed — CityRelief was accepting international donations the same day we signed up. Multi-currency support was the game-changer.', 'giftflow' ),
			'metrics'      => array(
				array(
					'value' => '$95K',
					'label' => __( 'Monthly', 'giftflow' ),
				),
				array(
					'value' => '18',
					'label' => __( 'Countries', 'giftflow' ),
				),
				array(
					'value' => '3',
					'label' => __( 'Currencies', 'giftflow' ),
				),
			),
			'authorName'   => 'Robert Jackson',
			'authorRole'   => __( 'Operations Director', 'giftflow' ),
			'stars'        => 5,
		),
		array(
			'sponsorName'  => 'EduBridge',
			'color'        => '#f59e0b',
			'impactBadge'  => '+53% giving',
			'quote'        => __( 'Our parent community loves how easy it is to contribute. GiftFlow made fundraising accessible to teachers, parents, and local businesses alike.', 'giftflow' ),
			'metrics'      => array(
				array(
					'value' => '$67K',
					'label' => __( 'Raised', 'giftflow' ),
				),
				array(
					'value' => '4,200',
					'label' => __( 'Supporters', 'giftflow' ),
				),
				array(
					'value' => '8',
					'label' => __( 'Programs', 'giftflow' ),
				),
			),
			'authorName'   => 'Sarah Price',
			'authorRole'   => __( 'Superintendent', 'giftflow' ),
			'stars'        => 4,
		),
		array(
			'sponsorName'  => 'MedCare',
			'color'        => '#ec4899',
			'impactBadge'  => '2x donors',
			'quote'        => __( "Managing campaigns across three regions used to be a headache. GiftFlow's dashboard gives us unified real-time insights across all our programs.", 'giftflow' ),
			'metrics'      => array(
				array(
					'value' => '$520K',
					'label' => __( 'Total', 'giftflow' ),
				),
				array(
					'value' => '3',
					'label' => __( 'Regions', 'giftflow' ),
				),
				array(
					'value' => '90+',
					'label' => __( 'Campaigns', 'giftflow' ),
				),
			),
			'authorName'   => 'David Wong',
			'authorRole'   => __( 'CFO, MedCare Foundation', 'giftflow' ),
			'stars'        => 5,
		),
	);
}

/**
 * Filter testimonials before rendering.
 *
 * @param array $gf_testimonials Testimonial items.
 * @param array $attributes      Block attributes.
 */
$gf_testimonials = apply_filters( 'giftflow_sponsors_testimonials_data', $gf_testimonials, $attributes );

$gf_autoplay = $attributes['autoplay'] ?? true;
$gf_delay    = max( 1000, (int) ( $attributes['autoplayDelay'] ?? 4000 ) );
$gf_loop     = $attributes['loop'] ?? true;
$gf_columns  = max( 1, min( 5, (int) ( $attributes['columns'] ?? 3 ) ) );

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
			'el'        => '.giftflow-st-carousel__pagination',
			'clickable' => true,
		),
		'navigation'          => array(
			'nextEl' => '.giftflow-st-carousel__next',
			'prevEl' => '.giftflow-st-carousel__prev',
		),
		'breakpoints'         => array(
			640  => array( 'slidesPerView' => min( 2, $gf_columns ) ),
			1024 => array( 'slidesPerView' => $gf_columns ),
		),
		'grabCursor'          => true,
		'watchSlidesProgress' => true,
	)
);

$gf_star_svg       = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
$gf_check_svg       = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>';
$gf_eye_sparkle_svg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>';

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-sponsors-testimonials-carousel' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<header class="giftflow-st-carousel__header">
		<?php if ( ! empty( $gf_eyebrow ) ) : ?>
			<span class="giftflow-st-carousel__eyebrow">
				<?php echo $gf_eye_sparkle_svg; // phpcs:ignore ?>
				<?php echo esc_html( $gf_eyebrow ); ?>
			</span>
		<?php endif; ?>

		<h2 class="giftflow-st-carousel__title">
			<?php echo esc_html( $gf_heading ); ?>
		</h2>

		<?php if ( ! empty( $gf_subheading ) ) : ?>
			<p class="giftflow-st-carousel__subtitle">
				<?php echo esc_html( $gf_subheading ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $gf_show_stats && ! empty( $gf_stats ) ) : ?>
			<div class="giftflow-st-carousel__stats">
				<?php foreach ( $gf_stats as $gf_stat ) : ?>
					<div class="giftflow-st-carousel__stat">
						<span class="giftflow-st-carousel__stat-value"><?php echo esc_html( $gf_stat['value'] ?? '' ); ?></span>
						<span class="giftflow-st-carousel__stat-label"><?php echo esc_html( $gf_stat['label'] ?? '' ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</header>

	<div class="swiper giftflow-st-carousel__swiper" data-carousel-config='<?php echo esc_attr( $gf_config ); ?>'>
		<div class="swiper-wrapper">
			<?php foreach ( $gf_testimonials as $gf_t ) : ?>
				<div class="swiper-slide">
					<div class="giftflow-st-carousel__card">
						<div class="giftflow-st-carousel__card-header">
							<div class="giftflow-st-carousel__sponsor">
								<div
									class="giftflow-st-carousel__sponsor-icon"
									style="--gf-st-sponsor-bg:<?php echo esc_attr( $gf_t['color'] ?? '#2563eb' ); ?>"
								>
									<?php if ( ! empty( $gf_t['sponsorImage']['url'] ) ) : ?>
										<img src="<?php echo esc_url( $gf_t['sponsorImage']['url'] ); ?>" alt="<?php echo esc_attr( $gf_t['sponsorImage']['alt'] ?? '' ); ?>" class="giftflow-st-carousel__sponsor-img" />
									<?php else : ?>
										<?php echo esc_html( mb_substr( $gf_t['sponsorName'] ?? '', 0, 1 ) ); ?>
									<?php endif; ?>
								</div>
								<div class="giftflow-st-carousel__sponsor-info">
									<span class="giftflow-st-carousel__sponsor-name">
										<?php echo esc_html( $gf_t['sponsorName'] ?? '' ); ?>
									</span>
								</div>
							</div>
							<?php if ( ! empty( $gf_t['impactBadge'] ) ) : ?>
								<span class="giftflow-st-carousel__badge" style="--gf-st-item-color:<?php echo esc_attr( $gf_t['color'] ?? '#2563eb' ); ?>">
									<?php echo $gf_check_svg; // phpcs:ignore ?>
									<?php echo esc_html( $gf_t['impactBadge'] ); ?>
								</span>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $gf_t['quote'] ) ) : ?>
							<blockquote class="giftflow-st-carousel__quote">
								<p><?php echo esc_html( $gf_t['quote'] ); ?></p>
							</blockquote>
						<?php endif; ?>

						<?php
						$gf_metrics = $gf_t['metrics'] ?? array();
						if ( ! empty( $gf_metrics ) ) :
							?>
							<div class="giftflow-st-carousel__metrics" style="--gf-st-item-color:<?php echo esc_attr( $gf_t['color'] ?? '#2563eb' ); ?>">
								<?php foreach ( $gf_metrics as $gf_m ) : ?>
									<div class="giftflow-st-carousel__metric">
										<span class="giftflow-st-carousel__metric-value"><?php echo esc_html( $gf_m['value'] ?? '' ); ?></span>
										<span class="giftflow-st-carousel__metric-label"><?php echo esc_html( $gf_m['label'] ?? '' ); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<div class="giftflow-st-carousel__author">
							<div
								class="giftflow-st-carousel__author-avatar"
								style="--gf-st-author-bg:<?php echo esc_attr( $gf_t['color'] ?? '#2563eb' ); ?>"
							>
								<?php if ( ! empty( $gf_t['authorImage']['url'] ) ) : ?>
									<img src="<?php echo esc_url( $gf_t['authorImage']['url'] ); ?>" alt="<?php echo esc_attr( $gf_t['authorImage']['alt'] ?? '' ); ?>" class="giftflow-st-carousel__author-img" />
								<?php else : ?>
									<?php echo esc_html( mb_substr( $gf_t['authorName'] ?? '', 0, 1 ) ); ?>
								<?php endif; ?>
							</div>
							<div class="giftflow-st-carousel__author-info">
								<span class="giftflow-st-carousel__author-name">
									<?php echo esc_html( $gf_t['authorName'] ?? '' ); ?>
								</span>
								<span class="giftflow-st-carousel__author-role">
									<?php echo esc_html( $gf_t['authorRole'] ?? '' ); ?>
								</span>
							</div>
							<?php $gf_stars = max( 1, min( 5, (int) ( $gf_t['stars'] ?? 5 ) ) ); ?>
							<div class="giftflow-st-carousel__stars" aria-label="<?php /* translators: %d: star rating (1-5) */ printf( esc_attr__( '%d out of 5 stars', 'giftflow' ), (int) $gf_stars ); ?>">
								<?php for ( $gf_i = 0; $gf_i < $gf_stars; $gf_i++ ) : ?>
									<?php echo $gf_star_svg; // phpcs:ignore ?>
								<?php endfor; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( count( $gf_testimonials ) > $gf_columns ) : ?>
			<button class="giftflow-st-carousel__prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
			</button>
			<button class="giftflow-st-carousel__next" aria-label="<?php esc_attr_e( 'Next testimonial', 'giftflow' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
			</button>
		<?php endif; ?>
		<div class="giftflow-st-carousel__pagination"></div>
	</div>
</div>
