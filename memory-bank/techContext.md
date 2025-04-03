# Technical Context: GiftFlowWp - WordPress Donation Plugin

## Technology Stack

### 1. Core Technologies
- PHP 7.4+
- WordPress 5.0+
- MySQL 5.6+
- JavaScript (ES6+)
- CSS3
- HTML5

### 2. External Libraries
```json
{
  "name": "giftflowwp",
  "description": "A comprehensive WordPress donation plugin",
  "require": {
    "php": ">=7.4",
    "composer/installers": "^2.0",
    "stripe/stripe-php": "^10.0",
    "paypal/rest-api-sdk-php": "^1.14",
    "phpoffice/phpspreadsheet": "^1.29",
    "dompdf/dompdf": "^2.0",
    "nesbot/carbon": "^2.0",
    "monolog/monolog": "^2.0"
  },
  "require-dev": {
    "phpunit/phpunit": "^9.0",
    "wp-coding-standards/wpcs": "^3.0",
    "dealerdirect/phpcodesniffer-composer-installer": "^1.0",
    "phpcompatibility/phpcompatibility-wp": "^2.1"
  }
}
```

### 3. Frontend Libraries
- jQuery (WordPress bundled)
- Select2
- Chart.js
- Moment.js
- Bootstrap 5 (optional)

### 3. Payment Processing
- Direct integration with payment gateways
- No dependency on WooCommerce
- Uses official SDKs:
  - Stripe PHP SDK
  - PayPal REST API SDK
- Secure payment processing
- PCI compliant implementation

## Development Environment

### 1. Local Development
- Local by Flywheel
- Docker
- XAMPP/MAMP
- VS Code
- PHPStorm

### 2. Version Control
- Git
- GitHub/GitLab
- Branching strategy:
  - main (production)
  - develop (staging)
  - feature/* (features)
  - hotfix/* (bug fixes)

### 3. Development Tools
- Composer
- npm
- WP-CLI
- PHP_CodeSniffer
- PHPUnit
- XDebug

## WordPress Integration

### 1. Core Features Used
- Custom Post Types
- Post Meta
- Taxonomies
- Meta Boxes
- Settings API
- Options API
- Transients API
- REST API
- Cron API
- Capabilities API

### 2. Custom Post Types
```php
// Donation Post Type
register_post_type('giftflowwp_donation', [
    'labels' => [
        'name' => __('Donations', 'giftflowwp'),
        'singular_name' => __('Donation', 'giftflowwp'),
    ],
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'supports' => ['title', 'custom-fields'],
    'capability_type' => 'post',
    'map_meta_cap' => true,
]);

// Donor Post Type
register_post_type('giftflowwp_donor', [
    'labels' => [
        'name' => __('Donors', 'giftflowwp'),
        'singular_name' => __('Donor', 'giftflowwp'),
    ],
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'supports' => ['title', 'custom-fields'],
    'capability_type' => 'post',
    'map_meta_cap' => true,
]);

// Campaign Post Type
register_post_type('giftflowwp_campaign', [
    'labels' => [
        'name' => __('Campaigns', 'giftflowwp'),
        'singular_name' => __('Campaign', 'giftflowwp'),
    ],
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'capability_type' => 'post',
    'map_meta_cap' => true,
]);

// Form Post Type
register_post_type('giftflowwp_form', [
    'labels' => [
        'name' => __('Forms', 'giftflowwp'),
        'singular_name' => __('Form', 'giftflowwp'),
    ],
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'supports' => ['title', 'custom-fields'],
    'capability_type' => 'post',
    'map_meta_cap' => true,
]);
```

### 3. Hooks and Filters
```php
// Core Hooks
add_action('init', 'register_post_types');
add_action('admin_init', 'register_settings');
add_action('wp_enqueue_scripts', 'enqueue_scripts');
add_filter('the_content', 'filter_content');

// Custom Hooks
do_action('giftflowwp_donation_processed', $donation_id);
do_action('giftflowwp_campaign_goal_reached', $campaign_id);
apply_filters('giftflowwp_donation_form_fields', $fields);
apply_filters('giftflowwp_donation_receipt_content', $content);
```

## Security Considerations

### 1. Data Protection
- Input validation
- Output escaping
- Nonce verification
- Capability checks
- Data encryption
- Secure cookies

### 2. Payment Security
- PCI compliance
- SSL/TLS
- Tokenization
- Secure API calls
- Error handling

### 3. Access Control
- Role-based access
- Permission checks
- IP restrictions
- Rate limiting
- Audit logging

## Performance Optimization

### 1. WordPress Optimization
- Proper post type registration
- Efficient meta queries
- Transient caching
- Query optimization
- Cleanup routines

### 2. Frontend
- Asset minification
- Lazy loading
- Caching headers
- CDN integration
- Code splitting

### 3. Backend
- Object caching
- Transient caching
- Background processing
- Queue system
- Resource optimization

## Testing Framework

### 1. Unit Testing
- PHPUnit
- WordPress test suite
- Mock objects
- Data providers
- Assertions

### 2. Integration Testing
- WordPress integration
- API testing
- Post type testing
- Payment gateway testing
- Third-party integration

### 3. End-to-End Testing
- Browser automation
- User workflows
- Form submission
- Payment processing
- Email delivery

## Deployment Process

### 1. Staging
- Environment setup
- Database migration
- Configuration
- Testing
- Approval

### 2. Production
- Backup
- Deployment
- Verification
- Monitoring
- Rollback plan

### 3. Maintenance
- Updates
- Security patches
- Performance monitoring
- Error tracking
- User feedback

## WordPress Development Standards

### File Naming Conventions
1. **Class Files**
   - Format: `class-{name}.php`
   - Must be lowercase
   - Use hyphens for word separation
   - Example: `class-plugin.php`, `class-admin.php`

2. **Autoloading Implementation**
   ```php
   // Correct implementation
   $filename = 'class-' . strtolower(str_replace('\\', '-', $relative_class)) . '.php';
   
   // Common mistakes to avoid
   $filename = $relative_class . '.php'; // WRONG
   $filename = 'Class' . $relative_class . '.php'; // WRONG
   ```

3. **Directory Structure**
   - Follow WordPress plugin structure
   - Use consistent naming across all directories
   - Maintain proper case sensitivity
   - Example structure:
     ```
     plugin-name/
     ├── admin/
     │   ├── class-admin.php
     │   └── ...
     ├── includes/
     │   ├── core/
     │   │   └── class-plugin.php
     │   └── ...
     └── ...
     ```

4. **Best Practices**
   - Always follow WordPress coding standards
   - Use proper file naming conventions
   - Implement consistent autoloading
   - Verify file paths and naming
   - Test autoloader with new classes 