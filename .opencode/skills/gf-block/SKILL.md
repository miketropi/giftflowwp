---
name: gf-block
description: Use ONLY when creating or modifying a Gutenberg block in the GiftFlow plugin. Follows the block.php registration + React component pattern. Trigger keywords: "add block", "create block", "new block", "Gutenberg block", "register block type", "block render".
---

# GiftFlow Gutenberg Block

## Pattern Overview

Each block lives in `blocks/{block-name}/` and is auto-loaded by `GiftFlow_Block_Loader` via `blocks/index.php`. The PHP `block.php` registers the block type with `render_callback`. The JS lives in `blocks/{block-name}/index.js` (or `edit.js`, `save.js`) and is compiled into `blocks-build/index.js`.

## Steps

1. **Create directory**: `blocks/{block-name}/`

2. **Create `blocks/{block-name}/block.php`**:

```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function giftflow_{block_name}_block() {
    register_block_type(
        'giftflow/{block-name}',
        array(
            'api_version'     => 3,
            'render_callback' => 'giftflow_{block_name}_block_render',
            'attributes'      => array(
                // Define block attributes that match the JS side.
                'myAttribute' => array(
                    'type'    => 'string',
                    'default' => '',
                ),
            ),
        )
    );
}
add_action( 'init', 'giftflow_{block_name}_block' );

function giftflow_{block_name}_block_render( $attributes, $content, $block ) {
    unset( $content );
    unset( $block );

    // Prepare data for the template.
    $template_data = array(
        'attributes' => $attributes,
    );

    // Load template using the plugin's template loader.
    ob_start();
    giftflow_load_template( 'block/{block-name}.php', $template_data );
    return ob_get_clean();
}
```

3. **Create template**: `templates/block/{block-name}.php` — The PHP template that renders the block output.

4. **Create JS component** (optional for dynamic blocks): `blocks/{block-name}/index.js`

The JS source is compiled via Laravel Mix into `blocks-build/index.js`. Elements use `@wordpress/element` (React 19). Assets are auto-extracted by `@wordpress/dependency-extraction-webpack-plugin`.

5. **No manual loading needed** — `blocks/index.php`::`GiftFlow_Block_Loader::load_blocks()` auto-loads all `blocks/*/block.php` files.

## Key Rules
- Block slug: `giftflow/{block-name}`, use kebab-case
- Block category: `giftflow` (auto-registered by Loader)
- Use `api_version: 3` for modern block API
- Use `giftflow_load_template()` inside render callbacks
- Template files go in `templates/block/` directory
- Handle `wp_is_serving_rest_request()` for Gutenberg editor SSR context
- For editor-only post ID context: use `__editorPostId` attribute pattern
- Use `giftflow_prepare_campaign_status_bar_data()` or similar helpers
- Register block on `init` hook

## Existing Blocks for Reference
- `blocks/campaign-status-bar/block.php` — simple dynamic block with REST detection
- `blocks/campaign-single-content/block.php` — content rendering block
- `blocks/campaigns-grid/block.php` — query loop block
- `blocks/donation-button/block.php` — interactive block
- `blocks/donor-account/block.php` — account page block
- `blocks/share/block.php` — social sharing
- `blocks/thank-donor/block.php` — thank you page block
