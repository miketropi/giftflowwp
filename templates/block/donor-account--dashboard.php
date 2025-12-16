<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// current user
$current_user = wp_get_current_user();

// get name user
$name = implode(' ', [$current_user->first_name, $current_user->last_name]);
if ( empty( trim( $name ) ) ) { $name = 'donor'; }
?>
<div class="giftflowwp-donor-account-dashboard">
  <div class="giftflowwp-donor-account-dashboard__welcome">
    <h2 class="giftflowwp-donor-account-dashboard__title">
      <?php
        /* translators: %s: Donor's full name */
        printf( esc_html__( 'Welcome, %s!', 'giftflowwp' ), esc_html( $name ) );
      ?>
    </h2>
    <p>
      <?php esc_html_e('Thank you for your generous support. Your contributions make a real difference!', 'giftflowwp'); ?>
    </p>
    <p>
      <?php esc_html_e('From supporting urgent campaigns to helping us reach long-term goals, every donation you make empowers positive change in our community.', 'giftflowwp'); ?>
    </p>
    <ul>
      <li><?php esc_html_e('Track your recent donations and see the impact you\'ve made.', 'giftflowwp'); ?></li>
      <li><?php esc_html_e('Bookmark campaigns you care about for easy access.', 'giftflowwp'); ?></li>
      <li><?php esc_html_e('Update your profile to personalize your giving experience.', 'giftflowwp'); ?></li>
    </ul>
  </div>
  <div class="giftflowwp-donor-account-dashboard__quick-actions">
    <h3><?php esc_html_e('Quick Actions', 'giftflowwp'); ?></h3>
    <ul>
      <li>
        <a href="<?php echo esc_url( giftflowwp_donor_account_page_url('donations') ); ?>" class="giftflowwp-dashboard-action">
          <?php echo wp_kses(giftflowwp_svg_icon('clipboard-clock'), giftflowwp_allowed_svg_tags()); ?>
          <span><?php esc_html_e('View My Donations', 'giftflowwp'); ?></span>
        </a>
      </li>
      <li>
        <a href="<?php echo esc_url( giftflowwp_donor_account_page_url('my-account') ); ?>" class="giftflowwp-dashboard-action">
          <?php echo wp_kses(giftflowwp_svg_icon('user'), giftflowwp_allowed_svg_tags()); ?>
          <span><?php esc_html_e('Edit Profile', 'giftflowwp'); ?></span>
        </a>
      </li>
      <li>
        <a href="<?php echo esc_url( giftflowwp_donor_account_page_url('bookmarks') ); ?>" class="giftflowwp-dashboard-action">
          <?php echo wp_kses(giftflowwp_svg_icon('bookmark'), giftflowwp_allowed_svg_tags()); ?>
          <span><?php esc_html_e('Bookmarks', 'giftflowwp'); ?></span>
        </a>
      </li>
    </ul>
  </div>
  <div class="giftflowwp-donor-account-dashboard__message">
    <p>
      <?php esc_html_e('Stay tuned for new features and updates to your donor account. We appreciate your ongoing commitment!', 'giftflowwp'); ?>
    </p>
  </div>
</div>