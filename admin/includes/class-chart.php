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
        add_action('wp_ajax_giftflowwp_get_status_chart_data', array($this, 'get_status_chart_data'));
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

    /**
     * Get donation statuses chart data via AJAX
     */
    public function get_status_chart_data() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'giftflowwp_chart_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $campaign_id = sanitize_text_field($_POST['campaign_id']);
        
        // Get status chart data
        $chart_data = $this->get_donations_by_status($campaign_id);
        
        wp_send_json_success($chart_data);
    }
    
    /**
     * Get donations data grouped by payment status
     */
    private function get_donations_by_status($campaign_id) {
        global $wpdb;
        
        // Base query - using the correct meta key '_status' instead of '_payment_status'
        $query = "
            SELECT 
                COALESCE(pm_status.meta_value, 'pending') as payment_status,
                COUNT(p.ID) as donation_count,
                SUM(CAST(pm_amount.meta_value AS DECIMAL(10,2))) as total_amount
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm_amount ON p.ID = pm_amount.post_id AND pm_amount.meta_key = '_amount'
            LEFT JOIN {$wpdb->postmeta} pm_status ON p.ID = pm_status.post_id AND pm_status.meta_key = '_status'
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
        
        $query .= " GROUP BY payment_status ORDER BY donation_count DESC";
        
        $results = $wpdb->get_results($query);
        
        // Debug: Log the results for troubleshooting
        error_log('Donation Status Results: ' . print_r($results, true));
        
        // Define all possible statuses with proper mapping
        $all_statuses = array(
            'pending' => array('label' => 'Pending', 'count' => 0, 'amount' => 0, 'color' => '#FACC15'),
            'completed' => array('label' => 'Completed', 'count' => 0, 'amount' => 0, 'color' => '#22C55E'),
            'failed' => array('label' => 'Failed', 'count' => 0, 'amount' => 0, 'color' => '#EF4444'),
            'refunded' => array('label' => 'Refunded', 'count' => 0, 'amount' => 0, 'color' => '#3B82F6'),
            'processing' => array('label' => 'Processing', 'count' => 0, 'amount' => 0, 'color' => '#2196F3'),
            'cancelled' => array('label' => 'Cancelled', 'count' => 0, 'amount' => 0, 'color' => '#795548')
        );
        
        // Process results
        foreach ($results as $result) {
            $status = strtolower(trim($result->payment_status));
            if (isset($all_statuses[$status])) {
                $all_statuses[$status]['count'] = intval($result->donation_count);
                $all_statuses[$status]['amount'] = floatval($result->total_amount);
            } else {
                // If status is not recognized, add it to pending
                $all_statuses['pending']['count'] += intval($result->donation_count);
                $all_statuses['pending']['amount'] += floatval($result->total_amount);
            }
        }
        
        // Generate chart data - only include statuses with donations
        $labels = array();
        $data = array();
        $colors = array();
        
        foreach ($all_statuses as $status => $info) {
            if ($info['count'] > 0) {
                $labels[] = $info['label'] . ' (' . $info['count'] . ')';
                $data[] = $info['count'];
                $colors[] = $info['color'];
            }
        }
        
        // If no data found, return empty state
        if (empty($data)) {
            $labels = array('No Data');
            $data = array(1);
            $colors = array('#f0f0f0');
        }
        
        return array(
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors
        );
    }
}

// Initialize the chart functionality
new GiftFlowWP_Chart();
