<?php
/**
 * Email Service — centralized email sending with template support.
 *
 * @package GiftFlow
 * @subpackage Services
 */

namespace GiftFlow\Services;

use GiftFlow\Core\AbstractModule;
use GiftFlow\Core\Container;
use GiftFlow\Settings\SettingsRegistry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles donation confirmation emails, admin notifications,
 * and template-based email composition.
 */
class EmailService extends AbstractModule {

	/**
	 * Settings registry.
	 *
	 * @var SettingsRegistry
	 */
	private SettingsRegistry $settings;

	/**
	 * Constructor.
	 *
	 * @param Container        $container Service container.
	 * @param SettingsRegistry $settings  Settings registry.
	 */
	public function __construct( Container $container, SettingsRegistry $settings ) {
		parent::__construct( $container );
		$this->settings = $settings;
	}

	/**
	 * No hooks needed — called imperatively.
	 *
	 * @return void
	 */
	public function register(): void {}

	/**
	 * Send donation confirmation to the donor.
	 *
	 * @param string $donor_email   Donor email.
	 * @param string $donor_name    Donor name.
	 * @param int    $amount_cents  Donation amount in cents.
	 * @param string $campaign_name Campaign name.
	 * @param string $transaction_id Transaction ID.
	 * @return bool
	 */
	public function send_donor_confirmation(
		string $donor_email,
		string $donor_name,
		int $amount_cents,
		string $campaign_name,
		string $transaction_id = ''
	): bool {
		$email_settings = $this->settings->get_group( 'email' );
		$from_name      = $email_settings['from_name'] ?: get_bloginfo( 'name' );
		$subject        = $this->replace_placeholders(
			$email_settings['donor_subject_template'],
			$donor_name,
			$amount_cents,
			$campaign_name
		);

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $from_name . ' <' . get_option( 'admin_email' ) . '>',
		);

		$message = $this->build_donor_message(
			$donor_name,
			$amount_cents,
			$campaign_name,
			$transaction_id
		);

		return wp_mail( $donor_email, $subject, $message, $headers );
	}

	/**
	 * Send new donation notification to admin.
	 *
	 * @param string $donor_name    Donor name.
	 * @param int    $amount_cents  Donation amount in cents.
	 * @param string $campaign_name Campaign name.
	 * @param string $transaction_id Transaction ID.
	 * @return bool
	 */
	public function send_admin_notification(
		string $donor_name,
		int $amount_cents,
		string $campaign_name,
		string $transaction_id = ''
	): bool {
		$email_settings = $this->settings->get_group( 'email' );
		$admin_email    = $email_settings['admin_address'] ?: get_option( 'admin_email' );
		$from_name      = $email_settings['from_name'] ?: get_bloginfo( 'name' );
		$subject        = $this->replace_placeholders(
			$email_settings['admin_subject_template'],
			$donor_name,
			$amount_cents,
			$campaign_name
		);

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $from_name . ' <' . get_option( 'admin_email' ) . '>',
		);

		$message = $this->build_admin_message(
			$donor_name,
			$amount_cents,
			$campaign_name,
			$transaction_id
		);

		return wp_mail( $admin_email, $subject, $message, $headers );
	}

	/**
	 * Replace placeholders in email templates.
	 *
	 * @param string $template      Template string.
	 * @param string $donor_name    Donor name.
	 * @param int    $amount_cents  Amount in cents.
	 * @param string $campaign_name Campaign name.
	 * @return string
	 */
	private function replace_placeholders(
		string $template,
		string $donor_name,
		int $amount_cents,
		string $campaign_name
	): string {
		$currency_service = new CurrencyService( $this->container );
		$amount_formatted = $currency_service->format_amount( $amount_cents );

		$replacements = array(
			'{{donor_name}}'    => $donor_name,
			'{{amount}}'        => $amount_formatted,
			'{{campaign_name}}' => $campaign_name,
		);

		return str_replace(
			array_keys( $replacements ),
			array_values( $replacements ),
			$template
		);
	}

	/**
	 * Build donor confirmation email HTML.
	 *
	 * @param string $donor_name    Donor name.
	 * @param int    $amount_cents  Amount in cents.
	 * @param string $campaign_name Campaign name.
	 * @param string $transaction_id Transaction ID.
	 * @return string
	 */
	private function build_donor_message(
		string $donor_name,
		int $amount_cents,
		string $campaign_name,
		string $transaction_id
	): string {
		$currency_service = new CurrencyService( $this->container );
		$amount_formatted = $currency_service->format_amount( $amount_cents );

		ob_start();
		?>
		<div style="max-width:600px;margin:0 auto;font-family:sans-serif;">
			<h2><?php esc_html_e( 'Thank You for Your Donation!', 'giftflow' ); ?></h2>
			<p><?php echo esc_html( sprintf( __( 'Dear %s,', 'giftflow' ), $donor_name ) ); ?></p>
			<p><?php echo esc_html( sprintf( __( 'Your donation of %s to %s has been received.', 'giftflow' ), $amount_formatted, $campaign_name ) ); ?></p>
			<?php if ( $transaction_id ) : ?>
				<p><?php echo esc_html( sprintf( __( 'Transaction ID: %s', 'giftflow' ), $transaction_id ) ); ?></p>
			<?php endif; ?>
			<p><?php esc_html_e( 'Your generosity makes a real difference.', 'giftflow' ); ?></p>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Build admin notification email HTML.
	 *
	 * @param string $donor_name    Donor name.
	 * @param int    $amount_cents  Amount in cents.
	 * @param string $campaign_name Campaign name.
	 * @param string $transaction_id Transaction ID.
	 * @return string
	 */
	private function build_admin_message(
		string $donor_name,
		int $amount_cents,
		string $campaign_name,
		string $transaction_id
	): string {
		$currency_service = new CurrencyService( $this->container );
		$amount_formatted = $currency_service->format_amount( $amount_cents );
		$donations_url    = admin_url( 'edit.php?post_type=donation' );

		ob_start();
		?>
		<div style="max-width:600px;margin:0 auto;font-family:sans-serif;">
			<h2><?php esc_html_e( 'New Donation Received', 'giftflow' ); ?></h2>
			<p><?php echo esc_html( sprintf( __( '%s donated %s to %s.', 'giftflow' ), $donor_name, $amount_formatted, $campaign_name ) ); ?></p>
			<?php if ( $transaction_id ) : ?>
				<p><?php echo esc_html( sprintf( __( 'Transaction ID: %s', 'giftflow' ), $transaction_id ) ); ?></p>
			<?php endif; ?>
			<p><a href="<?php echo esc_url( $donations_url ); ?>"><?php esc_html_e( 'View all donations', 'giftflow' ); ?></a></p>
		</div>
		<?php
		return ob_get_clean();
	}
}
