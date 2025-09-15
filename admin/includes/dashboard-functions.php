<?php
/**
 * Dashboard Widget Functions for GiftFlowWP
 *
 * @package GiftFlowWP
 * @subpackage Admin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Widget 1: Display overview statistics
 */
function giftflowwp_display_overview_stats() {
    $total_campaigns = giftflowwp_get_total_campaigns();
    $total_donations = giftflowwp_get_total_donations_amount();
    $total_donors = giftflowwp_get_total_donors();
    $new_donors = giftflowwp_get_new_donors_count();
    $top_donors = giftflowwp_get_top_donors();
    
    giftflowwp_load_template('admin/dashboard-overview-stats.php', array(
        'total_campaigns' => $total_campaigns,
        'total_donations' => $total_donations,
        'total_donors' => $total_donors,
        'new_donors' => $new_donors,
        'top_donors' => $top_donors,
    ));
}

/**
 * Widget 2: Display highlight campaigns
 */
function giftflowwp_display_highlight_campaigns() {
    $campaigns = giftflowwp_get_highlight_campaigns();
    
    giftflowwp_load_template('admin/dashboard-highlight-campaigns.php', array(
        'campaigns' => $campaigns,
    ));
}

/**
 * Widget 3: Display recent donations
 */
function giftflowwp_display_recent_donations() {
    $donations = giftflowwp_get_recent_donations();
    
    giftflowwp_load_template('admin/dashboard-recent-donations.php', array(
        'donations' => $donations,
    ));
}

/**
 * Widget 4: Display statistics charts
 */
function giftflowwp_display_statistics_charts() {
    $active_campaigns = giftflowwp_count_campaigns_by_status('active');
    $closed_campaigns = giftflowwp_count_campaigns_by_status('closed');
    $pending_campaigns = giftflowwp_count_campaigns_by_status('pending');
    $completed_campaigns = giftflowwp_count_campaigns_by_status('completed');

    giftflowwp_load_template('admin/dashboard-statistics-charts.php', array(
        'active_campaigns' => $active_campaigns,
        'closed_campaigns' => $closed_campaigns,
        'pending_campaigns' => $pending_campaigns,
        'completed_campaigns' => $completed_campaigns
    ));
}

/**
 * Widget 6: Display quick actions
 */
function giftflowwp_display_quick_actions() {
    giftflowwp_load_template('admin/dashboard-quick-actions.php');
}

// Helper functions for data retrieval

/**
 * Get total campaigns count
 */
function giftflowwp_get_total_campaigns() {
    $campaigns = get_posts(array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields' => 'ids'
    ));
    return count($campaigns);
}

/**
 * Get active campaigns count
 */
function giftflowwp_count_campaigns_by_status($status = 'active') {
    $campaigns = get_posts(array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => '_status',
                'value' => $status,
                'compare' => '='
            )
        ),
        'fields' => 'ids'
    ));
    return count($campaigns);
}

/**
 * Get total donations amount
 */
function giftflowwp_get_total_donations_amount() {
    global $wpdb;
    $total = $wpdb->get_var("
        SELECT SUM(CAST(meta_value AS DECIMAL(10,2))) 
        FROM {$wpdb->postmeta} 
        WHERE meta_key = '_amount' 
        AND post_id IN (
            SELECT ID FROM {$wpdb->posts} 
            WHERE post_type = 'donation' 
            AND post_status = 'publish'
        )
    ");
    return $total ? $total : 0;
}

/**
 * Get total donors count
 */
function giftflowwp_get_total_donors() {
    $donors = get_posts(array(
        'post_type' => 'donor',
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields' => 'ids'
    ));
    return count($donors);
}

/**
 * Get highlight campaigns (top 3 by raised amount)
 */
function giftflowwp_get_highlight_campaigns() {
    $campaigns = get_posts(array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'numberposts' => 5,
        'orderby' => 'date',
        'order' => 'DESC'
        // 'orderby' => 'meta_value_num',
        // 'meta_key' => '_goal_amount'
    ));
    
    $highlight_campaigns = array();
    
    foreach ($campaigns as $campaign) {
        $goal = get_post_meta($campaign->ID, '_goal_amount', true);
        $raised = giftflowwp_get_campaign_raised_amount($campaign->ID);
        $percentage = $goal > 0 ? round(($raised / $goal) * 100, 1) : 0;
        
        $highlight_campaigns[] = array(
            'id' => $campaign->ID,
            'title' => $campaign->post_title,
            'link' => get_edit_post_link($campaign->ID),
            'goal' => $goal,
            'raised' => $raised,
            'percentage' => $percentage
        );
    }
    
    return $highlight_campaigns;
}

