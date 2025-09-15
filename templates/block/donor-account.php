<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

$tabs = $tabs ?? [];
$current_user = $current_user ?? null;
$attributes = $attributes ?? [];
$active_tab = $active_tab ?? $tabs[0]['slug'];
?>

<div class="giftflowwp-donor-account" role="tablist" aria-label="<?php _e('Donor Account Navigation', 'giftflowwp'); ?>">
  <div class="giftflowwp-donor-account__tabs" role="tablist">
    <?php foreach ($tabs as $index => $tab) : ?>
      <a 
        href="<?php echo giftflowwp_donor_account_page_url($tab['slug']); ?>" 
        class="giftflowwp-donor-account__tab<?php echo $tab['slug'] === $active_tab ? ' active' : ''; ?>"
        role="tab"
        id="tab-<?php echo esc_attr($tab['slug']); ?>"
      >
        <span class="giftflowwp-donor-account__tab-icon" aria-hidden="true"><?php echo $tab['icon']; ?></span>
        <span class="giftflowwp-donor-account__tab-label"><?php echo esc_html($tab['label']); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
  
  <div class="giftflowwp-donor-account__content">
    <?php foreach ($tabs as $index => $tab) : 
      // if not active tab is continue 
  
      if ($tab['slug'] !== $active_tab) {
        continue;
      }
      ?>
      <div 
        class="giftflowwp-donor-account__tab-content" 
        role="tabpanel"
        id="<?php echo esc_attr($tab['slug']); ?>-panel"
        aria-labelledby="tab-<?php echo esc_attr($tab['slug']); ?>"
      >
        <?php 
        // Execute callback function if it exists
        if (is_callable($tab['callback'])) {
          call_user_func($tab['callback'], $current_user, $attributes);
          
        } elseif (function_exists($tab['callback'])) {
          call_user_func($tab['callback'], $current_user, $attributes);
          
        } else { 
          ?>
          <div class="donor-account-empty">
            <div class="empty-icon"><?php echo giftflowwp_svg_icon('folder-code'); ?></div>
            <div class="empty-message">
              <h4><?php echo esc_html($tab['label']); ?></h4>
              <p>
                <?php esc_html_e('This section is currently under development.', 'giftflowwp'); ?>
                <br>
                <?php esc_html_e('We\'re working hard to bring you new features and improvements. Please check back soon for updates!', 'giftflowwp'); ?>
              </p>
              <ul>
                <li><?php esc_html_e('Stay tuned for enhanced donor tools and insights.', 'giftflowwp'); ?></li>
                <li><?php esc_html_e('Your feedback is valuable—let us know what you\'d like to see!', 'giftflowwp'); ?></li>
                <li><?php esc_html_e('Thank you for supporting our mission.', 'giftflowwp'); ?></li>
              </ul>
            </div>
          </div> 
          <?php
        }
        ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>