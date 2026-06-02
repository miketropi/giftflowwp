---
name: gf-meta-box
description: Use ONLY when creating or modifying a meta box in the GiftFlow WordPress plugin admin. Follows the Base_Meta_Box abstract class with GiftFlow_Field integration. Trigger keywords: "add meta box", "create meta box", "custom field", "post meta", "admin fields", "meta box".
---

# GiftFlow Meta Box

## Pattern Overview

All meta boxes extend `GiftFlow\Admin\MetaBoxes\Base_Meta_Box`. The child class sets `$this->id`, `$this->title`, `$this->post_type` in the constructor, then implements `get_fields()` returning a field configuration array.

## Steps

1. **Create the class** in `admin/includes/meta-boxes/class-{name}-meta.php`:

```php
<?php
namespace GiftFlow\Admin\MetaBoxes;

use GiftFlow_Field;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class {Name}_Meta extends Base_Meta_Box {
    public function __construct() {
        $this->id        = '{meta_box_id}';
        $this->title     = esc_html__( '{Display Title}', 'giftflow' );
        $this->post_type = '{post_type}';   // e.g., 'campaign', 'donation', 'donor'
        parent::__construct();
    }

    protected function get_fields() {
        return array(
            'regular' => array(             // Tab key: 'regular', 'advanced', etc.
                '_my_field' => array(
                    'label'       => esc_html__( 'My Field', 'giftflow' ),
                    'type'        => 'textfield',  // textfield, number, currency, select, textarea, switch, datetime, gallery, checkbox, color, googlemap, repeater, accordion, html, multiple-select
                    'step'        => '1',
                    'min'         => '0',
                    'description' => esc_html__( 'Help text for this field', 'giftflow' ),
                    'options'     => array(        // For select type
                        'value1' => esc_html__( 'Label 1', 'giftflow' ),
                        'value2' => esc_html__( 'Label 2', 'giftflow' ),
                    ),
                    'default'     => '',
                ),
            ),
        );
    }
}

new {Name}_Meta();
```

2. **Require the file** in `giftflow.php` → `giftflow_load_files()`:

```php
require_once GIFTFLOW_PLUGIN_DIR . 'admin/includes/meta-boxes/class-{name}-meta.php';
```

3. **Initialize** in `includes/core/class-loader.php` → `init()` method:

```php
new \GiftFlow\Admin\MetaBoxes\{Name}_Meta();
```

## Available Field Types
- `textfield` — text input
- `number` — numeric input
- `currency` — currency input with symbol
- `textarea` — textarea
- `select` — dropdown
- `multiple-select` — multi-select
- `switch` — toggle on/off
- `checkbox` — checkbox
- `datetime` — date/time picker
- `gallery` — media gallery
- `color` — color picker
- `googlemap` — Google Maps location
- `repeater` — repeatable field group
- `accordion` — collapsible section
- `html` — custom HTML

## Key Rules
- Meta box ID: lowercase with underscores, e.g., `campaign_details`
- Meta keys: prefixed with `_`, e.g., `_goal_amount`
- The `get_fields()` array top-level keys define tabs (e.g., `'regular'`, `'advanced'`)
- Each field array must have at minimum: `label`, `type`
- `Base_Meta_Box` auto-handles `render_meta_box()` and `save_meta_box()` via `GiftFlow_Field`
- Always call `parent::__construct()` last so properties are set before hooks
- Text domain: always `'giftflow'`
- Class name pattern: `{Name}_Meta`

## Existing Meta Boxes for Reference
- `admin/includes/meta-boxes/class-campaign-details-meta.php` — `Campaign_Details_Meta` for `campaign` (331 lines, most complex)
- `admin/includes/meta-boxes/class-donor-contact-meta.php` — `Donor_Contact_Meta` for `donor`
- `admin/includes/meta-boxes/class-donation-transaction-meta.php` — `Donation_Transaction_Meta` for `donation`