/**
 * Get recent donations (last 5)
 */
function giftflowwp_get_recent_donations() {
    $donations = get_posts(array(
        'post_type' => 'donation',
        'post_status' => 'publish',
        'numberposts' => 8,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $recent_donations = array();
    
    foreach ($donations as $donation) {
        $amount = get_post_meta($donation->ID, '_amount', true);
        $donor_id = get_post_meta($donation->ID, '_donor_id', true);
        $campaign_id = get_post_meta($donation->ID, '_campaign_id', true);
        
        $donor_name = 'Anonymous';
        if ($donor_id) {
            $first_name = get_post_meta($donor_id, '_first_name', true);
            $last_name = get_post_meta($donor_id, '_last_name', true);
            $donor_name = trim($first_name . ' ' . $last_name);
        }
        
        $campaign_title = 'General';
        $campaign_link = '';
        if ($campaign_id) {
            $campaign = get_post($campaign_id);
            if ($campaign) {
                $campaign_title = $campaign->post_title;
                $campaign_link = get_edit_post_link($campaign_id);
            }
        }
        
        $recent_donations[] = array(
            'donor_name' => $donor_name,
            'amount' => $amount,
            'campaign_title' => $campaign_title,
            'campaign_link' => $campaign_link,
            'date' => date_i18n('d/m/Y', strtotime($donation->post_date))
        );
    }
    
    return $recent_donations;
}

/**
 * Get top donor this month
 */
function giftflowwp_get_top_donors() {
    global $wpdb;
    
    $results = $wpdb->get_results("
        SELECT 
            pm1.meta_value as donor_id,
            pm2.meta_value as donor_name,
            SUM(CAST(dm.meta_value AS DECIMAL(10,2))) as total_amount
        FROM {$wpdb->posts} d
        INNER JOIN {$wpdb->postmeta} dm ON d.ID = dm.post_id AND dm.meta_key = '_amount'
        LEFT JOIN {$wpdb->postmeta} pm1 ON d.ID = pm1.post_id AND pm1.meta_key = '_donor_id'
        LEFT JOIN {$wpdb->postmeta} pm2 ON pm1.meta_value = pm2.post_id AND pm2.meta_key = '_email'
        WHERE d.post_type = 'donation'
        AND d.post_status = 'publish'
        GROUP BY donor_id, donor_name
        ORDER BY total_amount DESC
        LIMIT 5
    ");
    
    if ($results) {
        $top_donors = array();
        foreach ($results as $result) {
            $top_donors[] = array(
                'link' => get_edit_post_link($result->donor_id),
                'name' => $result->donor_name ?: 'Anonymous',
                'amount' => $result->total_amount
            );
        }
        return $top_donors;
    }
    
    return array(
        array('name' => 'N/A', 'amount' => 0),
        array('name' => 'N/A', 'amount' => 0),
        array('name' => 'N/A', 'amount' => 0),
        array('name' => 'N/A', 'amount' => 0),
        array('name' => 'N/A', 'amount' => 0)
    );
}

/**
 * Get new donors count (last 7 days)
 */
function giftflowwp_get_new_donors_count() {
    $new_donors = get_posts(array(
        'post_type' => 'donor',
        'post_status' => 'publish',
        'numberposts' => -1,
        'date_query' => array(
            array(
                'after' => '7 days ago'
            )
        ),
        'fields' => 'ids'
    ));
    
    return count($new_donors);
}
