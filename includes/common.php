<?php 
use GiftFlowWP\Frontend\Template;
use GiftFlowWp\Core\Role;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
/**
 * Common functions for the plugin
 * 
 * @package GiftFlowWP
 */

// Global helper functions for easy access

/**
 * Assign donor role to user
 *
 * @param int $user_id User ID
 * @return bool True if role was assigned, false otherwise
 */
function giftflowwp_assign_donor_role($user_id) {
  return \GiftFlowWp\Core\Role::get_instance()->assign_donor_role($user_id);
}

/**
* Remove donor role from user
*
* @param int $user_id User ID
* @return bool True if role was removed, false otherwise
*/
function giftflowwp_remove_donor_role($user_id) {
  return \GiftFlowWp\Core\Role::get_instance()->remove_donor_role($user_id);
}

/**
* Check if user has donor role
*
* @param int $user_id User ID
* @return bool True if user has donor role, false otherwise
*/
function giftflowwp_user_has_donor_role($user_id) {
  return \GiftFlowWp\Core\Role::get_instance()->user_has_donor_role($user_id);
}

/**
* Get the Role instance (for advanced usage)
*
* @return \GiftFlowWp\Core\Role
*/
function giftflowwp_get_role_manager() {
  return \GiftFlowWp\Core\Role::get_instance();
}

function giftflowwp_svg_icon($name) {
  $icons = require(__DIR__ . '/icons.php');
  return isset($icons[$name]) ? $icons[$name] : '';
}

/**
 * Get the raised amount for a campaign
 *
 * @param int $campaign_id The campaign ID
 * @return float The raised amount
 */
function giftflowwp_get_campaign_raised_amount($campaign_id) {
    // Get all donations for this campaign
    $donations = get_posts(array(
        'post_type' => 'donation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_campaign_id',
                'value' => $campaign_id,
                'compare' => '='
            ),
            array(
                'key' => '_status',
                'value' => 'completed',
                'compare' => '='
            )
        )
    ));

    $total_amount = 0;

    // Sum up all completed donations
    foreach ($donations as $donation) {
        $amount = get_post_meta($donation->ID, '_amount', true);
        if ($amount) {
            $total_amount += floatval($amount);
        }
    }

    return $total_amount;
}

/**
 * giftflowwp_get_campaign_goal_amount
 * 
 * @param int $campaign_id The campaign ID
 * @return float The goal amount
 */
function giftflowwp_get_campaign_goal_amount($campaign_id) {
    return get_post_meta($campaign_id, '_goal_amount', true);
}

/**
 * Get the percentage of raised amount compared to goal amount
 *
 * @param int $campaign_id The campaign ID
 * @return float The percentage (0-100)
 */
function giftflowwp_get_campaign_progress_percentage($campaign_id) {
    $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);
    $goal_amount = get_post_meta($campaign_id, '_goal_amount', true);
    
    if (!$goal_amount || floatval($goal_amount) <= 0) {
        return 0;
    }
    
    $percentage = ($raised_amount / floatval($goal_amount)) * 100;
    
    // Ensure percentage is between 0 and 100
    return min(100, max(0, round($percentage, 2)));
}

/**
 * Display the campaign progress percentage
 *
 * @param int $campaign_id The campaign ID
 * @return string HTML formatted progress percentage
 */
function giftflowwp_display_campaign_progress($campaign_id) {
    $percentage = giftflowwp_get_campaign_progress_percentage($campaign_id);
    $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);
    $goal_amount = get_post_meta($campaign_id, '_goal_amount', true);
    
    $html = sprintf(
        '<div class="campaign-progress">
            <div class="progress-bar">
                <div class="progress" style="width: %s%%"></div>
            </div>
            <div class="progress-stats">
                <span class="raised">%s</span> / <span class="goal">%s</span> (%s%%)
            </div>
        </div>',
        esc_attr($percentage),
        esc_html(number_format($raised_amount, 2)),
        esc_html(number_format($goal_amount, 2)),
        esc_html($percentage)
    );
    
    return $html;
}

