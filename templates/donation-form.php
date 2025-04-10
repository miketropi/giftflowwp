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

// icons
$icons = array(
    'info' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
    'mail' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
    'eye-off' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>',
    'loop' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-repeat-icon lucide-repeat"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>',
    'switch' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-toggle-left-icon lucide-toggle-left"><rect width="20" height="12" x="2" y="6" rx="6" ry="6"/><circle cx="8" cy="12" r="2"/></svg>',
    'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>',
    'credit-card' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
    'bank' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>',
    'paypal' => '<svg width="20px" height="20px" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M33.0312 28C39 28 43 25.5 43 20C43 14.5 39 12 33.0312 12H22L17 43H26L28 28H33.0312Z" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/> <path d="M18 36H10L15 5H26.0312C32 5 36 7.5 36 13C36 18.5 32 21 26.0312 21H21" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
);
?>
<form class="giftflowwp-donation-form" id="giftflowwp-donation-form" data-campaign-id="<?php echo esc_attr($campaign_id); ?>">
    <div class="giftflowwp-donation-form-header">
        <!-- 2 columns, left is thumbnail, right is title and description -->
        <div class="giftflowwp-donation-form-header-left">
            <img src="<?php echo get_the_post_thumbnail_url($campaign_id, 'thumbnail'); ?>" alt="<?php echo get_the_title($campaign_id); ?>">
        </div>
        <div class="giftflowwp-donation-form-header-right">
            <h4><?php _e('Donate to', 'giftflowwp'); ?>: "<u><?php echo get_the_title($campaign_id); ?></u>"</h4>
            <p>
                <!-- render message template: $100 raised from $1000 total -->
                <?php printf(__('%s raised from %s total', 'giftflowwp'), giftflowwp_render_currency_formatted_amount($raised_amount), giftflowwp_render_currency_formatted_amount($goal_amount)); ?>
            </p>
        </div>
    </div>

    <!-- Step Navigation -->
    <div class="giftflowwp-donation-form-steps-nav">
        <div class="giftflowwp-donation-form-step-nav-item active" data-step="1">
            <span class="giftflowwp-donation-form-step-nav-number">1</span>
            <span class="giftflowwp-donation-form-step-nav-title"><?php _e('Donation Information', 'giftflowwp'); ?></span>
        </div>
        <span class="giftflowwp-donation-form-steps-nav-separator"></span>
        <div class="giftflowwp-donation-form-step-nav-item" data-step="2">
            <span class="giftflowwp-donation-form-step-nav-number">2</span>
            <span class="giftflowwp-donation-form-step-nav-title"><?php _e('Payment Method', 'giftflowwp'); ?></span>
        </div>
    </div>

    <!-- Step 1: Donation Information -->
    <div class="giftflowwp-donation-form-step giftflowwp-donation-form-step-1 active">
        <div class="giftflowwp-donation-form-body">
            <!-- input amount -->
            <div class="giftflowwp-donation-form-input-amount">
                <!-- Recurring donation options -->
                
                <!-- add message allow user to select once or monthly -->
                <label for="giftflowwp-donation-form-recurring-donation">
                    <?php echo $icons['switch']; ?>
                    <?php _e('Select type of donation, one-time or monthly', 'giftflowwp'); ?>
                </label>

                <div class="giftflowwp-donation-form-recurring-options">
                    <div class="giftflowwp-donation-form-recurring-option">
                        <input type="radio" id="donation-type-once" name="donation_type" value="once" checked>
                        <label for="donation-type-once">
                            <span class="giftflowwp-donation-form-recurring-option-title">
                                <?php // echo $icons['star']; ?>
                                <?php _e('One-time donation', 'giftflowwp'); ?>
                            </span>
                            <span class="giftflowwp-donation-form-recurring-option-description"><?php _e('Make a single donation', 'giftflowwp'); ?></span>
                        </label>
                    </div>
                    <div class="giftflowwp-donation-form-recurring-option">
                        <input type="radio" id="donation-type-monthly" name="donation_type" value="monthly">
                        <label for="donation-type-monthly">
                            <span class="giftflowwp-donation-form-recurring-option-title">
                                <?php echo $icons['loop']; ?>
                                <?php _e('Monthly donation', 'giftflowwp'); ?>
                            </span>
                            <span class="giftflowwp-donation-form-recurring-option-description"><?php _e('Make a recurring monthly donation', 'giftflowwp'); ?></span>
                        </label>
                    </div>
                </div>

                <label for="giftflowwp-donation-form-input-amount"><?php _e('Amount', 'giftflowwp'); ?></label>
                <div class="giftflowwp-donation-form-input-amount-input-wrapper">
                  <span class="__currency-symbol">
                    <?php echo giftflowwp_get_global_currency_symbol(); ?>
                  </span>
                  <input type="number" step="1" min="1" id="giftflowwp-donation-form-input-amount" name="giftflowwp-donation-form-input-amount" placeholder="<?php _e('Enter amount', 'giftflowwp'); ?>">
                  
                </div> 

                <p class="giftflowwp-donation-form-input-description">
                    <?php echo $icons['info']; ?>
                    <?php _e('Select an amount or enter your own', 'giftflowwp'); ?>
                </p>
                
                <!-- preset donation amounts -->
                <div class="giftflowwp-donation-form-preset-amounts">
                    <?php foreach ($preset_donation_amounts as $preset_donation_amount) : ?>
                        <button type="button" class="giftflowwp-donation-form-preset-amount" data-amount="<?php echo $preset_donation_amount['amount']; ?>"><?php echo giftflowwp_render_currency_formatted_amount($preset_donation_amount['amount']); ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- user info -->
            <div class="giftflowwp-donation-form-user-info">
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
                      <?php echo $icons['mail']; ?>
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
                      <?php echo $icons['eye-off']; ?>
                      <?php _e('Anonymous', 'giftflowwp'); ?>
                    </label>
                    <input type="checkbox" id="giftflowwp-donation-form-user-info-anonymous" name="giftflowwp-donation-form-user-info-anonymous">
                </div>
            </div>

            <!-- next step button -->
            <button class="giftflowwp-donation-form-next-step" type="button"><?php _e('Continue to Payment', 'giftflowwp'); ?></button>
        </div>
    </div>

    <!-- Step 2: Payment Method -->
    <div class="giftflowwp-donation-form-step giftflowwp-donation-form-step-2">
        <div class="giftflowwp-donation-form-body">
            <div class="giftflowwp-donation-form-step-2-content">
                <!-- Payment Methods -->
                <div class="giftflowwp-donation-form-payment-section">
                    <h3><?php _e('Select Payment Method', 'giftflowwp'); ?></h3>
                    
                    <div class="giftflowwp-donation-form-payment-methods">
                        <div class="giftflowwp-donation-form-payment-method">
                            <input type="radio" id="payment-method-stripe" name="payment_method" value="stripe" checked>
                            <label for="payment-method-stripe">
                                <div class="payment-method-icon">
                                    <?php echo $icons['credit-card']; ?>
                                </div>
                                <div class="payment-method-info">
                                    <span class="payment-method-title"><?php _e('Credit Card', 'giftflowwp'); ?></span>
                                    <span class="payment-method-description"><?php _e('Pay securely with your credit card', 'giftflowwp'); ?></span>
                                </div>
                            </label>
                        </div>

                        <div class="giftflowwp-donation-form-payment-method">
                            <input type="radio" id="payment-method-paypal" name="payment_method" value="paypal">
                            <label for="payment-method-paypal">
                                <div class="payment-method-icon">
                                    <?php echo $icons['paypal']; ?>
                                </div>
                                <div class="payment-method-info">
                                    <span class="payment-method-title"><?php _e('PayPal', 'giftflowwp'); ?></span>
                                    <span class="payment-method-description"><?php _e('Pay with your PayPal account', 'giftflowwp'); ?></span>
                                </div>
                            </label>
                        </div>

                        <div class="giftflowwp-donation-form-payment-method">
                            <input type="radio" id="payment-method-bank-transfer" name="payment_method" value="bank-transfer">
                            <label for="payment-method-bank-transfer">
                                <div class="payment-method-icon">
                                    <?php echo $icons['bank']; ?>
                                </div>
                                <div class="payment-method-info">
                                    <span class="payment-method-title"><?php _e('Bank Transfer', 'giftflowwp'); ?></span>
                                    <span class="payment-method-description"><?php _e('Direct bank transfer', 'giftflowwp'); ?></span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Stripe payment form (hidden by default) -->
                    <div class="giftflowwp-donation-form-stripe-payment" id="stripe-payment-form">
                        <div id="card-element"></div>
                        <div id="card-errors" role="alert"></div>
                    </div>
                </div>

                <!-- Donation Summary -->
                <div class="giftflowwp-donation-form-summary-section">
                    <h3><?php _e('Donation Summary', 'giftflowwp'); ?></h3>
                    <div class="giftflowwp-donation-form-summary">
                        <div class="giftflowwp-donation-form-summary-item">
                            <span class="giftflowwp-donation-form-summary-label"><?php _e('Amount', 'giftflowwp'); ?></span>
                            <span class="giftflowwp-donation-form-summary-value" id="summary-amount"><?php echo giftflowwp_render_currency_formatted_amount(0); ?></span>
                        </div>
                        <div class="giftflowwp-donation-form-summary-item">
                            <span class="giftflowwp-donation-form-summary-label"><?php _e('Type', 'giftflowwp'); ?></span>
                            <span class="giftflowwp-donation-form-summary-value" id="summary-type"><?php _e('One-time', 'giftflowwp'); ?></span>
                        </div>
                        <div class="giftflowwp-donation-form-summary-item">
                            <span class="giftflowwp-donation-form-summary-label"><?php _e('Donor', 'giftflowwp'); ?></span>
                            <span class="giftflowwp-donation-form-summary-value" id="summary-donor">-</span>
                        </div>
                        <div class="giftflowwp-donation-form-summary-item">
                            <span class="giftflowwp-donation-form-summary-label"><?php _e('Campaign', 'giftflowwp'); ?></span>
                            <span class="giftflowwp-donation-form-summary-value"><?php echo get_the_title($campaign_id); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- back and submit buttons -->
            <div class="giftflowwp-donation-form-step-buttons">
                <button class="giftflowwp-donation-form-prev-step" type="button"><?php _e('Back', 'giftflowwp'); ?></button>
                <button class="giftflowwp-donation-form-submit" type="submit"><?php _e('Complete Donation', 'giftflowwp'); ?></button>
            </div>
        </div>
    </div>
</form>