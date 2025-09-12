<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-insights">
  <div class="giftflowwp-widget-header">
      <h3><?php _e('Donor Insights', 'giftflowwp'); ?></h3>
  </div>
  <div class="giftflowwp-widget-content">
    <div class="giftflowwp-insights-list">
      <div class="giftflowwp-insight-item">
          <strong><?php _e('Top donor this month:', 'giftflowwp'); ?></strong>
          <span><?php echo esc_html($top_donor['name']); ?> (<?php echo giftflowwp_render_currency_formatted_amount($top_donor['amount']); ?>)</span>
      </div>
      <div class="giftflowwp-insight-item">
          <strong><?php _e('Recurring donors:', 'giftflowwp'); ?></strong>
          <span><?php echo esc_html($recurring_donors['count']); ?> (<?php echo esc_html($recurring_donors['percentage']); ?>%)</span>
      </div>
      <div class="giftflowwp-insight-item">
          <strong><?php _e('New donors:', 'giftflowwp'); ?></strong>
          <span><?php echo esc_html($new_donors); ?> (<?php _e('last 7 days', 'giftflowwp'); ?>)</span>
      </div>
    </div>
  </div>
</div>