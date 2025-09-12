<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-dashboard-help">
  <h2><?php _e('GiftFlowWP Help', 'giftflowwp'); ?></h2>
  
  <div class="giftflowwp-help-section">
      <h3><?php _e('Getting Started', 'giftflowwp'); ?></h3>
      <p><?php _e('To get started with GiftFlowWP, follow these steps:', 'giftflowwp'); ?></p>
      <ol>
          <li><?php _e('Configure your basic settings in the Settings menu', 'giftflowwp'); ?></li>
          <li><?php _e('Create your first campaign', 'giftflowwp'); ?></li>
          <li><?php _e('Add the donation form shortcode to your pages or posts', 'giftflowwp'); ?></li>
      </ol>
  </div>
  
  <div class="giftflowwp-help-section">
      <h3><?php _e('Shortcodes', 'giftflowwp'); ?></h3>
      <p><?php _e('Use the following shortcodes to display donation forms on your site:', 'giftflowwp'); ?></p>
      <ul>
          <li><code>[giftflowwp_donation id="1"]</code> - <?php _e('Display a specific donation form by ID', 'giftflowwp'); ?></li>
          <li><code>[giftflowwp_campaigns]</code> - <?php _e('Display a list of all campaigns', 'giftflowwp'); ?></li>
          <li><code>[giftflowwp_donors]</code> - <?php _e('Display a list of recent donors', 'giftflowwp'); ?></li>
      </ul>
  </div>
  
  <div class="giftflowwp-help-section">
      <h3><?php _e('Documentation', 'giftflowwp'); ?></h3>
      <p><?php _e('For comprehensive documentation on using GiftFlowWP, including advanced features and customization options, please visit our documentation site.', 'giftflowwp'); ?></p>
      <p><a href="https://giftflowwp.com/docs/" class="button" target="_blank"><?php _e('View Documentation', 'giftflowwp'); ?></a></p>
  </div>
</div>