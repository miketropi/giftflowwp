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
    thank you message:
    error message:
*/
?>

<form class="donation-form" id="donation-form-<?php echo esc_attr($campaign_id); ?>">
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
                            <?php // foreach donation types ?>
                            <?php foreach ($donation_types as $index => $donation_type) : 
                                $is_checked = $index === 0 ? 'checked' : '';
                                ?>
                                <label>
                                    <input 
                                        type="radio" 
                                        name="donation_type" 
                                        value="<?php echo esc_attr($donation_type['name']); ?>"   
                                        id="donation_type_<?php echo esc_attr($donation_type['name']); ?>" 
                                        <?php echo $is_checked; ?>>
                                    <div class="donation-form__radio-label" for="donation_type_<?php echo esc_attr($donation_type['name']); ?>">
                                        <span class="donation-form__radio-content">
                                            <span class="donation-form__radio-title">
                                                <?php echo $donation_type['icon']; ?>	
                                                <?php echo esc_html($donation_type['label']); ?>
                                            </span>
                                            <span class="donation-form__radio-description"><?php echo esc_html($donation_type['description']); ?></span>
                                        </span>
                                    </div>
                                </label>
                            <?php endforeach; ?>

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
                                <input type="text" id="donor_name" name="donor_name" value="<?php echo $user_fullname; ?>" required data-validate="required" <?php echo $user_info_readonly ? 'readonly' : ''; ?>>

                                <?php // error message ?>
                                <div class="donation-form__field-error">
                                    <?php echo giftflowwp_svg_icon('error'); ?>
                                    <?php _e('This field is required, please enter your name', 'giftflowwp'); ?>
                                </div> 
                            </div>
                            <div class="donation-form__field">
                                <label for="donor_email"><?php _e('Email Address', 'giftflowwp'); ?></label>
                                <input type="email" id="donor_email" name="donor_email" value="<?php echo $user_email; ?>" required data-validate="email" <?php echo $user_info_readonly ? 'readonly' : ''; ?>>

                                <?php // error message ?>
                                <div class="donation-form__field-error">
                                    <?php echo giftflowwp_svg_icon('error'); ?>
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
						<?php echo giftflowwp_svg_icon('next'); ?>
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
							<?php foreach ($gateways as $method) {
								echo $method->template_html();
							} ?>
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
                                <dd class="donation-form__summary-email" data-output="donor_email">
                                    <?php echo $user_email; ?>
                                </dd>
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
                    <span class="donation-form__security-notice-icon"><?php echo giftflowwp_svg_icon('shield-check'); ?></span>
                    <p><?php _e('We use methods of payment that are secure and trusted. Your payment information is encrypted and never stored on our servers.', 'giftflowwp'); ?></p>
                </div>

                <div class="donation-form__step-actions">
                    <?php // hidden fields campaign id, wp nonce, form nonce, recurring interval ?>
                    <input type="hidden" name="campaign_id" value="<?php echo esc_attr($campaign_id); ?>">
                    <input type="hidden" name="wp_nonce" value="<?php echo wp_create_nonce('giftflowwp_donation_form'); ?>">
                    <input type="hidden" name="recurring_interval" value="<?php echo esc_attr($recurring_interval); ?>">

                    <button type="button" class="donation-form__button donation-form__button--back" data-prev-step="donation-information">
                        <?php echo giftflowwp_svg_icon('prev'); ?>
                        <?php _e('Back', 'giftflowwp'); ?>
                    </button>
										
                    <?php // support class loading show icon loading when submit ?>
                    <button type="submit" class="donation-form__button donation-form__button--submit">
                        <?php _e('Complete Donation', 'giftflowwp'); ?>
                        <?php echo giftflowwp_svg_icon('next'); ?>
                    </button>
                </div>
            </section>
            
            <?php 
            // do action to allow other plugins to add custom content after the payment method step
            /**
             * @see giftflowwp_donation_form_thank_you_section_html - 20
             * @see giftflowwp_donation_form_error_section_html - 22
             */
            do_action('giftflowwp_donation_form_after_payment_method', $args); 
            ?>
        </div>
    </div>
</form>