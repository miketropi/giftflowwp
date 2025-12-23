<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$donation_id = $donation_id ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$campaign_name = $campaign_name ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$campaign_url = $campaign_url ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$donor_name = $donor_name ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$donor_email = $donor_email ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$amount = $amount ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$date = $date ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$donor_dashboard_url = $donor_dashboard_url ?? '';

// Get site information
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$site_name = get_bloginfo( 'name' );
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$site_url = home_url();
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$admin_email = get_option( 'admin_email' );
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$accent_color = '#0b57d0';
?>

<h2 style="font-size: 1.2rem; font-weight: 600; margin: 0 0 1rem 0; color: black;">
	<?php esc_html_e( 'Thank You for Your Generous Donation!', 'giftflow' ); ?>
</h2>

<p style="margin: 0 0 1.5rem 0; line-height: 1.6; opacity: .8; font-size: .9rem;">
	<?php
	if ( ! empty( $donor_name ) ) {
		printf(
			/* translators: %s: Donor name */
			esc_html__( 'Dear %s,', 'giftflow' ),
			esc_html( $donor_name )
		);
	} else {
		esc_html_e( 'Dear Donor,', 'giftflow' );
	}
	?>
</p>

<p style="margin: 0 0 1.5rem 0; line-height: 1.6; opacity: .8; font-size: .9rem;">
	<?php esc_html_e( 'We are incredibly grateful for your generous donation. Your support makes a real difference and helps us continue our important work.', 'giftflow' ); ?>
</p>

<table id="giftflow-email-table" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%; border-collapse: collapse; margin: 1.8rem 0 0; background: #ffffff; border: 1px solid #e9ecef; border-radius: 0.3rem; overflow: hidden;">
	
	<!-- Donation Summary Header -->
	<tr>
		<td style="padding: .8rem 0; border-bottom: 1px solid #e9ecef;">
			<h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: black;">
				<?php esc_html_e( 'Donation Summary', 'giftflow' ); ?>
			</h3>
		</td>
	</tr>
	
	<!-- Amount -->
	<tr>
		<td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
			<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
				<tr>
					<td style="width: 30%; padding: 0; vertical-align: top;">
						<p style="font-size: 0.9rem; margin: 0;">
							<?php esc_html_e( 'Amount:', 'giftflow' ); ?>
						</p>
					</td>
					<td style="width: 70%; padding: 0; vertical-align: top;">
						<span style="color: <?php echo esc_attr( $accent_color ); ?>; font-weight: 600;">
							<?php echo wp_kses_post( $amount ); ?>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<!-- Date -->
	<tr>
		<td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
			<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
				<tr>
					<td style="width: 30%; padding: 0; vertical-align: top;">
						<p style="font-size: 0.9rem; margin: 0;">
							<?php esc_html_e( 'Date:', 'giftflow' ); ?>
						</p>
					</td>
					<td style="width: 70%; padding: 0; vertical-align: top;">
						<span style="">
							<?php echo esc_html( $date ); ?>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<!-- Campaign -->
	<?php if ( ! empty( $campaign_name ) ) : ?>
	<tr>
		<td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
			<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
				<tr>
					<td style="width: 30%; padding: 0; vertical-align: top;">
						<p style="font-size: 0.9rem; margin: 0;">
							<?php esc_html_e( 'Campaign:', 'giftflow' ); ?>
						</p>
					</td>
					<td style="width: 70%; padding: 0; vertical-align: top;">
						<?php if ( ! empty( $campaign_url ) ) : ?>
							<a href="<?php echo esc_url( $campaign_url ); ?>" style="color: <?php echo esc_attr( $accent_color ); ?>; text-decoration: none;">
								<?php echo esc_html( $campaign_name ); ?>
							</a>
						<?php else : ?>
							<span style="">
								<?php echo esc_html( $campaign_name ); ?>
							</span>
						<?php endif; ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php endif; ?>
	
	<!-- Transaction ID (if available) -->
	<?php if ( ! empty( $transaction_id ) ) : ?>
	<tr>
		<td style="padding: .8rem 0;">
			<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
				<tr>
					<td style="width: 30%; padding: 0; vertical-align: top;">
						<p style="font-size: 0.9rem; margin: 0;">
							<?php esc_html_e( 'Transaction ID:', 'giftflow' ); ?>
						</p>
					</td>
					<td style="width: 70%; padding: 0; vertical-align: top;">
						<span style="font-family: monospace; font-size: 0.85rem; color: #666;">
							<?php echo esc_html( $transaction_id ); ?>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php endif; ?>
	
</table>

<!-- Impact Message -->
<div style="background: #f8f9fa;     padding: 1.5rem;     margin: 1.5rem 0;     border-radius: .3rem;     border: solid 1px #eee;">
	<h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600;">
		<?php esc_html_e( 'Your Impact', 'giftflow' ); ?>
	</h4>
	<p style="margin: 0; opacity: .8; font-size: 0.9rem; line-height: 1.5;">
		<?php esc_html_e( 'Your generous donation helps us continue our mission and make a positive impact in our community. Every contribution, no matter the size, makes a difference.', 'giftflow' ); ?>
	</p>
</div>

<!-- Action Buttons -->
<div style="text-align: center; margin: 2rem 0;">
	<table cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; width: 100%;">
		<tr>
			<?php if ( ! empty( $donor_dashboard_url ) ) : ?>
			<td style="padding: 0 0.5rem;">
				<a 
					href="<?php echo esc_url( $donor_dashboard_url ); ?>" 
					style="border-bottom: solid 2px;     font-weight: 600;     text-decoration: none;">
					<?php esc_html_e( '👉 View My Donations', 'giftflow' ); ?>
				</a>
			</td>
			<?php endif; ?>
			<td style="padding: 0 0.5rem;">
				<a 
					href="<?php echo esc_url( $site_url ); ?>" 
					style="border-bottom: solid 2px;     font-weight: 600;     text-decoration: none;">
					<?php esc_html_e( '🌎 Visit Our Website', 'giftflow' ); ?>
				</a>
			</td>
		</tr>
	</table>
</div>

<!-- Thank You Message -->
<p style="margin: 1.5rem 0 0 0; color: #555; font-size: 0.9rem; line-height: 1.6; text-align: center;">
	<?php esc_html_e( 'Thank you again for your generosity and support. Together, we can make a difference!', 'giftflow' ); ?>
</p>
