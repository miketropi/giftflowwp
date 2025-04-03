<?php 
/**
 * Common functions for the plugin
 * 
 * @package GiftFlowWP
 */

/**
 * Get the raised amount for a campaign
 *
 * @param int $campaign_id The campaign ID
 * @return float The raised amount
 */
function giftflowwp_get_campaign_raised_amount($campaign_id) {
    // Get all donations for this campaign
    $donations = get_posts(array(
        'post_type' => 'donation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_campaign_id',
                'value' => $campaign_id,
                'compare' => '='
            ),
            array(
                'key' => '_status',
                'value' => 'completed',
                'compare' => '='
            )
        )
    ));

    $total_amount = 0;

    // Sum up all completed donations
    foreach ($donations as $donation) {
        $amount = get_post_meta($donation->ID, '_amount', true);
        if ($amount) {
            $total_amount += floatval($amount);
        }
    }

    return $total_amount;
}

/**
 * Get the percentage of raised amount compared to goal amount
 *
 * @param int $campaign_id The campaign ID
 * @return float The percentage (0-100)
 */
function giftflowwp_get_campaign_progress_percentage($campaign_id) {
    $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);
    $goal_amount = get_post_meta($campaign_id, '_goal_amount', true);
    
    if (!$goal_amount || floatval($goal_amount) <= 0) {
        return 0;
    }
    
    $percentage = ($raised_amount / floatval($goal_amount)) * 100;
    
    // Ensure percentage is between 0 and 100
    return min(100, max(0, round($percentage, 2)));
}

/**
 * Display the campaign progress percentage
 *
 * @param int $campaign_id The campaign ID
 * @return string HTML formatted progress percentage
 */
function giftflowwp_display_campaign_progress($campaign_id) {
    $percentage = giftflowwp_get_campaign_progress_percentage($campaign_id);
    $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);
    $goal_amount = get_post_meta($campaign_id, '_goal_amount', true);
    
    $html = sprintf(
        '<div class="campaign-progress">
            <div class="progress-bar">
                <div class="progress" style="width: %s%%"></div>
            </div>
            <div class="progress-stats">
                <span class="raised">%s</span> / <span class="goal">%s</span> (%s%%)
            </div>
        </div>',
        esc_attr($percentage),
        esc_html(number_format($raised_amount, 2)),
        esc_html(number_format($goal_amount, 2)),
        esc_html($percentage)
    );
    
    return $html;
}