function giftflowwp_get_common_currency() {
  $currencies = require(__DIR__ . '/currency.php');

  // apply filter to the currencies
  $currencies = apply_filters('giftflowwp_common_currencies', $currencies);

  return $currencies;
}

// get currency current 
function giftflowwp_get_current_currency() {
  $options = get_option('giftflowwp_general_options');
  $currency = isset($options['currency']) ? $options['currency'] : 'USD';
  return $currency;
}

// get symbol of currency
function giftflowwp_get_currency_symbol($currency) {
  $currencies = giftflowwp_get_common_currency();
  $_currency = array_filter($currencies, function($c) use ($currency) {
    return $c['code'] === $currency;
  });
  $_currency = array_values($_currency);
  return $_currency[0]['symbol'];
}

// get name of currency
function giftflowwp_get_currency_name($currency) {
  $currencies = giftflowwp_get_common_currency();
  $_currency = array_filter($currencies, function($c) use ($currency) {
    return $c['code'] === $currency;
  });
  $_currency = array_values($_currency);
  return $_currency[0]['name'];
}

/**
 * render currency formatted amount
 * 
 * @param float $amount
 * @param float $decimals
 * @param string $currency
 * @param string $template // default: {{currency_symbol}} {{amount}}
 * @return string
 */
function giftflowwp_render_currency_formatted_amount($amount, $decimals = 2, $currency = null, $template = '') {
  
  if (!$currency) {
    $currency = giftflowwp_get_current_currency();
  }
  $currency_symbol = giftflowwp_get_currency_symbol($currency);
  $amount = number_format($amount, $decimals);
  
  // replace array map 
  $replace = array(
    '{{currency_symbol}}' => $currency_symbol,
    '{{amount}}' => $amount,
  );

  if (!$template) {
    $template = giftflowwp_get_currency_template();
  }

  $amount = '<span class="giftflowwp-currency-formatted-amount">' . str_replace(array_keys($replace), array_values($replace), $template) . '</span>';

  $amount = apply_filters('giftflowwp_render_currency_formatted_amount', $amount, $currency, $decimals);
  return $amount;
}

// get symbol
function giftflowwp_get_global_currency_symbol() {
  $currency = giftflowwp_get_current_currency();
  $currencies = giftflowwp_get_common_currency();
  $_currency = array_filter($currencies, function($c) use ($currency) {
    return $c['code'] === $currency;
  });
  $_currency = array_values($_currency);
  return $_currency[0]['symbol'];
}

// get currency template
function giftflowwp_get_currency_template() {
  $options = get_option('giftflowwp_general_options');
  $currency_template = isset($options['currency_template']) ? $options['currency_template'] : '{{currency_symbol}}{{amount}}';
  return $currency_template;
}

function giftflowwp_get_currency_js_format_template() {
  $temp = giftflowwp_get_currency_template();
  $symbol = giftflowwp_get_global_currency_symbol();
  $template = str_replace('{{currency_symbol}}', $symbol, $temp);
  $template = str_replace('{{amount}}', '{{value}}', $template);
  return $template;
}

function giftflowwp_get_preset_donation_amounts() {
  $options = get_option('giftflowwp_general_options');
  $preset_donation_amounts = isset($options['preset_donation_amounts']) ? $options['preset_donation_amounts'] : '10, 25, 35';
  return $preset_donation_amounts;
}

/**
 * get preset donation amounts by campaign id
 * 
 * @param int $campaign_id
 * @return array
 */
function giftflowwp_get_preset_donation_amounts_by_campaign($campaign_id) {
  $preset_donation_amounts = get_post_meta($campaign_id, '_preset_donation_amounts', true);

  // unserialize if exists
  if (is_serialized($preset_donation_amounts)) {
    $preset_donation_amounts = unserialize($preset_donation_amounts);
  }
  

  return array_map(function($item) {
    return array(
      'amount' => (float)trim($item['amount']),
    );
  }, $preset_donation_amounts);
}

