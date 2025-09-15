<?php
/**
 * Export functionality for GiftFlowWP
 *
 * @package GiftFlowWP
 * @subpackage Admin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class GiftFlowWP_Export {
    
    /**
     * Initialize the export functionality
     */
    public function __construct() {
        add_action('wp_ajax_giftflowwp_export_donations', array($this, 'export_donations'));
        add_action('wp_ajax_nopriv_giftflowwp_export_donations', array($this, 'export_donations'));
    }
    
    /**
     * Export donations based on criteria
     */
    public function export_donations() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'giftflowwp_export_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $format = sanitize_text_field($_POST['format']);
        $campaign_id = sanitize_text_field($_POST['campaign_id']);
        $period = sanitize_text_field($_POST['period']);
        $date_from = sanitize_text_field($_POST['date_from']);
        $date_to = sanitize_text_field($_POST['date_to']);
        $include_donor = isset($_POST['include_donor']) ? true : false;
        $include_campaign = isset($_POST['include_campaign']) ? true : false;
        
        // Build query args
        $args = array(
            'post_type' => 'donation',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => array()
        );
        
        // Add campaign filter
        if (!empty($campaign_id)) {
            $args['meta_query'][] = array(
                'key' => '_campaign_id',
                'value' => $campaign_id,
                'compare' => '='
            );
        }
        
        // Add date filter
        $date_query = $this->get_date_query($period, $date_from, $date_to);
        if ($date_query) {
            $args['date_query'] = $date_query;
        }
        
        // Get donations
        $donations = get_posts($args);
        
        if (empty($donations)) {
            wp_die('No donations found for the selected criteria');
        }
        
        // Generate export based on format
        switch ($format) {
          case 'csv':
              $this->export_csv($donations, $include_donor, $include_campaign);
              break;
          default:
              wp_die('Invalid export format');
        }
    }
    
    /**
     * Get date query based on period
     */
    private function get_date_query($period, $date_from, $date_to) {
      switch ($period) {
        case 'year':
            return array(
                array(
                    'after' => '1 year ago',
                    'inclusive' => true,
                ),
            );
        case 'month':
            return array(
                array(
                    'after' => '1 month ago',
                    'inclusive' => true,
                ),
            );
        case 'week':
            return array(
                array(
                    'after' => '1 week ago',
                    'inclusive' => true,
                ),
            );
        case 'custom':
            if (!empty($date_from) && !empty($date_to)) {
                return array(
                    array(
                        'after' => $date_from,
                        'before' => $date_to,
                        'inclusive' => true,
                    ),
                );
            }
            break;
        case 'all':
        default:
            return null;
      }
      
      return null;
    }
    
    /**
     * Export donations to CSV
     */
    private function export_csv($donations, $include_donor, $include_campaign) {
        $filename = 'donations_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        $headers = array('ID', 'Date', 'Amount', 'Status');
        
        if ($include_donor) {
            $headers = array_merge($headers, array('Donor Name', 'Donor Email'));
        }
        
        if ($include_campaign) {
            $headers[] = 'Campaign';
        }
        
        $headers[] = 'Payment Method';
        
        fputcsv($output, $headers);
        
        // CSV data
        foreach ($donations as $donation) {
            $row = array(
                $donation->ID,
                get_the_date('Y-m-d H:i:s', $donation->ID),
                get_post_meta($donation->ID, '_amount', true),
                get_post_meta($donation->ID, '_status', true)
            );
            
            if ($include_donor) {
                $donor_id = get_post_meta($donation->ID, '_donor_id', true);
                if ($donor_id) {
                    $donor = get_post($donor_id);
                    $row[] = $donor ? $donor->post_title : '';
                    $row[] = get_post_meta($donor_id, '_email', true);
                } else {
                    $row[] = '';
                    $row[] = '';
                }
            }
            
            if ($include_campaign) {
                $campaign_id = get_post_meta($donation->ID, '_campaign_id', true);
                if ($campaign_id) {
                    $campaign = get_post($campaign_id);
                    $row[] = $campaign ? $campaign->post_title : '';
                } else {
                    $row[] = '';
                }
            }
            
            $row[] = get_post_meta($donation->ID, '_payment_method', true);
            
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
}

// Initialize the export functionality
new GiftFlowWP_Export();
