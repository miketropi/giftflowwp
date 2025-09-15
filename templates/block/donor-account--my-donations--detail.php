<?php 
/**
 * Template for my donations detail
 * @package GiftflowWP
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// print_r($donation);

// Extract donation data
$donation_id = $donation->ID;
$donation_title = $donation->post_title;
$donor_name = $donation->donor_name ?? '';
$donor_email = $donation->donor_email ?? '';
$amount = $donation->__amount_formatted ?? '$0.00';
$status = $donation->status ?? '';
$payment_method = $donation->payment_method ?? '';
$donation_date = $donation->__date_gmt ?? '';
$campaign_name = $donation->campaign_name ?? '';
$campaign_url = $donation->campaign_url ?? '';
$edit_url = $donation->donation_edit_url ?? '';
$message = $donation->message ?? '';
$anonymous = ucfirst($donation->anonymous ?? '');

// Status styling
$status_class = 'gfw-status-' . strtolower($status);
$status_label = ucfirst($status);

// Payment method styling
$payment_method_label = ucfirst(str_replace('_', ' ', $payment_method));
?>

<div class="gfw-donation-detail-actions">
  <a 
    href="<?php echo esc_url(giftflowwp_donor_account_page_url('donations')); ?>" 
    class="gfw-view-detail-link"
  >
    &larr; <?php esc_html_e('Back to Donations', 'giftflowwp'); ?>
  </a>
</div>

<div class="gfw-my-donations-detail">

  <!-- Header -->
  <div class="gfw-donation-detail-header">
    <div class="gfw-donation-detail-campaign gfw-donation-detail-title">
      Campaign:
      <a href="<?php echo esc_url($campaign_url); ?>" class="gfw-campaign-link" target="_blank" rel="noopener">
        <?php echo esc_html($campaign_name); ?>
      </a>
    </div>
    <div class="gfw-donation-detail-meta">
      <span class="gfw-donation-id">#<?php echo esc_html($donation_id); ?></span>
      <span class="gfw-donation-date"><?php echo esc_html($donation_date); ?></span>
    </div>
  </div>

  <!-- Donation Information -->
  <div class="gfw-donation-detail-content">
    
    <!-- Amount & Status -->
    <div class="gfw-donation-detail-amount-section">
      <div class="gfw-amount-display">
        <span class="gfw-amount"><?php echo $amount; ?></span>
        <span class="donation-status status-<?php echo esc_attr($status); ?>"><?php echo esc_html($status_label); ?></span>
      </div>
    </div>

    <table class="gfw-donation-detail-table">
      <tbody>
        <tr>
          <th colspan="2">Donation Details</th>
        </tr>
        <tr>
          <td class="gfw-detail-label">Name</td>
          <td class="gfw-detail-value"><?php echo esc_html($donor_name); ?></td>
        </tr>
        <tr>
          <td class="gfw-detail-label">Email</td>
          <td class="gfw-detail-value" style="font-family: monospace;"><?php echo esc_html($donor_email); ?></td>
        </tr>
        <tr>
          <td class="gfw-detail-label">Message</td>
          <td class="gfw-detail-value" style="font-family: monospace;"><?php echo esc_html($message); ?></td>
        </tr>
        <tr>
          <td class="gfw-detail-label">Anonymous</td>
          <td class="gfw-detail-value" style="font-family: monospace;"><?php echo esc_html($anonymous); ?></td>
        </tr>
        <tr>
          <td class="gfw-detail-label">Payment Method</td>
          <td class="gfw-detail-value"><?php echo esc_html($payment_method_label); ?></td>
        </tr>
        <tr>
          <td class="gfw-detail-label">Date</td>
          <td class="gfw-detail-value gfw-donation-date"><?php echo esc_html($donation_date); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Actions -->
  <div class="gfw-donation-detail-actions">
    <a href="<?php echo esc_url(wp_get_referer() ?: home_url('/donor-account')); ?>" class="gfw-view-detail-link">
      ← Back to Donations
    </a>
    <?php if (!empty($campaign_url)): ?>
    <a href="<?php echo esc_url($campaign_url); ?>" class="gfw-view-detail-link" target="_blank">
      View Campaign →
    </a>
    <?php endif; ?>
  </div>
</div>