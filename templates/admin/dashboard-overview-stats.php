<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-overview">
    <div class="giftflowwp-widget-header">
        <h3><?php _e('Overview', 'giftflowwp'); ?></h3>
    </div>
    <div class="giftflowwp-widget-content">
        <div class="giftflowwp-overview-stats">
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total donations:', 'giftflowwp'); ?></strong>
                <span><?php echo giftflowwp_render_currency_formatted_amount($total_donations); ?></span>
            </div>
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total donors:', 'giftflowwp'); ?></strong>
                <span><?php echo esc_html($total_donors); ?></span>
            </div>
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total campaigns:', 'giftflowwp'); ?></strong>
                <span><?php echo esc_html($total_campaigns); ?></span>
            </div>
        </div>
    </div>
</div>