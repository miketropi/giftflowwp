---
name: gf-helper
description: Use ONLY when adding helper/utility functions to GiftFlow's common.php or when the user asks to "add a helper function", "add a giftflow_ function", or create reusable utilities. Also use when modifying hooks.php.
---

# GiftFlow Helper Functions

## Pattern Overview

Global helper functions live in `includes/common.php` (1743+ lines). All are prefixed with `giftflow_`. Hooks are wired in `includes/hooks.php`. Functions use WordPress APIs and follow WPCS conventions.

## Adding a Helper Function

1. **Add to `includes/common.php`** (at the end of the file):

```php
/**
 * Brief description of what this function does.
 *
 * @param string $param Description of parameter.
 * @param array  $args  Optional arguments.
 * @return mixed Description of return value.
 */
function giftflow_{function_name}( $param, $args = array() ) {
    // Implementation using WordPress APIs.
    // Always sanitize inputs.
    // Always escape outputs when echoing.

    $result = /* ... */;

    // Apply filter for extensibility.
    return apply_filters( 'giftflow_{filter_name}', $result, $param, $args );
}
```

2. **Add hook registration** to `includes/hooks.php` if the function hooks into WordPress:

```php
add_action( '{hook_name}', 'giftflow_{function_name}', 10, 2 );
add_filter( '{hook_name}', 'giftflow_{function_name}', 10, 2 );
```

## Naming Conventions
- Prefix: `giftflow_`
- Snake case: `giftflow_get_campaign_raised_amount()`
- Action verbs: `get_`, `render_`, `display_`, `validate_`, `sanitize_`, `process_`, `prepare_`
- Boolean checkers: `is_` prefix, e.g., `is_campaigns_page()`

## Coding Standards
- PHP 7.4+ compatible (no typed properties unless guarded, no union types)
- All user-facing strings use `__()`, `esc_html__()`, `_x()` with `'giftflow'` text domain
- All output must be escaped: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()`, `wp_kses_post()`
- Nonce verification for any form handlers
- `wp_send_json_success()` / `wp_send_json_error()` for AJAX
- DB queries with meta keys: add `phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key` comments
- Use `giftflow_load_template()` for template rendering
- Use `giftflow_render_currency_formatted_amount()` for currency display
- Use `giftflow_get_options()` for plugin settings
- Use `giftflow_sanitize_array()` for recursive array sanitization

## Common Patterns

### Template Loading
```php
function giftflow_render_something( $args = array() ) {
    ob_start();
    giftflow_load_template( 'path/to/template.php', $args );
    return ob_get_clean();
}
```

### Campaign Data Helpers
- `giftflow_get_campaign_raised_amount( $campaign_id )` — total completed donations
- `giftflow_get_campaign_progress_percentage( $campaign_id )` — 0-100 progress
- `giftflow_get_campaign_days_left( $campaign_id )` — days remaining
- `giftflow_get_campaign_donations( $campaign_id, $args, $paged )` — paginated donations
- `giftflow_prepare_campaign_status_bar_data( $post_id )` — status bar template data

### Donor Data Helpers
- `giftflow_get_donor_data_by_id( $donor_id )` — WP_Post with meta
- `giftflow_get_donor_by_email( $email )` — donor array by email
- `giftflow_get_donor_id_by_email( $email )` — creates donor if not found
- `giftflow_get_donor_user_information( $user_id )` — combined WP user + donor data
- `giftflow_query_donation_by_donor_id( $donor_id, $page, $per_page, $filters )` — paginated

### Currency Helpers
- `giftflow_get_current_currency()` — e.g., 'USD'
- `giftflow_get_currency_symbol( $currency )` — e.g., '$'
- `giftflow_render_currency_formatted_amount( $amount, $decimals, $currency, $template )` — HTML formatted

### Page Checkers
- `is_campaigns_page()`, `is_my_account_page()`, `is_thank_donor_page()`

### Options/Settings
- `giftflow_get_options( $option, $group, $default )` — read any option
- `giftflow_get_payment_methods_options()` — registered gateway IDs → titles
- `giftflow_get_donation_status_options()` — pending/completed/failed/refunded/cancelled

## File Locations
- Helper functions: **`includes/common.php`** (append at end)
- Hook registrations: **`includes/hooks.php`** (append at end)
- Currency data: **`includes/currency.php`** (returns array)
- Icons: **`includes/icons.php`** (returns array)
- Email functions: **`includes/mail.php`**
