<?php 

function giftflowwp_donation_button_block() {

    register_block_type(
        'giftflowwp/donation-button',
        array(
            'api_version' => 3,
            'render_callback' => 'giftflowwp_donation_button_block_render',
            'attributes' => array(
                'campaignId' => array(
                    'type' => 'number',
                    'default' => 0,
                ),
                'buttonText' => array(
                    'type' => 'string',
                    'default' => 'Donate Now',
                ),
                'backgroundColor' => array(
                    'type' => 'string',
                    'default' => '#000000',
                ),
                'textColor' => array(
                    'type' => 'string',
                    'default' => '#ffffff',
                ),
                'borderRadius' => array(
                    'type' => 'number',
                    'default' => 0,
                ),
                'fullWidth' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
            ),
        )
    );
}

add_action('init', 'giftflowwp_donation_button_block');

function giftflowwp_donation_button_block_render($attributes, $content, $block) {
    $campaign_id = $attributes['campaignId'] ?? 0;
    
    // If no specific campaign is selected, use the current post ID
    if (empty($campaign_id)) {
        $campaign_id = get_the_ID();
    }

    // if still no campaign ID, return error message
    if (empty($campaign_id)) {
        ob_start();
        ?>
        <div class="giftflowwp-donation-button">
            <button class="donation-btn donation-btn--disabled--" disabled-- style="
                background-color: <?php echo esc_attr($attributes['backgroundColor'] ?? '#000000'); ?>;
                color: <?php echo esc_attr($attributes['textColor'] ?? '#ffffff'); ?>;
                border-radius: <?php echo esc_attr($attributes['borderRadius'] ?? 0); ?>px;
                --opacity: 0.6;
                cursor: not-allowed;
                <?php if ($attributes['fullWidth'] ?? false): ?>width: 100%;<?php endif; ?>
            ">
                <?php echo esc_html($attributes['buttonText'] ?? 'Donate Now'); ?>
            </button>
            <p class="donation-button-error">
                <?php echo giftflowwp_svg_icon('circle-alert'); ?>
                <?php _e('No campaign found. Please select a campaign or ensure this is a campaign post.', 'giftflowwp'); ?>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }

    // Get campaign data
    $goal_amount = get_post_meta($campaign_id, '_goal_amount', true);
    $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);
    $campaign_title = get_the_title($campaign_id);
    
    // Check if campaign exists and is active
    $campaign_status = get_post_status($campaign_id);
    $is_active = $campaign_status === 'publish';
    
    // Get button attributes
    $button_text = $attributes['buttonText'] ?? esc_html__('Donate Now', 'giftflowwp');
    $background_color = $attributes['backgroundColor'] ?? '#000000';
    $text_color = $attributes['textColor'] ?? '#ffffff';
    $border_radius = $attributes['borderRadius'] ?? 0;
    $full_width = $attributes['fullWidth'] ?? false;

    // Build CSS classes
    $button_classes = array(
        'donation-btn',
    );

    if (!$is_active) {
        $button_classes[] = 'donation-btn--disabled';
    }

    if ($full_width) {
        $button_classes[] = 'donation-btn--full-width';
    }

    $button_class_string = implode(' ', $button_classes);

    // Build inline styles for the button
    $button_styles = array(
        'background-color: ' . esc_attr($background_color),
        'color: ' . esc_attr($text_color),
        'border-radius: ' . esc_attr($border_radius) . 'px',
    );

    // Add full width styling if enabled
    if ($full_width) {
        $button_styles[] = 'width: 100%';
        $button_styles[] = 'max-width: none';
    }

    // Add disabled state styling if campaign is not active
    if (!$is_active) {
        $button_styles[] = 'opacity: 0.6';
        $button_styles[] = 'cursor: not-allowed';
    }

    $button_style_string = implode('; ', $button_styles);

    ob_start();
    ?>
    <div class="giftflowwp-donation-button" data-campaign-id="<?php echo esc_attr($campaign_id); ?>">
        <button 
            class="<?php echo esc_attr($button_class_string); ?>"
            style="<?php echo esc_attr($button_style_string); ?>"
            <?php echo $is_active ? '' : 'disabled'; ?>
            data-campaign-id="<?php echo esc_attr($campaign_id); ?>"
            data-campaign-title="<?php echo esc_attr($campaign_title); ?>"
        >
            <?php echo esc_html($button_text); ?>
        </button>
    </div>
    <?php
    return ob_get_clean();
}
