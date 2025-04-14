<?php
/**
 * Donation form template 
 * 
 * @package GiftFlowWP
 */

/* 
Donation form template 2 steps:
    Step 1:
        - header 
            - thumbnail
            - campaign title
            - raised / goal
        - Amount input
            - custom amount
            - preset amounts
        - User info
            - name
            - email
            - phone
            - message
        - Anonymous donation checkbox
        - Next step button
    Step 2:
        - Select payment method
            - credit card
            - paypal
            - bank transfer
        - Donation summary
            - amount
            - email
            - campaign title
        - Back button
        - Donate button
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
    'eye-off' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>',
    'loop' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-repeat-icon lucide-repeat"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>',
    'switch' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-toggle-left-icon lucide-toggle-left"><rect width="20" height="12" x="2" y="6" rx="6" ry="6"/><circle cx="8" cy="12" r="2"/></svg>',
    'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>',
    'credit-card' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
    'bank' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>',
    'paypal' => '<svg width="20px" height="20px" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M33.0312 28C39 28 43 25.5 43 20C43 14.5 39 12 33.0312 12H22L17 43H26L28 28H33.0312Z" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/> <path d="M18 36H10L15 5H26.0312C32 5 36 7.5 36 13C36 18.5 32 21 26.0312 21H21" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
    'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
    'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>',
    'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
    'shield' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>',
    'phone' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
    'message' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
		'shield-check' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>',
		'error' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
		'next' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>',
		'prev' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>'
);

// Get default donation amount (first preset amount or 10)
$default_amount = !empty($preset_donation_amounts) ? $preset_donation_amounts[0]['amount'] : 10;

// Get campaign title
$campaign_title = get_the_title($campaign_id);

// Get currency symbol
$currency_symbol = giftflowwp_get_global_currency_symbol();

// Get currency format template
$currency_format_template = giftflowwp_get_currency_js_format_template();

// Payment methods
$payment_methods = array(
	'stripe' => array(
		'name' => 'stripe',
		'label' => __('Credit Card', 'giftflowwp'),
		'icon' => $icons['credit-card'],
		'callback_html' => 'giftflowwp_stripe_payment_method_callback',
	),
	'paypal' => array(
		'name' => 'paypal',
		'label' => __('PayPal', 'giftflowwp'),
		'icon' => $icons['paypal'],
		// 'callback_html' => 'giftflowwp_paypal_payment_method_callback',
	),
	'bank' => array(
		'name' => 'bank',
		'label' => __('Bank Transfer', 'giftflowwp'),
		'icon' => $icons['bank'],
		// 'callback_html' => 'giftflowwp_bank_payment_method_callback',
	),
);
?>

<form class="donation-form" id="donation-form">
    <!-- Campaign Header -->
    <div class="donation-form__header">
        <div class="donation-form__campaign">
            <div class="donation-form__campaign-thumbnail">
                <?php echo get_the_post_thumbnail($campaign_id, 'thumbnail'); ?>
            </div>
            <div class="donation-form__campaign-info">
                <h4 class="donation-form__campaign-title"><?php _e('Donate to', 'giftflowwp'); ?>: <?php echo esc_html($campaign_title); ?></h4>
                <div class="donation-form__campaign-progress">
                    <?php echo sprintf(__('%s raised from %s goal', 'giftflowwp'), giftflowwp_render_currency_formatted_amount($raised_amount), giftflowwp_render_currency_formatted_amount($goal_amount)); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Steps -->
    <div class="donation-form__steps">
        <!-- Step Navigation -->
        <nav class="donation-form__step-nav">
            <ol class="donation-form__step-list">
                <li class="donation-form__step-item nav-step-1">
                    <a href="#donation-information" class="donation-form__step-link is-active" data-step="donation-information">
                        <span class="donation-form__step-number">1</span>
                        <span class="donation-form__step-text"><?php _e('Donation Information', 'giftflowwp'); ?></span>
                    </a>
                </li>
                <li class="donation-form__step-separator"></li>
                <li class="donation-form__step-item nav-step-2">
                    <a href="#payment-method" class="donation-form__step-link" data-step="payment-method">
                        <span class="donation-form__step-number">2</span>
                        <span class="donation-form__step-text"><?php _e('Payment Method', 'giftflowwp'); ?></span>
                    </a>
                </li>
            </ol>
        </nav>

        <!-- Step Content -->
        <div class="donation-form__step-content">
            <!-- Step 1: Donation Information -->
            <section id="donation-information" class="donation-form__step-panel step-1 is-active">
                <div class="donation-form__step-panel-content">
                    <!-- Donation Type -->
                    <fieldset class="donation-form__fieldset">
                        <legend class="donation-form__legend"><?php _e('Select donation type, one-time or monthly', 'giftflowwp'); ?></legend>
                        <div class="donation-form__radio-group donation-form__radio-group--donation-type">
                            <input type="radio" name="donation_type" value="once" checked id="donation_type_once">
                            <label class="donation-form__radio-label" for="donation_type_once">
                                
                                <span class="donation-form__radio-content">
                                    <span class="donation-form__radio-title"><?php _e('One-time Donation', 'giftflowwp'); ?></span>
                                    <span class="donation-form__radio-description"><?php _e('Make a single donation', 'giftflowwp'); ?></span>
                                </span>
                            </label>

                            <input type="radio" name="donation_type" value="monthly" id="donation_type_monthly">
                            <label class="donation-form__radio-label" for="donation_type_monthly">
                                
                                <span class="donation-form__radio-content">
                                    <span class="donation-form__radio-title">
                                        <?php echo $icons['loop']; ?>
                                        <?php _e('Monthly Donation', 'giftflowwp'); ?>
                                    </span>
                                    <span class="donation-form__radio-description"><?php _e('Make a recurring monthly donation', 'giftflowwp'); ?></span>
                                </span>
                            </label>
                        </div>
                    </fieldset>

                    <!-- Donation Amount -->
                    <fieldset class="donation-form__fieldset">
                        <legend class="donation-form__legend"><?php _e('Donation Amount', 'giftflowwp'); ?></legend>
                        <div class="donation-form__amount">
                            <div class="donation-form__amount-input">
                                <span class="donation-form__currency"><?php echo $currency_symbol; ?></span>
                                <input type="number" name="donation_amount" value="<?php echo esc_attr($default_amount); ?>" min="1" step="1" required data-validate="required">
                            </div>
                            <div class="donation-form__preset-amounts">
                                <?php foreach ($preset_donation_amounts as $amount) : ?>
                                    <button 
																			type="button" 
																			class="donation-form__preset-amount" 
																			data-amount="<?php echo esc_attr($amount['amount']); ?>">
                                        <?php echo giftflowwp_render_currency_formatted_amount($amount['amount']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Donor Information -->
                    <fieldset class="donation-form__fieldset">
                        <legend class="donation-form__legend"><?php _e('Your Information', 'giftflowwp'); ?></legend>
                        <div class="donation-form__fields">
                            <div class="donation-form__field">
                                <label for="donor_name"><?php _e('Full Name', 'giftflowwp'); ?></label>
                                <input type="text" id="donor_name" name="donor_name" required data-validate="required">

																<?php // error message ?>
																<div class="donation-form__field-error">
																	<?php echo $icons['error']; ?>
																	<?php _e('This field is required, please enter your name', 'giftflowwp'); ?>
																</div> 
                            </div>
                            <div class="donation-form__field">
                                <label for="donor_email"><?php _e('Email Address', 'giftflowwp'); ?></label>
                                <input type="email" id="donor_email" name="donor_email" required data-validate="email">

																<?php // error message ?>
																<div class="donation-form__field-error">
																	<?php echo $icons['error']; ?>
																	<?php _e('This field is required, please enter your email', 'giftflowwp'); ?>
																</div> 
                            </div>
                            <div class="donation-form__field">
                                <label for="donor_message"><?php _e('Message (Optional)', 'giftflowwp'); ?></label>
                                <textarea id="donor_message" name="donor_message"></textarea>
                            </div>
                            <div class="donation-form__field">
                                <label class="donation-form__checkbox-label">
                                    <input type="checkbox" name="anonymous_donation">
                                    <span><?php _e('Make this donation anonymous', 'giftflowwp'); ?></span>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="donation-form__step-actions">
                    <button type="button" class="donation-form__button donation-form__button--next" data-next-step="payment-method">
                        <?php _e('Continue to Payment', 'giftflowwp'); ?>
												<?php echo $icons['next']; ?>
                    </button>
                </div>
            </section>

            <!-- Step 2: Payment Method -->
            <section id="payment-method" class="donation-form__step-panel step-2">
                <div class="donation-form__step-panel-content">
                    <!-- Payment Methods -->
                    <fieldset class="donation-form__fieldset">
                        <legend class="donation-form__legend"><?php _e('Select Payment Method', 'giftflowwp'); ?></legend>
                        <div class="donation-form__payment-methods">
														<?php foreach ($payment_methods as $method) : 
															// if callback html is set, show the callback html
															if (isset($method['callback_html'])) {
																// call the callback html and send all args
																echo call_user_func($method['callback_html'], $method);
																continue;
															}
															?>
															<label class="donation-form__payment-method">
																<input type="radio" checked name="payment_method" value="<?php echo esc_attr($method['name']); ?>">
																<span class="donation-form__payment-method-content">
																	<?php echo $method['icon']; ?>
																	<span class="donation-form__payment-method-title"><?php echo esc_html($method['label']); ?></span>
																</span>
															</label>
														<?php endforeach; ?>


                            <!-- <label class="donation-form__payment-method">
                                <input type="radio" checked name="payment_method" value="credit_card" required>
                                <span class="donation-form__payment-method-content">
                                    <?php echo $icons['credit-card']; ?>
                                    <span class="donation-form__payment-method-title"><?php _e('Credit Card', 'giftflowwp'); ?></span>
                                </span>
                            </label>
                            <div class="donation-form__payment-method-description donation-form__payment-method-description--stripe">
                                <?php _e('We use Stripe to process payments. Your payment information is encrypted and never stored on our servers.', 'giftflowwp'); ?>
                                <div id="STRIPE-CARD-ELEMENT"></div>
                            </div>

                            <label class="donation-form__payment-method">
                                <input type="radio" name="payment_method" value="paypal">
                                <span class="donation-form__payment-method-content">
                                    <?php echo $icons['paypal']; ?>
                                    <span class="donation-form__payment-method-title"><?php _e('PayPal', 'giftflowwp'); ?></span>
                                </span>
                            </label>
                            <label class="donation-form__payment-method">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <span class="donation-form__payment-method-content">
                                    <?php echo $icons['bank']; ?>
                                    <span class="donation-form__payment-method-title"><?php _e('Bank Transfer', 'giftflowwp'); ?></span>
                                </span>
                            </label> -->
                        </div>
                    </fieldset>

                    <!-- Donation Summary -->
                    <div class="donation-form__summary">
                        <h3 class="donation-form__summary-title"><?php _e('Donation Summary', 'giftflowwp'); ?></h3>
                        <dl class="donation-form__summary-list">
                            <div class="donation-form__summary-item">
                                <dt><?php _e('Amount', 'giftflowwp'); ?></dt>
                                <dd class="donation-form__summary-amount" data-output="donation_amount" data-format-template="<?php echo $currency_format_template; ?>"></dd>
                            </div>
                            <div class="donation-form__summary-item">
                                <dt><?php _e('Email', 'giftflowwp'); ?></dt>
                                <dd class="donation-form__summary-email" data-output="donor_email"></dd>
                            </div>
                            <div class="donation-form__summary-item">
                                <dt><?php _e('Campaign', 'giftflowwp'); ?></dt>
                                <dd><?php echo esc_html($campaign_title); ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <?php // notice scurity payment check ?>
                <div class="donation-form__security-notice">
                    <span class="donation-form__security-notice-icon"><?php echo $icons['shield-check']; ?></span>
                    <p><?php _e('We use methods of payment that are secure and trusted. Your payment information is encrypted and never stored on our servers.', 'giftflowwp'); ?></p>
                </div>

                <div class="donation-form__step-actions">
										<?php // hidden fields campaign id, wp nonce, form nonce ?>
										<input type="hidden" name="campaign_id" value="<?php echo esc_attr($campaign_id); ?>">
										<input type="hidden" name="wp_nonce" value="<?php echo wp_create_nonce('giftflowwp_donation_form'); ?>">

										<button type="button" class="donation-form__button donation-form__button--back" data-prev-step="donation-information">
											<?php echo $icons['prev']; ?>
											<?php _e('Back', 'giftflowwp'); ?>
                    </button>
                    <button type="submit" class="donation-form__button donation-form__button--submit">
											<?php _e('Complete Donation', 'giftflowwp'); ?>
											<?php echo $icons['next']; ?>
                    </button>
                </div>
            </section>
        </div>
    </div>
</form>