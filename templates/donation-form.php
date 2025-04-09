<?php
/**
 * Donation form template 
 *
 * @package GiftFlowWP
 */

// get the campaign id
$campaign_id = get_the_ID();

// preset donation amounts
$preset_donation_amounts = giftflowwp_get_preset_donation_amounts_by_campaign($campaign_id);

// raised amount
$raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);

// goal amount
$goal_amount = giftflowwp_get_campaign_goal_amount($campaign_id);
?>
<form class="giftflowwp-donation-form">
    <div class="giftflowwp-donation-form-header">
        <!-- 2 columns, left is thumbnail, right is title and description -->
        <div class="giftflowwp-donation-form-header-left">
            <img src="<?php echo get_the_post_thumbnail_url($campaign_id, 'thumbnail'); ?>" alt="<?php echo get_the_title($campaign_id); ?>">
        </div>
        <div class="giftflowwp-donation-form-header-right">
            <h4><?php _e('Donate to', 'giftflowwp'); ?> <?php echo get_the_title($campaign_id); ?></h4>
            <p>
                <!-- render message template: $100 raised from $1000 total -->
                <?php printf(__('%s raised from %s total', 'giftflowwp'), giftflowwp_render_currency_formatted_amount($raised_amount), giftflowwp_render_currency_formatted_amount($goal_amount)); ?>
            </p>
        </div>
    </div>
    <div class="giftflowwp-donation-form-body">
        <!-- input amount -->
        <div class="giftflowwp-donation-form-input-amount">
            <label for="giftflowwp-donation-form-input-amount"><?php _e('Amount', 'giftflowwp'); ?></label>
            <div class="giftflowwp-donation-form-input-amount-input-wrapper">
              <span class="__currency-symbol">
                <?php echo giftflowwp_get_global_currency_symbol(); ?>
              </span>
              <input type="number" id="giftflowwp-donation-form-input-amount" name="giftflowwp-donation-form-input-amount" placeholder="<?php _e('Enter amount', 'giftflowwp'); ?>">
              <p class="giftflowwp-donation-form-input-description">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <?php _e('Select an amount or enter your own', 'giftflowwp'); ?>
              </p>
            </div> 
            
            <!-- preset donation amounts -->
            <div class="giftflowwp-donation-form-preset-amounts">
                <!-- Show message: Select an amount or enter your own -->
                
                <?php foreach ($preset_donation_amounts as $preset_donation_amount) : ?>
                    <button type="button" class="giftflowwp-donation-form-preset-amount" data-amount="<?php echo $preset_donation_amount['amount']; ?>"><?php echo giftflowwp_render_currency_formatted_amount($preset_donation_amount['amount']); ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- user info -->
        <div class="giftflowwp-donation-form-user-info">
            <!-- first name, last name, email, anonymous checkbox, message, (first name, last name grouped 2 columns) -->
            <div class="giftflowwp-donation-form-user-info-first-name-last-name">
              <div class="giftflowwp-donation-form-user-info-first-name">
                  <label for="giftflowwp-donation-form-user-info-first-name"><?php _e('First name', 'giftflowwp'); ?></label>
                  <input type="text" id="giftflowwp-donation-form-user-info-first-name" name="giftflowwp-donation-form-user-info-first-name" placeholder="<?php _e('John', 'giftflowwp'); ?>">
              </div>
              <div class="giftflowwp-donation-form-user-info-last-name">
                  <label for="giftflowwp-donation-form-user-info-last-name"><?php _e('Last name', 'giftflowwp'); ?></label>
                  <input type="text" id="giftflowwp-donation-form-user-info-last-name" name="giftflowwp-donation-form-user-info-last-name" placeholder="<?php _e('Doe', 'giftflowwp'); ?>">
              </div>
            </div>

            <div class="giftflowwp-donation-form-user-info-email">
                <label for="giftflowwp-donation-form-user-info-email">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                  <?php _e('Email', 'giftflowwp'); ?>
                </label>
                <input type="email" id="giftflowwp-donation-form-user-info-email" name="giftflowwp-donation-form-user-info-email" placeholder="<?php _e('john.doe@example.com', 'giftflowwp'); ?>">
            </div>
            <div class="giftflowwp-donation-form-user-info-message">
                <label for="giftflowwp-donation-form-user-info-message">
                  <?php _e('Message', 'giftflowwp'); ?>
                </label>
                <textarea id="giftflowwp-donation-form-user-info-message" name="giftflowwp-donation-form-user-info-message" placeholder="<?php _e('I would like to say...', 'giftflowwp'); ?>"></textarea>
            </div>
            <div class="giftflowwp-donation-form-user-info-anonymous">
                <label for="giftflowwp-donation-form-user-info-anonymous">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                  <?php _e('Anonymous', 'giftflowwp'); ?>
                </label>
                <input type="checkbox" id="giftflowwp-donation-form-user-info-anonymous" name="giftflowwp-donation-form-user-info-anonymous">
            </div>
        </div>

        <!-- submit button -->
        <button class="giftflowwp-donation-form-submit" type="submit"><?php _e('Donate Campaign', 'giftflowwp'); ?></button>
    </div>
</form>