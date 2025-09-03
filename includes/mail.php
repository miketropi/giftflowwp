<?php 
/**
 * Sendmail 
 */

function giftflowwp_send_mail_template($args = array()) {
  $args = wp_parse_args($args, array(
    'to' => '',
    'subject' => '',
    'header' => '',
    'content' => '',
    'footer' => '',
  ));

  $email_opts = get_option('giftflowwp_email_options');
  $email_from_name = $email_opts['email_from_name'] ?? get_bloginfo('name');
  $email_admin_address = $email_opts['email_admin_address'] ?? get_bloginfo('admin_email');

  $headers = array(
    'From: ' . $email_from_name . ' <' . $email_admin_address . '>',
    'Content-Type: text/html; charset=UTF-8'
  );

  // get html
  ob_start();
  giftflowwp_load_template('email/template-default.php', array(
    'header' => $args['header'],
    'content' => $args['content'],
    'footer' => $args['footer']
  ));
  $__html = ob_get_clean();


  return wp_mail($args['to'], $args['subject'], $__html, $headers);
}

function giftflowwp_send_mail_notification_donation_to_admin() {
  $email_opts = get_option('giftflowwp_email_options');
  $admin_email = $email_opts['email_admin_address'];

  giftflowwp_send_mail_template($admin_email, 'New Donation', 'New donation received');
}

// test mail ajax
add_action('wp_ajax_giftflowwp_test_send_mail', 'giftflowwp_test_send_mail_ajax');
function giftflowwp_test_send_mail_ajax() {

  // check nonce
  if (!wp_verify_nonce($_POST['nonce'], 'giftflowwp_admin_nonce')) {
    wp_send_json_error('Invalid nonce');
  }

  // name 
  $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
  
  // do action test mail
  do_action('giftflowwp_test_send_mail', $name);
  die();
}

add_action('giftflowwp_test_send_mail', 'giftflowwp_test_send_mail_action');
function giftflowwp_test_send_mail_action($name) {

  $email_opts = get_option('giftflowwp_email_options');
  $admin_email = $email_opts['email_admin_address'];

  switch ($name) {
    case 'admin_new_donation':
      ob_start();
      giftflowwp_load_template('email/new-donation-admin.php', array(
        'campaign_name' => '<Campaign Name>',
        'campaign_url' => '#',
        'donor_name' => '<Donor Name>',
        'donor_email' => '<Donor Email>',
        'amount' => '<Amount>',
        'date' => '<Date>',
        'status' => '<Status>',
        'payment_method' => '<Payment Method>'
      ));
      $content = ob_get_clean();
      return giftflowwp_send_mail_template(array(
        'to' => $admin_email,
        'subject' => 'New Donation: <Campaign Name>',
        'header' => 'New donation received',
        'content' => $content
      ));
      break;
    case 'donor_new_donation':
      return giftflowwp_send_mail_template(array(
        'to' => $admin_email,
        'subject' => 'New Donation',
        'content' => 'New donation received'
      ));
      break;
    case 'donor_thanks':
      return giftflowwp_send_mail_template(array(
        'to' => $admin_email,
        'subject' => 'New Donation',
        'content' => 'New donation received'
      ));
      break;
  }
}