function giftflowwp_get_campaign_days_left($campaign_id) {
  $start_date = get_post_meta($campaign_id, '_start_date', true);
  $end_date = get_post_meta($campaign_id, '_end_date', true);
  
  if (!$start_date) {
    return 0;
  }
  

  // current date
  $current_date = current_time('timestamp');

  $start_date = strtotime($start_date);
  $end_date = strtotime($end_date);

  // if start date is in the future, return false
  if ($start_date > $current_date) {
    return false;
  }

  // if end date empty, return ''
  if (!$end_date) {
    return '';
  }

  
  // if end date is in the past, return true
  if ($end_date < $current_date) {
    return true;
  }

  $days_left = ceil(($end_date - $current_date) / 86400);

  // apply filter
  $days_left = apply_filters('giftflowwp_get_campaign_days_left', $days_left, $campaign_id);
  
  return $days_left;
}

// get all donations for the campaign id  
function giftflowwp_get_campaign_donations($campaign_id, $args = array(), $paged = 1) {

  $args = wp_parse_args($args, array(
    'posts_per_page' => apply_filters('giftflowwp_campaign_donations_per_page', 20),
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
    'post_type' => 'donation',
  ));

  $args['meta_key'] = '_campaign_id';
  $args['meta_value'] = $campaign_id;

  $donations = new WP_Query($args);

  // return donation posts, total, and pagination
  return array(
    'posts' => array_map(function($post) {
      $is_anonymous = get_post_meta($post->ID, '_anonymous_donation', true);

      $donor_meta = [];
      $donor_meta['id'] = get_post_meta($post->ID, '_donor_id', true);
      $donor_meta['name'] = get_the_title($donor_meta['id']);
      $donor_meta['email'] = get_post_meta($donor_meta['id'], '_email', true);
      $donor_meta['phone'] = get_post_meta($donor_meta['id'], '_phone', true);
      $donor_meta['address'] = get_post_meta($donor_meta['id'], '_address', true);
      $donor_meta['city'] = get_post_meta($donor_meta['id'], '_city', true);
      $donor_meta['state'] = get_post_meta($donor_meta['id'], '_state', true);
      $donor_meta['postal_code'] = get_post_meta($donor_meta['id'], '_postal_code', true);
      $donor_meta['country'] = get_post_meta($donor_meta['id'], '_country', true);

      if ($is_anonymous == 'yes') {
        $donor_meta['name'] = __('Anonymous 🍀', 'giftflowwp');
        $donor_meta['email'] = '';
        $donor_meta['phone'] = '';
        $donor_meta['address'] = '';
        $donor_meta['city'] = '';
        $donor_meta['state'] = '';
        $donor_meta['postal_code'] = '';
        $donor_meta['country'] = '';
      }

      return array(
        'id' => $post->ID,
        'amount' => get_post_meta($post->ID, '_amount', true),
        'amount_formatted' => giftflowwp_render_currency_formatted_amount(get_post_meta($post->ID, '_amount', true)),
        'payment_method' => get_post_meta($post->ID, '_payment_method', true),
        'status' => get_post_meta($post->ID, '_status', true),
        'transaction_id' => get_post_meta($post->ID, '_transaction_id', true),
        'donor_id' => get_post_meta($post->ID, '_donor_id', true),
        'donor_meta' => $donor_meta,
        'campaign_id' => get_post_meta($post->ID, '_campaign_id', true),
        'message' => get_post_meta($post->ID, '_donor_message', true),
        // anonymous
        'is_anonymous' => get_post_meta($post->ID, '_anonymous_donation', true),
        'date' => get_the_date('', $post->ID),
        'date_gmt' => get_gmt_from_date(get_the_date('Y-m-d H:i:s', $post->ID))
      );
    }, $donations->posts),
    'total' => $donations->found_posts,
    'pagination' => $donations->max_num_pages,
  );
}

