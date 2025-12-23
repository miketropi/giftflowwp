<?php
/**
 * Sendmail
 */
function giftflow_send_mail_template( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'to'      => '',
			'subject' => '',
			'header'  => '',
			'content' => '',
			'footer'  => '',
		)
	);

	$email_opts          = get_option( 'giftflow_email_options' );
	$email_from_name     = $email_opts['email_from_name'] ?? get_bloginfo( 'name' );
	$email_admin_address = $email_opts['email_admin_address'] ?? get_bloginfo( 'admin_email' );

	$headers = array(
		'From: ' . $email_from_name . ' <' . $email_admin_address . '>',
		'Content-Type: text/html; charset=UTF-8',
	);

	// get html
	ob_start();
	giftflow_load_template(
		'email/template-default.php',
		array(
			'header'  => $args['header'],
			'content' => $args['content'],
			'footer'  => $args['footer'],
		)
	);
	$__html = ob_get_clean();

	return wp_mail( $args['to'], $args['subject'], $__html, $headers );
}

// test mail ajax
add_action( 'wp_ajax_giftflow_test_send_mail', 'giftflow_test_send_mail_ajax' );
function giftflow_test_send_mail_ajax() {

	// check nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'giftflow_admin_nonce' ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}

	// name
	$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';

	// do action test mail
	do_action( 'giftflow_test_send_mail', $name );
	die();
}

add_action( 'giftflow_test_send_mail', 'giftflow_test_send_mail_action' );
function giftflow_test_send_mail_action( $name ) {

	$email_opts  = get_option( 'giftflow_email_options' );
	$admin_email = $email_opts['email_admin_address'];

	switch ( $name ) {

		case 'admin_new_donation':
			ob_start();
			giftflow_load_template(
				'email/new-donation-admin.php',
				array(
					'campaign_name'  => '<Campaign Name>',
					'campaign_url'   => '#',
					'donor_name'     => '<Donor Name>',
					'donor_email'    => '<Donor Email>',
					'amount'         => '<Amount>',
					'date'           => '<Date>',
					'status'         => '<Status>',
					'payment_method' => '<Payment Method>',
				)
			);
			$content = ob_get_clean();
			return giftflow_send_mail_template(
				array(
					'to'      => $admin_email,
					'subject' => 'New Donation: <Campaign Name>',
					'header'  => 'New donation received',
					'content' => $content,
				)
			);
		break;

		case 'donor_thanks':
			ob_start();
			giftflow_load_template(
				'email/thanks-donor.php',
				array(
					'campaign_name'       => '<Campaign Name>',
					'campaign_url'        => '#',
					'donor_name'          => '<Donor Name>',
					'donor_email'         => '<Donor Email>',
					'amount'              => '<Amount>',
					'date'                => '<Date>',
					'donor_dashboard_url' => '#',
				)
			);
			$content = ob_get_clean();

			return giftflow_send_mail_template(
				array(
					'to'      => $admin_email,
					'subject' => 'Hi <Donor Name>, Thanks for Your Donation',
					'header'  => 'Thanks for Your Donation',
					'content' => $content,
				)
			);
		break;

		case 'new_user_first_time_donation':
			ob_start();
			giftflow_load_template(
				'email/new-user.php',
				array(
					'name'      => '<Name>',
					'username'  => '<Username>',
					'password'  => '<Password>',
					'login_url' => '<Login URL>',
				)
			);
			$content = ob_get_clean();
			return giftflow_send_mail_template(
				array(
					'to'      => $admin_email,
					'subject' => 'New User Registered',
					'header'  => 'New User Registered',
					'content' => $content,
				)
			);
		break;
	}
}

add_action( 'giftflow_donation_after_payment_processed', 'giftflow_send_mail_notification_donation_to_admin', 10, 2 );
function giftflow_send_mail_notification_donation_to_admin( $donation_id, $payment_result ) {
	$email_opts  = get_option( 'giftflow_email_options' );
	$admin_email = $email_opts['email_admin_address'];

	// get donation data
	$donation_data = giftflow_get_donation_data_by_id( $donation_id );

	ob_start();
	/**
	 * Allow filtering/modifying the arguments passed to the admin new donation email template.
	 *
	 * @param array $args The arguments for the email template.
	 * @param int $donation_id The donation ID.
	 * @param object $donation_data The donation data object.
	 */
	$admin_email_args = apply_filters(
		'giftflow_new_donation_admin_email_args',
		array(
			'donation_id'       => $donation_id,
			'donation_edit_url' => $donation_data->donation_edit_url,
			'campaign_name'     => $donation_data->campaign_name,
			'campaign_url'      => $donation_data->campaign_url,
			'donor_name'        => $donation_data->donor_name,
			'donor_email'       => $donation_data->donor_email,
			'amount'            => $donation_data->__amount_formatted,
			'date'              => $donation_data->__date,
			'status'            => $donation_data->status,
			'payment_method'    => $donation_data->payment_method,
		),
		$donation_id,
		$donation_data
	);

	giftflow_load_template( 'email/new-donation-admin.php', $admin_email_args );
	$content = ob_get_clean();

	return giftflow_send_mail_template(
		array(
			'to'      => $admin_email,
			/* translators: %s: Campaign name for the new donation notification email subject */
			'subject' => sprintf( esc_html__( 'New Donation — %s', 'giftflow' ), $donation_data->campaign_name ),
			'header'  => esc_html__( 'New donation received', 'giftflow' ),
			'content' => $content,
		)
	);
}

add_action( 'giftflow_donation_after_payment_processed', 'giftflow_send_mail_thank_you_to_donor_payment_successful', 12, 2 );
function giftflow_send_mail_thank_you_to_donor_payment_successful( $donation_id, $payment_result ) {
	if ( $payment_result != true ) {
		return;
	}

	// get giftflow_get_donor_account_page() url
	$donor_account_url = get_permalink( giftflow_get_donor_account_page() );

	// get donation data
	$donation_data = giftflow_get_donation_data_by_id( $donation_id );

	// send thanks email
	ob_start();
	/**
	 * Allow filtering/modifying the arguments passed to the donor thank you email template.
	 *
	 * @param array $args The arguments for the email template.
	 * @param int $donation_id The donation ID.
	 * @param object $donation_data The donation data object.
	 */
	$thanks_donor_args = apply_filters(
		'giftflow_thanks_donor_email_args',
		array(
			'donation_id'         => $donation_id,
			'campaign_name'       => $donation_data->campaign_name,
			'campaign_url'        => $donation_data->campaign_url,
			'donor_name'          => $donation_data->donor_name,
			'donor_email'         => $donation_data->donor_email,
			'amount'              => $donation_data->__amount_formatted,
			'date'                => $donation_data->__date,
			'donor_dashboard_url' => $donor_account_url,
		),
		$donation_id,
		$donation_data
	);

	giftflow_load_template( 'email/thanks-donor.php', $thanks_donor_args );
	$content = ob_get_clean();

	return giftflow_send_mail_template(
		array(
			'to'      => $donation_data->donor_email,
			/* translators: %s: Campaign name for donor thank you email subject */
			'subject' => sprintf( esc_html__( 'Thank You for Your Donation — %s', 'giftflow' ), $donation_data->campaign_name ),
			'header'  => esc_html__( 'Thank You for Your Donation', 'giftflow' ),
			'content' => $content,
		)
	);
}
