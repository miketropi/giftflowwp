# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Build & Development
- `npm run dev` - Watch and compile assets during development using Laravel Mix
- `npm run build` - Production build of assets
- `composer install` - Install PHP dependencies (required for plugin to function)
- `composer update` - Update PHP dependencies

### Asset Management
The plugin uses Laravel Mix (webpack.mix.js) for asset compilation:
- JS files in `assets/js/` are automatically compiled to `.bundle.js` files
- SCSS files in `assets/css/` are automatically compiled to `.bundle.css` files  
- Admin assets in `admin/js/` and `admin/css/` are compiled separately
- React blocks in `blocks/` are compiled to `blocks-build/`

## Code Architecture

### Plugin Structure
GiftFlowWP is a comprehensive WordPress donation plugin with the following core architecture:

#### Core Classes & Inheritance
- **Base Class**: `GiftFlowWp\Core\Base` - Foundation class that provides common functionality (version, plugin paths)
- **Post Types**: All custom post types extend `GiftFlowWp\Admin\PostTypes\Base_Post_Type`
- **Meta Boxes**: All meta boxes extend `GiftFlowWp\Admin\MetaBoxes\Base_Meta_Box`
- **Payment Gateways**: All gateways extend `GiftFlowWp\Gateways\Gateway_Base`

#### Custom Post Types (admin/includes/post-types/)
- `Donation` - Manages donation transactions with meta boxes for transaction details and recurring settings
- `Donor` - Manages donor profiles with contact info and donation history meta boxes
- `Campaign` - Manages campaigns with goal tracking and progress monitoring meta boxes

#### Payment Gateway System (includes/gateways/)
- Extensible gateway architecture using `Gateway_Base` abstract class
- Current implementations: Stripe (`class-stripe.php`), PayPal (`class-paypal.php`)
- Uses Omnipay library for standardized payment processing

#### Frontend Components (includes/frontend/)
- `Forms` class handles donation form rendering and AJAX processing
- `Template` class manages template loading and overrides
- `Shortcodes` class provides shortcode functionality

#### Field Builder System
The plugin includes a sophisticated field builder (`GiftFlowWP_Field` class) supporting:
- 14 field types: textfield, number, currency, select, multiple_select, textarea, checkbox, switch, datetime, color, gallery, googlemap
- Extensive customization options and validation
- Integration with WordPress meta boxes
- See `docs/field-builder.md` for complete documentation

#### Block System (blocks/)
- Custom Gutenberg blocks for campaign display and donation forms
- React-based blocks compiled via Laravel Mix
- Block templates in `block-templates/` for theme integration

### Key Namespaces
- `GiftFlowWp\Core\*` - Core plugin functionality
- `GiftFlowWp\Admin\PostTypes\*` - Custom post type definitions
- `GiftFlowWp\Admin\MetaBoxes\*` - Meta box implementations
- `GiftFlowWp\Gateways\*` - Payment gateway implementations
- `GiftFlowWp\Frontend\*` - Frontend functionality

### Hook System
The plugin provides extensive WordPress hooks for customization:

#### Key Action Hooks
- `donation_form_submitted` - After form submission
- `donation_payment_processed` - After successful payment
- `donation_campaign_goal_reached` - When campaign goal is met
- `donor_registered` - When new donor is created

#### Key Filter Hooks
- `donation_form_fields` - Customize form fields
- `donation_payment_gateways` - Add/modify payment gateways
- `donation_receipt_email_content` - Customize email templates

### Dependencies
- **PHP**: Requires PHP 8.2+ (specified in main plugin file)
- **WordPress**: 5.8+ minimum
- **Composer Dependencies**: Omnipay for payment processing
- **Node Dependencies**: Laravel Mix, React, Stripe JS, WordPress packages

### File Locations
- Main plugin file: `giftflowwp.php`
- Core includes: `includes/`
- Admin functionality: `admin/includes/`
- Frontend assets: `assets/`
- Block source: `blocks/`
- Compiled blocks: `blocks-build/`
- Templates: `templates/` and `block-templates/`
- Documentation: `docs/`

### Template System
- Templates can be overridden in active theme
- Block templates for FSE themes in `block-templates/`
- Standard PHP templates in `templates/`
- Template loading handled by `Template` class with fallback system

### Security & Data Handling
- All meta box data uses WordPress nonces for CSRF protection
- Payment processing through secure SDKs (Stripe PHP, PayPal REST API)
- Field validation and sanitization built into Field Builder system
- Database queries use WordPress standards and prepared statements