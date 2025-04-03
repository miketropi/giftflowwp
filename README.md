# GiftFlowWp - WordPress Donation Plugin

A comprehensive WordPress plugin for managing donations, donors, and campaigns with modern features and extensible architecture.

## Features

- **Donation Management**
  - Custom post types for donations, donors, and campaigns
  - Meta boxes for transaction details and recurring settings
  - Meta boxes for donor profiles and preferences
  - Meta boxes for campaign goals and progress
  - Custom taxonomies for campaigns and categories
  - Comprehensive donor profiles
  - Donation history tracking

- **Payment Processing**
  - Multiple payment gateway support (Stripe, PayPal)
  - Secure payment processing
  - Recurring donation options
  - Payment status tracking

- **Campaign Management**
  - Create and manage donation campaigns
  - Set campaign goals and track progress
  - Campaign-specific donation forms
  - Campaign analytics and reporting

- **Form Builder**
  - Drag-and-drop form builder
  - Multiple form templates
  - Custom field support
  - Conditional logic

- **Reporting & Analytics**
  - Dashboard widgets
  - Custom reports
  - Export functionality
  - Donation trends

## Requirements

- PHP 7.4 or higher
- WordPress 5.0 or higher
- MySQL 5.6 or higher
- Composer for dependency management
- SSL certificate (for secure payment processing)

## Payment Gateways

GiftFlowWp supports the following payment gateways through official SDKs:

1. **Stripe**
   - Direct integration using Stripe PHP SDK
   - Supports credit/debit cards
   - Secure payment processing
   - PCI compliant

2. **PayPal**
   - Direct integration using PayPal REST API SDK
   - Supports PayPal payments
   - Secure payment processing
   - PCI compliant

Additional payment gateways can be added through the plugin's extensible architecture.

## Installation

1. Upload the `giftflowwp` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to GiftFlowWp > Settings to configure the plugin

## Configuration

1. **General Settings**
   - Set your preferred currency
   - Configure currency position
   - Set up email notifications

2. **Payment Settings**
   - Enable payment gateways
   - Configure Stripe API keys
   - Set up PayPal email

3. **Email Settings**
   - Configure email templates
   - Set up notification preferences
   - Customize email content

## Usage

### Custom Post Types

1. **Donations**
   - Transaction details meta box
   - Recurring settings meta box
   - Payment status tracking
   - Donor and campaign relationships

2. **Donors**
   - Contact information meta box
   - Donation history meta box
   - Preferences meta box
   - Tax information storage

3. **Campaigns**
   - Campaign details meta box
   - Campaign settings meta box
   - Goal tracking
   - Progress monitoring

### Shortcodes

- `[giftflowwp_donation_form]` - Display a donation form
  - Parameters:
    - `campaign` - Campaign ID (optional)
    - `amount` - Default amount (optional)
    - `recurring` - Enable recurring donations (true/false)

- `[giftflowwp_donation_campaign]` - Display a campaign
  - Parameters:
    - `id` - Campaign ID
    - `show_progress` - Show progress bar (true/false)
    - `show_donors` - Show donor list (true/false)

### Templates

The plugin includes the following templates that can be overridden in your theme:

- `single-donation.php` - Single donation view with meta boxes
- `single-donor.php` - Single donor view with meta boxes
- `single-campaign.php` - Single campaign view with meta boxes
- `archive-donation.php` - Donation archive
- `donation-form.php` - Donation form
- `donation-campaign.php` - Campaign display

## Development

### Directory Structure

```
giftflowwp/
├── admin/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── includes/
│   │   ├── post-types/
│   │   │   ├── class-donation.php
│   │   │   ├── class-donor.php
│   │   │   └── class-campaign.php
│   │   ├── meta-boxes/
│   │   │   ├── class-donation-meta.php
│   │   │   ├── class-donor-meta.php
│   │   │   └── class-campaign-meta.php
│   │   └── core/
├── frontend/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── includes/
│   └── views/
├── includes/
│   └── core/
├── languages/
└── giftflowwp.php
```

### Hooks

#### Action Hooks

```php
// Form submission
do_action('donation_form_submitted', $donation_id, $form_data);
do_action('donation_payment_processed', $donation_id, $payment_data);
do_action('donation_recurring_created', $donation_id, $subscription_data);

// Campaign
do_action('donation_campaign_created', $campaign_id);
do_action('donation_campaign_updated', $campaign_id);
do_action('donation_campaign_goal_reached', $campaign_id);

// Donor
do_action('donor_registered', $donor_id);
do_action('donor_updated', $donor_id);
do_action('donor_donation_made', $donor_id, $donation_id);
```

#### Filter Hooks

```php
// Form customization
apply_filters('donation_form_fields', $fields, $form_id);
apply_filters('donation_form_validation', $validation_rules, $form_id);
apply_filters('donation_form_submission_data', $submission_data, $form_id);

// Email templates
apply_filters('donation_receipt_email_subject', $subject, $donation_id);
apply_filters('donation_receipt_email_content', $content, $donation_id);
apply_filters('donation_notification_email_recipients', $recipients, $donation_id);

// Payment processing
apply_filters('donation_payment_gateways', $gateways);
apply_filters('donation_payment_processing', $processing_data, $gateway);
apply_filters('donation_payment_completed', $payment_data, $donation_id);
```

## Support

For support, please visit our [support forum](https://giftflowwp.com/support) or email support@giftflowwp.com.

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by the GiftFlowWp Team. 