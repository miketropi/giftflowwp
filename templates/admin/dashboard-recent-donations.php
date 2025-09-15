<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-donations">
  <div class="giftflowwp-widget-header">
      <h3><?php _e('Recent Donations', 'giftflowwp'); ?></h3>
  </div>
  <div class="giftflowwp-widget-content">
    <?php 
    if (empty($donations)) {
      echo '<p>' . __('No recent donations found.', 'giftflowwp') . '</p>';

    } else { ?>

      <div class="giftflowwp-donations-list">
          <?php foreach ($donations as $donation): ?>
            <div class="giftflowwp-donation-item">
              <div class="giftflowwp-donation-info">
                <div class="giftflowwp-donation-donor">
                  <?php echo esc_html($donation['donor_name']); ?>
                </div>
                <div class="giftflowwp-donation-amount">
                  <?php echo giftflowwp_render_currency_formatted_amount($donation['amount']); ?>
                </div>
              </div>
              <div class="giftflowwp-donation-date">
                <?php echo esc_html($donation['date']); ?>
              </div>
              <div class="giftflowwp-donation-campaign">
                <a href="<?php echo esc_url($donation['campaign_link']); ?>"><?php echo esc_html($donation['campaign_title']); ?></a>
              </div>
            </div>
          <?php endforeach; ?>
      </div>
    
    <?php } ?>
  </div>
</div>