function giftflowwp_stripe_payment_method_callback($method) {
  $icons = array(
		'error' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
    'checked' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check-icon lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>',
  );

  ?>
  <label class="donation-form__payment-method">
      <input type="radio" checked name="payment_method" value="<?php echo esc_attr($method['name']); ?>" required>
      <span class="donation-form__payment-method-content">
          <?php echo $method['icon']; ?>
          <span class="donation-form__payment-method-title"><?php echo esc_html($method['label']); ?></span>
      </span>
  </label>
  <div 
    class="donation-form__payment-method-description donation-form__payment-method-description--stripe donation-form__fields" 
    >
      <div class="donation-form__payment-notification">
        <?php echo $icons['checked']; ?>
        <p><?php _e('We use Stripe to process payments. Your payment information is encrypted and never stored on our servers.', 'giftflowwp'); ?></p>
      </div>

      <?php // name on card field ?>
      <div class="donation-form__field">
        <label for="card_name" class="donation-form__field-label"><?php _e('Name on card', 'giftflowwp'); ?></label>
        <input type="text" id="card_name" name="card_name" class="donation-form__field-input" required data-validate="required">

        <div class="donation-form__field-error custom-error-message">
          <?php echo $icons['error']; ?>
          <span class="custom-error-message-text">
            <?php _e('Name on card is required', 'giftflowwp'); ?>
          </span>
        </div>
      </div>
      
      <?php // card element ?>
      <div 
        class="donation-form__field" 
        data-custom-validate="true" 
        data-custom-validate-status="false" >
        <label for="card_number" class="donation-form__field-label"><?php _e('Card number', 'giftflowwp'); ?></label>
        <div id="STRIPE-CARD-ELEMENT"></div> <?php // Render card via stripe.js ?>

        <div class="donation-form__field-error custom-error-message">
          <?php echo $icons['error']; ?>
          <span class="custom-error-message-text">
            <?php _e('Card information is incomplete', 'giftflowwp'); ?>
          </span>
        </div>
      </div>
  </div>
  <?php
}

/**
 * Donation form thank you template
 */
function giftflowwp_donation_form_thank_you_section_html($args = array()) {
  // load template thank you
  $template = new Template();
  $template->load_template('donation-form-thank-you.php', $args);
}

// donation form error section
function giftflowwp_donation_form_error_section_html() {
  // load template error
  $template = new Template();
  $template->load_template('donation-form-error.php');
}

function giftflowwp_load_template($template_name, $args = array()) {
  $template = new Template();
  $template->load_template($template_name, $args);
}

// get donation data by donation id
function giftflowwp_get_donation_data_by_id($donation_id) {
  $donation_data = get_post($donation_id);

  if(!$donation_data) {
    return false;
  }

  $donation_data->donation_edit_url = get_edit_post_link($donation_id);

  $campaign_id = get_post_meta($donation_id, '_campaign_id', true);
  $donation_data->campaign_name = $campaign_id ? get_the_title($campaign_id) : '???';
  $donation_data->campaign_url = $campaign_id ? get_the_permalink($campaign_id) : '#';

  $donor_id = get_post_meta($donation_id, '_donor_id', true);
  // donor_name = first name + last name
  $donation_data->donor_name = $donor_id ? get_post_meta($donor_id, '_first_name', true) . ' ' . get_post_meta($donor_id, '_last_name', true) : '???';
  $donation_data->donor_email = $donor_id ? get_post_meta($donor_id, '_email', true) : '';

  // message 
  $donation_data->message = get_post_meta($donation_id, '_donor_message', true);
  // anonymous
  $donation_data->anonymous = get_post_meta($donation_id, '_anonymous_donation', true);

  $donation_data->amount = get_post_meta($donation_id, '_amount', true);
  $donation_data->__amount_formatted = giftflowwp_render_currency_formatted_amount($donation_data->amount);

  $donation_data->status = get_post_meta($donation_id, '_status', true);
  $donation_data->payment_method = get_post_meta($donation_id, '_payment_method', true);
  $donation_data->__date = get_the_date('', $donation_id);
  $donation_data->__date_gmt = get_gmt_from_date(get_the_date('Y-m-d H:i:s', $donation_id));

  return $donation_data;
}

