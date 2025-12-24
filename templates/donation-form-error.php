<?php
/**
 * Template for donation form error
 *
 * @package GiftFlow
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<section id="donation-error" class="donation-form__step-panel step-error" data-step="error">
	<div class="donation-form__step-panel-content donation-form__error">
		<?php echo wp_kses( giftflow_svg_icon( 'error-circle' ), giftflow_allowed_svg_tags() ); ?>
		
		<div class="donation-form__error-content">
			<h2 class="donation-form__error-title">
				<?php esc_html_e( 'Oops! Something Went Wrong', 'giftflow' ); ?>
			</h2>
			
			<div class="donation-form__error-details">
				<p class="donation-form__error-message"></p>
				<p class="donation-form__error-help">
					<?php esc_html_e( 'Don\'t worry - your donation wasn\'t processed. You can try the following:', 'giftflow' ); ?>
				</p>
				
				<ul class="donation-form__error-tips">
					<li>
						<?php esc_html_e( 'Check your internet connection and refresh the page', 'giftflow' ); ?>
						<small><?php esc_html_e( 'A stable connection is required for secure payment processing', 'giftflow' ); ?></small>
					</li>
					<li>
						<?php esc_html_e( 'Verify your payment information is correct', 'giftflow' ); ?>
						<small><?php esc_html_e( 'Double-check your card number, expiration date, and CVV code', 'giftflow' ); ?></small>
					</li>
					<li>
						<?php esc_html_e( 'Try a different payment method or card', 'giftflow' ); ?>
						<small><?php esc_html_e( 'We accept credit cards, PayPal, and bank transfers', 'giftflow' ); ?></small>
					</li>
					<li>
						<?php esc_html_e( 'Clear your browser cache and cookies', 'giftflow' ); ?>
						<small><?php esc_html_e( 'This can resolve common payment form issues', 'giftflow' ); ?></small>
					</li>
				</ul>

				<div class="donation-form__error-actions">
					<button type="button" class="donation-form__button donation-form__button--retry">
						<?php echo wp_kses( giftflow_svg_icon( 'refresh' ), giftflow_allowed_svg_tags() ); ?>
						<?php esc_html_e( 'Try Again', 'giftflow' ); ?>
					</button>
					<a href="#" class="donation-form__button donation-form__button--support">
						<?php echo wp_kses( giftflow_svg_icon( 'help' ), giftflow_allowed_svg_tags() ); ?>
						<?php esc_html_e( 'Contact Support', 'giftflow' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>