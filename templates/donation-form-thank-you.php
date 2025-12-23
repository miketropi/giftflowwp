<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<section id="donation-thank-you" class="donation-form__step-panel step-thank-you" data-step="thank-you">
	<div class="donation-form__step-panel-content donation-form__thank-you">
		<?php echo wp_kses( giftflow_svg_icon( 'checkmark-circle' ), giftflow_allowed_svg_tags() ); // Add success icon ?>
		
		<div class="donation-form__thank-you-content">
			<h2 class="donation-form__thank-you-title">
				<?php esc_html_e( 'Thank You for Your Generous Support!', 'giftflow' ); ?>
			</h2>
			
			<div class="donation-form__thank-you-details">
				<p class="donation-form__thank-you-message">
					<?php esc_html_e( 'Your donation makes a real difference. A confirmation email has been sent to your inbox.', 'giftflow' ); ?>
				</p>
				
				<div class="donation-form__thank-you-summary">
					<div class="donation-form__thank-you-amount">
						<span class="gfw-monofont" data-output="donation_amount" data-format-template="<?php echo esc_attr( $currency_format_template ); ?>"></span>
					</div>
					<div class="donation-form__thank-you-campaign">
						<?php echo esc_html( $campaign_title ); ?>
					</div>
				</div>
			</div>

			<div class="donation-form__thank-you-actions">
				<a href="<?php echo esc_url( get_permalink( $campaign_id ) ); ?>" class="donation-form__button">
					<?php esc_html_e( 'Return to Campaign', 'giftflow' ); ?>
				</a>
				<!-- <button type="button" class="donation-form__button donation-form__button--share">
					<?php // echo wp_kses(giftflow_svg_icon('share'), giftflow_allowed_svg_tags()); ?>
					<?php // esc_html_e('Share This Campaign', 'giftflow'); ?>
				</button> -->
			</div>
		</div>
	</div>
</section>