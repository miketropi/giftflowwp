<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-campaigns">
  <div class="giftflowwp-widget-header">
    <h3><?php _e('Highlight Campaigns', 'giftflowwp'); ?></h3>
  </div>
  <div class="giftflowwp-widget-content">
    <?php
    if (empty($campaigns)) {
      echo '<p>' . __('No campaigns found.', 'giftflowwp') . '</p>';

    } else { ?>

      <div class="giftflowwp-campaigns-list">
          <?php foreach ($campaigns as $campaign): ?>
              <div class="giftflowwp-campaign-item">
                  <div class="giftflowwp-campaign-info">
                      <a href="<?php echo esc_url($campaign['link']); ?>">
                        <h4><?php echo esc_html($campaign['title']); ?></h4>
                      </a>
                      <div class="giftflowwp-campaign-progress">
                          <span class="giftflowwp-campaign-amount">
                              <?php echo giftflowwp_render_currency_formatted_amount($campaign['raised']); ?> / <?php echo giftflowwp_render_currency_formatted_amount($campaign['goal']); ?>
                          </span>
                          <span class="giftflowwp-campaign-percentage">(<?php echo esc_html($campaign['percentage']); ?>%)</span>
                      </div>
                      <div class="giftflowwp-progress-bar">
                          <div class="giftflowwp-progress-fill" style="width: <?php echo esc_attr($campaign['percentage']); ?>%"></div>
                      </div>
                  </div>
                  <div class="giftflowwp-campaign-actions">
                      <a href="<?php echo esc_url(get_edit_post_link($campaign['id'])); ?>" class="button button-small"><?php _e('Edit', 'giftflowwp'); ?></a>
                      <a href="<?php echo esc_url(get_permalink($campaign['id'])); ?>" class="button button-small" target="_blank"><?php _e('View', 'giftflowwp'); ?></a>
                  </div>
              </div>
          <?php endforeach; ?>
      </div>
      
    <?php } ?>
  </div>
</div>