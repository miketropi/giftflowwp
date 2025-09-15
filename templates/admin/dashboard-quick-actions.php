<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-actions">
  <div class="giftflowwp-widget-header">
      <h3><?php _e('Quick Actions', 'giftflowwp'); ?></h3>
  </div>
  <div class="giftflowwp-widget-content">
    <div class="giftflowwp-quick-actions">
      <div class="giftflowwp-action-buttons">
        <a href="<?php echo esc_url(admin_url('admin.php?page=giftflowwp-settings')); ?>" class="button">
          <span class="dashicons dashicons-admin-settings"></span>
          <?php _e('All Settings', 'giftflowwp'); ?>
        </a>
        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=campaign')); ?>" class="button">
          <span class="dashicons dashicons-plus-alt"></span>
          <?php _e('Create New Campaign', 'giftflowwp'); ?>
        </a>
        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=donation')); ?>" class="button">
          <span class="dashicons dashicons-money-alt"></span>
          <?php _e('Add Manual Donation', 'giftflowwp'); ?>
        </a>

        <button type="button" class="button giftflowwp-export-btn">
          <span class="dashicons dashicons-download"></span>
          <?php _e('Export Donations', 'giftflowwp'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Export Modal -->
<div id="giftflowwp-export-modal" class="giftflowwp-modal" style="display: none;">
  <div class="giftflowwp-modal-overlay"></div>
  <div class="giftflowwp-modal-content">
    <div class="giftflowwp-modal-header">
      <h3><?php _e('Export Donations', 'giftflowwp'); ?></h3>
      <button type="button" class="giftflowwp-modal-close">&times;</button>
    </div>
    <div class="giftflowwp-modal-body">
      <form id="giftflowwp-export-form">

        <div class="giftflowwp-form-group">
          <label for="giftflowwp-export-format"><?php _e('Format', 'giftflowwp'); ?></label>
          <select id="giftflowwp-export-format" name="format" class="giftflowwp-form-control">
            <option value="csv"><?php _e('CSV', 'giftflowwp'); ?></option>
          </select>
        </div>
        
         <div class="giftflowwp-form-group">
           <label for="giftflowwp-export-campaign"><?php _e('Campaign', 'giftflowwp'); ?></label>
           <select id="giftflowwp-export-campaign" name="campaign_id" class="giftflowwp-form-control giftflowwp-select2">
             <option value=""><?php _e('All Campaigns', 'giftflowwp'); ?></option>
             <?php
             $campaigns = get_posts(array(
                 'post_type' => 'campaign',
                 'post_status' => 'publish',
                 'numberposts' => -1,
                 'orderby' => 'title',
                 'order' => 'ASC'
             ));
             foreach ($campaigns as $campaign) {
                 echo '<option value="' . esc_attr($campaign->ID) . '">' . esc_html($campaign->post_title) . '</option>';
             }
             ?>
           </select>
         </div>
        
        <div class="giftflowwp-form-group">
          <label for="giftflowwp-export-period"><?php _e('Time Period', 'giftflowwp'); ?></label>
          <select id="giftflowwp-export-period" name="period" class="giftflowwp-form-control">
              <option value="all"><?php _e('All Time', 'giftflowwp'); ?></option>
              <option value="year"><?php _e('This Year', 'giftflowwp'); ?></option>
              <option value="month"><?php _e('This Month', 'giftflowwp'); ?></option>
              <option value="week"><?php _e('This Week', 'giftflowwp'); ?></option>
              <option value="custom"><?php _e('Custom Date Range', 'giftflowwp'); ?></option>
          </select>
        </div>
        
        <div class="giftflowwp-form-group" id="giftflowwp-custom-dates" style="display: none;">
          <div class="">
            <label for="giftflowwp-export-date-from"><?php _e('From Date', 'giftflowwp'); ?></label>
            <input type="date" id="giftflowwp-export-date-from" name="date_from" class="giftflowwp-form-control">
          </div>
          <div class="">
            <label for="giftflowwp-export-date-to"><?php _e('To Date', 'giftflowwp'); ?></label>
            <input type="date" id="giftflowwp-export-date-to" name="date_to" class="giftflowwp-form-control">
          </div>
        </div>
        
        <div class="giftflowwp-form-group">
          <label>
              <input type="checkbox" id="giftflowwp-export-include-donor" name="include_donor" checked>
              <?php _e('Include Donor Information', 'giftflowwp'); ?>
          </label>
        </div>
        
        <div class="giftflowwp-form-group">
          <label>
            <input type="checkbox" id="giftflowwp-export-include-campaign" name="include_campaign" checked>
            <?php _e('Include Campaign Information', 'giftflowwp'); ?>
          </label>
        </div>
      </form>
    </div>
    <div class="giftflowwp-modal-footer">
      <button type="button" class="button button-secondary giftflowwp-modal-close">
        <?php _e('Cancel', 'giftflowwp'); ?>
      </button>
      <button type="button" class="button button-primary" id="giftflowwp-export-submit">
        <span class="dashicons dashicons-download"></span>
        <?php _e('Export', 'giftflowwp'); ?>
      </button>
    </div>
  </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.giftflowwp-export-btn').on('click', function() {
        $('#giftflowwp-export-modal').fadeIn(300, function() {
          if (!$('#giftflowwp-export-campaign').hasClass('select2-hidden-accessible')) {
            $('#giftflowwp-export-campaign').select2({
                placeholder: '<?php _e('Select a campaign or leave blank for all', 'giftflowwp'); ?>',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#giftflowwp-export-modal')
            });
            }
        });
    });
    
    // Close modal
    $('.giftflowwp-modal-close, .giftflowwp-modal-overlay').on('click', function() {
        $('#giftflowwp-export-modal').fadeOut(300, function() {
            if ($('#giftflowwp-export-campaign').hasClass('select2-hidden-accessible')) {
              $('#giftflowwp-export-campaign').select2('destroy');
            }
        });
    });
    
    // Show/hide custom date fields
    $('#giftflowwp-export-period').on('change', function() {
      if ($(this).val() === 'custom') {
        $('#giftflowwp-custom-dates').slideDown(200);
      } else {
        $('#giftflowwp-custom-dates').slideUp(200);
      }
    });
    
    // Handle export form submission
    $('#giftflowwp-export-submit').on('click', function() {
        var $form = $('#giftflowwp-export-form');
        var formData = $form.serialize();
        
        // Show loading state
        var $btn = $(this);
        var originalText = $btn.html();
        $btn.html('<span class="dashicons dashicons-update"></span> <?php _e('Exporting...', 'giftflowwp'); ?>').prop('disabled', true);
        
        // Create form for download
        var $downloadForm = $('<form method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>" style="display: none;">');
        $downloadForm.append('<input type="hidden" name="action" value="giftflowwp_export_donations">');
        $downloadForm.append('<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('giftflowwp_export_nonce'); ?>">');
        
        // Add form data
        var formArray = $form.serializeArray();
        $.each(formArray, function(i, field) {
            $downloadForm.append('<input type="hidden" name="' + field.name + '" value="' + field.value + '">');
        });
        
        $('body').append($downloadForm);
        $downloadForm.submit();
        $downloadForm.remove();
        
        // Reset button and close modal
        setTimeout(function() {
            $btn.html(originalText).prop('disabled', false);
            $('#giftflowwp-export-modal').fadeOut(300, function() {
                // Destroy Select2 when modal closes
                if ($('#giftflowwp-export-campaign').hasClass('select2-hidden-accessible')) {
                    $('#giftflowwp-export-campaign').select2('destroy');
                }
            });
        }, 1000);
    });
    
    // Prevent modal close when clicking inside modal content
    $('.giftflowwp-modal-content').on('click', function(e) {
        e.stopPropagation();
    });
});
</script>