// get donor account page
function giftflowwp_get_donor_account_page() {
  $options = get_option('giftflowwp_general_options');
  $donor_account_page = isset($options['donor_account_page']) ? $options['donor_account_page'] : '';

  // if empty please search by path 'donor-account'
  if (!$donor_account_page) {
    $donor_account_page = get_page_by_path('donor-account');
    // Validate that $donor_account_page is a valid WP_Post object before accessing its ID
    if ($donor_account_page && is_a($donor_account_page, 'WP_Post')) {
        $donor_account_page = $donor_account_page->ID;
    } else {
        $donor_account_page = '';
    }
    // $donor_account_page = $donor_account_page->ID;
  }

  return $donor_account_page;
}

// get thank donor page
function giftflowwp_get_thank_donor_page() {
  $options = get_option('giftflowwp_general_options');
  $thank_donor_page = isset($options['thank_donor_page']) ? $options['thank_donor_page'] : '';

  // if empty please search by path 'thank-donor'
  if (!$thank_donor_page) {
    $thank_donor_page = get_page_by_path('thank-donor');
    // Validate that $thank_donor_page is a valid WP_Post object before accessing its ID
    if ($thank_donor_page && is_a($thank_donor_page, 'WP_Post')) {
        $thank_donor_page = $thank_donor_page->ID;
    } else {
        $thank_donor_page = '';
    }
    // $thank_donor_page = $thank_donor_page->ID;
  }

  return $thank_donor_page;
}

/**
 * 
 * 
 * @param int $donation_id
 * @param mixed $payment_result
 * @return void
 */
function giftflowwp_auto_create_user_on_donation( $donation_id, $payment_result ) {

  // get donor id 
  $donor_id = get_post_meta($donation_id, '_donor_id', true);

  // get donor data
  $donor_data = giftflowwp_get_donor_data_by_id($donor_id);

  // check if user exists by email
  $user = get_user_by('email', $donor_data->email);
  if ($user) {
    return;
  }

  // create new user, update first name, last name, and set role is subscriber
  $password = wp_generate_password();
  $user_id = wp_create_user($donor_data->email, $password, $donor_data->email);
  if ( is_wp_error( $user_id ) ) {
    return;
  }

  // update user data
  wp_update_user(array(
    'ID' => $user_id,
    'first_name' => $donor_data->first_name,
    'last_name' => $donor_data->last_name,
  ));

  // assign donor role 
  giftflowwp_assign_donor_role($user_id);

  // add hook after create new user
  do_action('giftflowwp_new_user_on_first_time_donation', $user_id);

  // get donor account url 
  $donor_account_url = get_permalink(giftflowwp_get_donor_account_page());

  // load content mail template 
  ob_start();
  $new_user_email_data = array(
    'name' => $donor_data->first_name . ' ' . $donor_data->last_name,
    'username' => $donor_data->email,
    'password' => $password,
    'login_url' => $donor_account_url,
  );

  /**
   * Filter the data passed to the new user email template.
   *
   * @param array $new_user_email_data
   * @param object $donor_data
   * @param int $user_id
   * @param int $donor_id
   */
  $new_user_email_data = apply_filters(
    'giftflowwp_new_user_email_data',
    $new_user_email_data,
    $donor_data,
    $user_id,
    $donor_id
  );

  giftflowwp_load_template('email/new-user.php', $new_user_email_data);
  $content = ob_get_clean();

  // send mail to new user
  giftflowwp_send_mail_template(array(
    'to' => $donor_data->email,
    'subject' => sprintf( esc_html__('Welcome to %s', 'giftflowwp'), get_bloginfo('name') ),
    'header' => esc_html__('🍀 Your donor account has been created.', 'giftflowwp'),
    'content' => $content,
  ));
}

/**
 * get donor data by id
 * 
 * @param int $donor_id
 * @return object|null
 */
