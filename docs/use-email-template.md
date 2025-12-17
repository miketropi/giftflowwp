# Email Template System Documentation

The GiftFlow plugin includes a comprehensive email template system that allows developers to send beautifully formatted emails using predefined templates. This document explains how to use the email system effectively.

## Overview

The email system consists of:
- **Template Engine**: Uses the main `template-default.php` template with customizable content
- **Predefined Templates**: Ready-to-use templates for common scenarios
- **Flexible API**: Easy-to-use functions for sending emails
- **Test System**: Built-in testing functionality for email templates

## Core Functions

### `giftflow_send_mail_template($args)`

The main function for sending emails using the default template system.

#### Parameters

```php
$args = array(
    'to' => '',        // Required: Recipient email address
    'subject' => '',   // Required: Email subject line
    'header' => '',    // Optional: Custom header content
    'content' => '',   // Required: Main email content (HTML)
    'footer' => '',    // Optional: Custom footer content
);
```

#### Example Usage

```php
// Basic email
giftflow_send_mail_template(array(
    'to' => 'user@example.com',
    'subject' => 'Welcome to Our Platform',
    'content' => '<h2>Welcome!</h2><p>Thank you for joining us.</p>'
));

// Email with custom header and footer
giftflow_send_mail_template(array(
    'to' => 'user@example.com',
    'subject' => 'Donation Confirmation',
    'header' => 'Thank you for your donation',
    'content' => '<p>Your donation of $50 has been received.</p>',
    'footer' => 'Contact us if you have any questions.'
));
```

### `giftflow_load_template($template_name, $args)`

Loads a specific email template with variables.

#### Parameters

```php
$template_name = 'email/template-name.php';  // Template file path
$args = array(
    'variable_name' => 'value',  // Variables to pass to template
);
```

#### Example Usage

```php
// Load admin notification template
ob_start();
giftflow_load_template('email/new-donation-admin.php', array(
    'campaign_name' => 'Emergency Relief Fund',
    'campaign_url' => 'https://example.com/campaign/123',
    'donor_name' => 'John Doe',
    'donor_email' => 'john@example.com',
    'amount' => '$100.00',
    'date' => '2024-01-15',
    'status' => 'Completed',
    'payment_method' => 'Credit Card'
));
$content = ob_get_clean();

// Send the email
giftflow_send_mail_template(array(
    'to' => 'admin@example.com',
    'subject' => 'New Donation Received',
    'content' => $content
));
```

## Available Email Templates

### 1. Admin Donation Notification (`new-donation-admin.php`)

Sends notification to admin when a new donation is received.

#### Required Variables

```php
array(
    'campaign_name' => 'string',     // Campaign name
    'campaign_url' => 'string',      // Campaign URL (optional)
    'donor_name' => 'string',        // Donor's name
    'donor_email' => 'string',       // Donor's email
    'amount' => 'string',            // Donation amount
    'date' => 'string',              // Donation date
    'status' => 'string',            // Payment status
    'payment_method' => 'string'     // Payment method (optional)
)
```

#### Example

```php
ob_start();
giftflow_load_template('email/new-donation-admin.php', array(
    'campaign_name' => 'School Supplies Drive',
    'campaign_url' => home_url('/campaign/school-supplies'),
    'donor_name' => 'Jane Smith',
    'donor_email' => 'jane@example.com',
    'amount' => '$75.00',
    'date' => date('Y-m-d H:i:s'),
    'status' => 'Completed',
    'payment_method' => 'PayPal'
));
$content = ob_get_clean();

giftflow_send_mail_template(array(
    'to' => get_option('admin_email'),
    'subject' => 'New Donation: School Supplies Drive',
    'content' => $content
));
```

### 2. Donor Thank You (`thanks-donor.php`)

Sends thank you email to donor after successful donation.

#### Required Variables

```php
array(
    'campaign_name' => 'string',        // Campaign name (optional)
    'campaign_url' => 'string',         // Campaign URL (optional)
    'donor_name' => 'string',           // Donor's name
    'donor_email' => 'string',          // Donor's email
    'amount' => 'string',               // Donation amount
    'date' => 'string',                 // Donation date
    'donor_dashboard_url' => 'string'   // Dashboard URL (optional)
)
```

#### Example

```php
ob_start();
giftflow_load_template('email/thanks-donor.php', array(
    'campaign_name' => 'Emergency Relief Fund',
    'campaign_url' => home_url('/campaign/emergency-relief'),
    'donor_name' => 'John Doe',
    'donor_email' => 'john@example.com',
    'amount' => '$150.00',
    'date' => date('Y-m-d H:i:s'),
    'donor_dashboard_url' => home_url('/my-donations')
));
$content = ob_get_clean();

giftflow_send_mail_template(array(
    'to' => 'john@example.com',
    'subject' => 'Thank You for Your Donation',
    'header' => 'Thank You for Your Generous Donation',
    'content' => $content
));
```

