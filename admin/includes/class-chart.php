<?php
/**
 * Chart functionality for GiftFlowWP
 *
 * @package GiftFlowWP
 * @subpackage Admin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GiftFlowWP_Chart {
    
    /**
     * Initialize the chart functionality
     */
    public function __construct() {
        add_action('wp_ajax_giftflowwp_get_chart_data', array($this, 'get_chart_data'));
    }
    
    /**
     * Get chart data via AJAX
     */
    public function get_chart_data() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'giftflowwp_chart_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $campaign_id = sanitize_text_field($_POST['campaign_id']);
        $period = sanitize_text_field($_POST['period']);
        
        // Get chart data based on period
        $chart_data = $this->get_donations_by_period($campaign_id, $period);
        
        wp_send_json_success($chart_data);
    }
    
    /**
     * Get donations data grouped by period
     */
    private function get_donations_by_period($campaign_id, $period) {
        global $wpdb;
        
        // Build date query based on period
        $date_condition = '';
        switch ($period) {
            case 'week':
                $date_condition = "AND DATE(p.post_date) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $date_condition = "AND DATE(p.post_date) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                break;
            case 'year':
                $date_condition = "AND DATE(p.post_date) >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                break;
        }
        
        // Base query
        $query = "
            SELECT 
                DATE(p.post_date) as donation_date,
                SUM(CAST(pm_amount.meta_value AS DECIMAL(10,2))) as total_amount,
                COUNT(p.ID) as donation_count
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm_amount ON p.ID = pm_amount.post_id AND pm_amount.meta_key = '_amount'
            WHERE p.post_type = 'donation'
            AND p.post_status = 'publish'
        ";
        
        // Add campaign filter
        if (!empty($campaign_id)) {
            $query .= " AND p.ID IN (
                SELECT post_id FROM {$wpdb->postmeta} 
                WHERE meta_key = '_campaign_id' AND meta_value = %d
            )";
            $query = $wpdb->prepare($query, $campaign_id);
        }
        
        // Add date filter
        $query .= " " . $date_condition;
        
        $query .= " GROUP BY DATE(p.post_date) ORDER BY donation_date ASC";
        
        $results = $wpdb->get_results($query);
        
        // Generate labels and data based on period
        $labels = array();
        $data = array();
        
        switch ($period) {
            case 'week':
                // Generate last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $labels[] = date('M j', strtotime($date));
                    
                    $amount = 0;
                    foreach ($results as $result) {
                        if ($result->donation_date === $date) {
                            $amount = floatval($result->total_amount);
                            break;
                        }
                    }
                    $data[] = $amount;
                }
                break;
                
            case 'month':
                // Generate last 30 days
                for ($i = 29; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $labels[] = date('M j', strtotime($date));
                    
                    $amount = 0;
                    foreach ($results as $result) {
                        if ($result->donation_date === $date) {
                            $amount = floatval($result->total_amount);
                            break;
                        }
                    }
                    $data[] = $amount;
                }
                break;
                
            case 'year':
                // Generate last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $month_start = date('Y-m-01', strtotime("-$i months"));
                    $month_end = date('Y-m-t', strtotime("-$i months"));
                    $labels[] = date('M Y', strtotime($month_start));
                    
                    $amount = 0;
                    foreach ($results as $result) {
                        if ($result->donation_date >= $month_start && $result->donation_date <= $month_end) {
                            $amount += floatval($result->total_amount);
                        }
                    }
                    $data[] = $amount;
                }
                break;
        }
        
        return array(
            'labels' => $labels,
            'data' => $data
        );
    }
}

// Initialize the chart functionality
new GiftFlowWP_Chart();