function giftflowwp_get_donor_data_by_id($donor_id = 0) {
  $donor_data = get_post($donor_id);

  if ( ! $donor_data || is_wp_error( $donor_data ) ) {
    return null;
  }

  // get meta fields
  $donor_data->email = get_post_meta($donor_id, '_email', true);
  $donor_data->first_name = get_post_meta($donor_id, '_first_name', true);
  $donor_data->last_name = get_post_meta($donor_id, '_last_name', true);
  $donor_data->phone = get_post_meta($donor_id, '_phone', true);
  $donor_data->address = get_post_meta($donor_id, '_address', true);
  $donor_data->city = get_post_meta($donor_id, '_city', true);
  $donor_data->state = get_post_meta($donor_id, '_state', true);
  $donor_data->postal_code = get_post_meta($donor_id, '_postal_code', true);
  $donor_data->country = get_post_meta($donor_id, '_country', true);

  return $donor_data;
}

/**
 * query donations by donor id, use wp_query
 * 
 * @param string $donor_id
 * @param int $page
 * @param int $per_page
 * @return array
 */
function giftflowwp_query_donation_by_donor_id($donor_id, $page = 1, $per_page = 20) {
  $donations = new WP_Query(array(
    'post_type' => 'donation',
    'meta_key' => '_donor_id',
    'meta_value' => $donor_id,
    'posts_per_page' => $per_page,
    'paged' => $page,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
  ));

  return $donations;
}

// get donor id by email
function giftflowwp_get_donor_id_by_email($email) {
  $donor = get_posts(array(
    'post_type' => 'donor',
    'meta_key' => '_email',
    'meta_value' => $email,
    'posts_per_page' => 1,
  ));

  if ($donor) {
    return $donor[0]->ID;
  }

  return 0;
}

/**
 * get donations by user id
 * 
 * @param int $user_id
 * @param int $page
 * @param int $per_page
 * @return wp_query
 */
function giftflowwp_get_donations_by_user($user_id, $page = 1, $per_page = 20) {
  $user_email = get_user_by('id', $user_id)->user_email;
  $donor_id = giftflowwp_get_donor_id_by_email($user_email);
  $donations = giftflowwp_query_donation_by_donor_id($donor_id, $page, $per_page);
  return $donations;
}

function giftflowwp_process_bar_of_campaign_donations($campaign_id) {
  $progress_percentage = giftflowwp_get_campaign_progress_percentage($campaign_id);

  ?>
  <div class="giftflowwp-campaign-progress-bar" title="<?php echo esc_attr($progress_percentage); ?>%" style="max-wdith: 100%; margin: 0 0 .5em;"> 
    <div class="progress-bar" style="height: 3px; background-color: #f1f5f9; overflow: hidden; width: 100%; border-radius: 1px;">
      <div class="progress" style="width: <?php echo esc_attr($progress_percentage); ?>%; height: 100%; background: linear-gradient(90deg, #22c55e, #4ade80);"></div>
    </div>
    <!-- <div class="progress-label" style="margin-top: 0.5em; font-size: 0.95em;">
      <?php // echo esc_html($progress_percentage); ?>%
    </div> -->
  </div>
  <?php
}

/**
 * get donor user information
 * 
 * @param int $user_id
 * @return array
 */
function giftflowwp_get_donor_user_information($user_id) {
  // get user data 
  $user_data = get_user_by('id', $user_id);
  
  // giftflowwp_get_donor_id_by_email
  $donor_id = giftflowwp_get_donor_id_by_email($user_data->user_email);

  // get donor data
  $donor_data = giftflowwp_get_donor_data_by_id($donor_id);

  $donor_information = array(
    // wp user
    'user_id' => $user_data->ID,
    'first_name' => $user_data->first_name,
    'last_name' => $user_data->last_name,
    'email' => $user_data->user_email,

    // donor 
    'donor_id' => $donor_id,
    'phone' => $donor_data->phone,
    'address' => $donor_data->address,
    'city' => $donor_data->city,
    'state' => $donor_data->state,
    'postal_code' => $donor_data->postal_code,
    'country' => $donor_data->country,
  );

  return $donor_information;
}