### 3. New User Welcome (`new-user.php`)

Sends welcome email to new users after registration.

#### Required Variables

```php
array(
    'name' => 'string',           // User's display name
    'username' => 'string',       // Username
    'password' => 'string',       // Password (optional)
    'login_url' => 'string'       // Login URL (optional)
)
```

#### Example

```php
ob_start();
giftflow_load_template('email/new-user.php', array(
    'name' => 'Jane Smith',
    'username' => 'jane_smith',
    'password' => 'temp_password_123',
    'login_url' => wp_login_url()
));
$content = ob_get_clean();

giftflow_send_mail_template(array(
    'to' => 'jane@example.com',
    'subject' => 'Welcome to Our Platform',
    'header' => 'Welcome to Our Community',
    'content' => $content
));
```

## Email Configuration

### Email Settings

The system uses WordPress options for email configuration:

```php
$email_opts = get_option('giftflow_email_options');
$email_from_name = $email_opts['email_from_name'] ?? get_bloginfo('name');
$email_admin_address = $email_opts['email_admin_address'] ?? get_bloginfo('admin_email');
```

### Customizing Email Appearance

The email templates use the following variables for consistent styling:

```php
$accent_color = '#0b57d0';  // Primary accent color
$site_name = get_bloginfo('name');
$site_url = home_url();
$admin_email = get_option('admin_email');
```

## Testing Email Templates

### Test Function

Use the built-in test system to preview email templates:

```php
// Trigger test email
do_action('giftflow_test_send_mail', 'template_name');
```

### Available Test Templates

- `admin_new_donation` - Tests admin donation notification
- `donor_thanks` - Tests donor thank you email
- `new_user_first_time_donation` - Tests new user welcome email

### Example Test Usage

```php
// Test admin notification
do_action('giftflow_test_send_mail', 'admin_new_donation');

// Test donor thank you
do_action('giftflow_test_send_mail', 'donor_thanks');

// Test new user welcome
do_action('giftflow_test_send_mail', 'new_user_first_time_donation');
```

## Best Practices

### 1. Always Use Output Buffering

When loading templates, always use output buffering to capture the content:

```php
ob_start();
giftflow_load_template('email/template-name.php', $args);
$content = ob_get_clean();
```

### 2. Sanitize User Input

Always sanitize user input before passing to templates:

```php
$donor_name = sanitize_text_field($_POST['donor_name']);
$amount = sanitize_text_field($_POST['amount']);
```

### 3. Use Translation Functions

All text in templates should use WordPress translation functions:

```php
esc_html_e('Thank you for your donation', 'giftflow');
printf(esc_html__('Hello %s', 'giftflow'), esc_html($name));
```

### 4. Handle Missing Variables

Always provide fallback values for optional variables:

```php
$campaign_name = $campaign_name ?? '';
$campaign_url = $campaign_url ?? '';
```

### 5. Test Before Production

Always test email templates before deploying to production:

```php
// Test with sample data
do_action('giftflow_test_send_mail', 'template_name');
```

## Custom Template Development

### Creating Custom Templates

1. Create a new PHP file in `templates/email/` directory
2. Follow the existing template structure
3. Use the same variable naming conventions
4. Include proper security checks

### Template Structure

```php
<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// Define variables with fallbacks
$variable_name = $variable_name ?? '';

// Get site information
$site_name = get_bloginfo('name');
$site_url = home_url();
$admin_email = get_option('admin_email');
$accent_color = '#0b57d0';
?>

<!-- Your HTML content here -->
```

### Adding Test Support

To add test support for your custom template:

```php
// In mail.php, add to the test function
case 'your_template_name':
    ob_start();
    giftflow_load_template('email/your-template.php', array(
        'variable1' => '<Sample Value 1>',
        'variable2' => '<Sample Value 2>',
    ));
    $content = ob_get_clean();
    return giftflow_send_mail_template(array(
        'to' => $admin_email,
        'subject' => 'Test: Your Template',
        'content' => $content
    ));
    break;
```

## Troubleshooting

### Common Issues

1. **Emails not sending**: Check WordPress mail configuration
2. **Templates not loading**: Verify file paths and permissions
3. **Variables not displaying**: Ensure variables are properly passed
4. **Styling issues**: Check email client compatibility

### Debug Mode

Enable WordPress debug mode to see error messages:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Support

For additional support or questions about the email template system, please refer to the plugin documentation or contact the development team.