<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>

<section id="donation-thank-you" class="donation-form__step-panel step-thank-you" data-step="thank-you">
    <div class="donation-form__step-panel-content donation-form__thank-you">
        <?php echo giftflowwp_svg_icon('checkmark-circle'); // Add success icon ?>
        
        <div class="donation-form__thank-you-content">
            <h2 class="donation-form__thank-you-title">
                <?php _e('Thank You for Your Generous Support!', 'giftflowwp'); ?>
            </h2>
            
            <div class="donation-form__thank-you-details">
                <p class="donation-form__thank-you-message">
                    <?php _e('Your donation makes a real difference. A confirmation email has been sent to your inbox.', 'giftflowwp'); ?>
                </p>
                
                <div class="donation-form__thank-you-summary">
                    <div class="donation-form__thank-you-amount">
                        <span data-output="donation_amount" data-format-template="<?php echo $currency_format_template; ?>"></span>
                    </div>
                    <div class="donation-form__thank-you-campaign">
                        <?php echo esc_html($campaign_title); ?>
                    </div>
                </div>
            </div>

            <div class="donation-form__thank-you-actions">
                <a href="<?php echo get_permalink($campaign_id); ?>" class="donation-form__button">
                    <?php _e('Return to Campaign', 'giftflowwp'); ?>
                </a>
                <button type="button" class="donation-form__button donation-form__button--share">
                    <?php echo giftflowwp_svg_icon('share'); ?>
                    <?php _e('Share This Campaign', 'giftflowwp'); ?>
                </button>
            </div>
        </div>
    </div>
</section>