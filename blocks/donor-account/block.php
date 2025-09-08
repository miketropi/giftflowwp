<?php 
function giftflowwp_donor_account_block() {
    register_block_type(
        'giftflowwp/donor-account',
        array(
            'api_version' => 3,
            'render_callback' => 'giftflowwp_donor_account_block_render',
            'attributes' => array(),
        )
    );
}

add_action('init', 'giftflowwp_donor_account_block');

function giftflowwp_donor_account_block_render($attributes, $content, $block) {

  // get current user loggin
  $current_user = wp_get_current_user();

  ob_start();
  if ($current_user->ID) {

    $tabs = giftflowwp_donor_account_tabs();

    // load template donor account
    giftflowwp_load_template('block/donor-account.php', array(
      'current_user' => $current_user,
      'attributes' => $attributes,
      'tabs' => $tabs,
      'active_tab' => get_query_var('tab', $tabs[0]['slug']),
      'root_donor_account_page' => get_permalink(giftflowwp_get_donor_account_page()),
    ));
  } else {
    // load template login form
    giftflowwp_load_template('login-form.php', array(
      'attributes' => $attributes,
    ));
  } 
  return ob_get_clean();
}

function giftflowwp_donor_account_tabs() {
    $tabs = [
      [
        'label' => esc_html__('Dashboard', 'giftflowwp'),
        'slug' => 'dashboard',
        'icon' => giftflowwp_svg_icon('gauge'),
        'url' => get_permalink(giftflowwp_get_donor_account_page()),
        'callback' => 'giftflowwp_donor_account_dashboard_callback',
      ],
      [
        'label' => esc_html__('My Donations', 'giftflowwp'),
        'slug' => 'donations',
        'icon' => giftflowwp_svg_icon('clipboard-clock'),
        'url' => get_permalink(giftflowwp_get_donor_account_page()), 
        'callback' => 'giftflowwp_donor_account_my_donations_callback',
      ],
      // campaign bookmarks
      [
        'label' => esc_html__('Bookmarks', 'giftflowwp'),
        'slug' => 'bookmarks',
        'icon' => giftflowwp_svg_icon('bookmark'),
        'url' => get_permalink(giftflowwp_get_donor_account_page()),
        'callback' => 'giftflowwp_donor_account_campaign_bookmarks_callback',
      ],
      // donor infomation
      [
        'label' => esc_html__('My Account', 'giftflowwp'),
        'slug' => 'my-account',
        'icon' => giftflowwp_svg_icon('user'),
        'callback' => 'giftflowwp_donor_account_my_account_callback',
      ],
    ];

    /**
      * Filter the donor account tabs.
      *
      * @since 1.0.0
      *
      * @param array $tabs The array of donor account tabs.
      */
    return apply_filters('giftflowwp_donor_account_tabs', $tabs);
}

add_action('init', function() {
  $donor_account_page_id = giftflowwp_get_donor_account_page();
  $slug = get_post_field('post_name', $donor_account_page_id);

  add_rewrite_rule(
      '^' . $slug . '/([^/]*)/?',
      'index.php?pagename=' . $slug . '&tab=$matches[1]',
      'top'
  );

  flush_rewrite_rules();
});

add_filter('query_vars', function($vars) {
  $vars[] = 'tab'; // register tab query var
  return $vars;
});

// donor_account_page_url
function giftflowwp_donor_account_page_url($tab) {
  $donor_account_page_id = giftflowwp_get_donor_account_page();
  $slug = get_post_field('post_name', $donor_account_page_id);

  $tab = trim( $tab, '/' );
  if ( get_option('permalink_structure') ) {
      // pretty permalinks enabled
      return home_url( '/' . $slug . '/' . $tab );
  } else {
      // plain permalinks -> query-string style
      return add_query_arg( array(
          'pagename' => $slug,
          'tab'      => $tab,
      ), home_url('/') );
  }
}

function giftflowwp_donor_account_dashboard_callback() {
  // load template donor-account--dashboard
  $current_user = wp_get_current_user();
  giftflowwp_load_template('block/donor-account--dashboard.php', array(
    'current_user' => $current_user,
  ));
}

function giftflowwp_donor_account_my_donations_callback() {
  // get current user
  $current_user = wp_get_current_user();

  $id = isset($_GET['_id']) ? intval($_GET['_id']) : null;

  if ($id) {

    // validate post type 
    if (get_post_type($id) != 'donation') {
      // return template not found
      giftflowwp_load_template('block/donor-account--not-found.php', array(
        'current_user' => $current_user,
        'id' => $id,
      ));
      return;
    }

    // get donation data by id 
    $donation = giftflowwp_get_donation_data_by_id($id);

    // check $donation is exist
    if (!$donation) {
      // return template not found
      giftflowwp_load_template('block/donor-account--not-found.php', array(
        'current_user' => $current_user,
        'id' => $id,
      ));
      return;
    }

    // check $donation donor_email is equal to current user email
    if ($donation->donor_email != $current_user->user_email) {
      // return template not allowed to view this donation
      giftflowwp_load_template('block/donor-account--not-allowed.php', array(
        'current_user' => $current_user,
        'id' => $id,
      ));
      return;
    }
    

    // view donation template 
    giftflowwp_load_template('block/donor-account--my-donations--detail.php', array(
      'current_user' => $current_user,
      'id' => $id,
      'donation' => $donation,
    ));
    return;
  }

  $page = isset($_GET['_page']) ? intval($_GET['_page']) : 1;
  if ($page < 1) {
    $page = 1;
  }

  // query donations by donor id
  $donations = giftflowwp_get_donations_by_user($current_user->ID, $page, 20);

  // load template donor-account--my-donations
  giftflowwp_load_template('block/donor-account--my-donations.php', array(
    'current_user' => $current_user,
    'donations' => $donations,
    'page' => $page,
  ));
}
