---
name: gf-block
description: Use ONLY when creating or modifying a Gutenberg block in the GiftFlow plugin. Follows the block.json + render.php + block.js + style.css pattern with BEM class sync. Trigger keywords: "add block", "create block", "new block", "Gutenberg block", "register block type", "block render", "block UI", "block editor", "editor skeleton", "block placeholder".
---

# GiftFlow Gutenberg Block

## Architecture

Blocks live in `blocks/{block-name}/`. Each contains:

```
blocks/{block-name}/
├── block.json      # Registration + block supports + attribute schema
├── render.php      # Frontend render callback (server-side)
├── block.js        # Editor entry (edit() function only — no save() for dynamic blocks)
└── style.css       # Block styles (loaded on frontend AND in editor)
```

Blocks are auto-discovered by `BlockRegistry` (src/Blocks/BlockRegistry.php) which scans for `block.json` files and calls `register_block_type_from_metadata()`. The legacy `block.php` files are kept for backward-compat helper functions but their `register_block_type` calls are unhooked.

Editor JS is built via `wp-scripts build` (webpack.config.js) → `build/blocks/{block-name}.js`. `AssetLoader` registers the scripts with handle `giftflow-block-{block-name}` exactly matching `editorScript` in block.json.

## block.json — Rules

### Block supports — use aggressively, avoid custom attributes

Leverage WordPress native block supports for **standard design properties**. The editor provides free controls (the Settings sidebar) for all of these:

```json
"supports": {
    "color": { "background": true, "text": true, "gradients": true },
    "typography": { "fontSize": true, "fontWeight": true, "letterSpacing": true, "textTransform": true, "lineHeight": true },
    "border": { "radius": true, "width": true, "color": true },
    "spacing": { "padding": true, "margin": true },
    "shadow": true,
    "align": ["left", "center", "right", "wide", "full"],
    "html": false
}
```

**NEVER** add custom attributes for: `backgroundColor`, `textColor`, `fontSize`, `fontWeight`, `letterSpacing`, `textTransform`, `borderRadius`, `borderWidth`, `paddingX`, `paddingY`, `shadow`. Let block supports handle them.

### Custom attributes — business logic only

Only define attributes that are **unique to the block's purpose** (not standard design):

```json
"attributes": {
    "campaignId":    { "type": "number", "default": 0 },
    "buttonText":    { "type": "string", "default": "Donate Now" },
    "buttonStyle":   { "type": "string", "default": "filled" },
    "hoverEffect":   { "type": "string", "default": "lift" },
    "icon":          { "type": "string", "default": "none" },
    "fullWidth":     { "type": "boolean", "default": false }
}
```

### Other block.json rules

- `"apiVersion": 3` always
- `"usesContext": ["postId", "postType"]` when block needs the current post
- `"render": "file:./render.php"` for dynamic blocks
- `"editorScript": "giftflow-block-{block-name}"` — must match the handle AssetLoader registers
- `"style": "file:./style.css"` — loaded in BOTH editor and frontend

## block.js — Rules

### Editor only — no save()

Dynamic blocks (all GiftFlow blocks) use `render.php` for frontend output. `block.js` defines `edit()` only:

```js
registerBlockType('giftflow/{block-name}', {
    apiVersion: 3,
    title: __('Block Title', 'giftflow'),
    icon: '...',
    category: 'giftflow',
    attributes: { /* mirror block.json */ },
    edit: (props) => {
        const blockProps = useBlockProps({ className: 'giftflow-{block-class}' });
        // ...
    },
});
```

### Editor skeleton MUST use the same BEM class names as render.php

The `style.css` is loaded in the editor. If the editor JS uses the same CSS classes as the frontend markup, the skeleton preview looks identical to the rendered output:

```jsx
// render.php outputs:
// <div class="giftflow-campaign-status-bar__progress">...</div>
// So the editor skeleton uses:
<div className="giftflow-campaign-status-bar__progress">
    <div className="giftflow-campaign-status-bar__progress-fill" style={{ width: '42%' }}></div>
</div>
```

**Rule**: Use `<div className="...">` (not `<div style={{...}}>`) wherever `style.css` already defines the styles. Only use inline `style={{}}` for truly dynamic values (e.g., progress percentage, custom colors from attributes).

### Show skeleton at all times

The editor skeleton must always render, even without post context. **No** conditional `Placeholder` fallbacks. The user must see a visual preview of how the block looks at all times.

### InspectorControls — custom options only

Do **NOT** add controls for color, typography, border, spacing — those are handled by block supports and appear automatically in the Settings sidebar. Only add panels for business-logic options:

```jsx
<InspectorControls>
    <PanelBody title={__('Campaign', 'giftflow')}>
        <SelectControl ... />
    </PanelBody>
    <PanelBody title={__('Content', 'giftflow')}>
        <TextControl ... />
        <ToggleGroupControl ... />
    </PanelBody>
    <PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
        <ToggleGroupControl ... />
    </PanelBody>
</InspectorControls>
```

### Use proper Gutenberg components

- `ToggleGroupControl` + `ToggleGroupControlOption` for mutually exclusive options (button style, hover effect, icon)
- `SelectControl` for dropdowns
- `TextControl` for text inputs
- `ToggleControl` for booleans
- `RangeControl` for numeric sliders
- **NOT** raw HTML buttons/inputs/dropdowns

### Read block support values at runtime

When the skeleton needs to reflect block support values (colors etc.), read from `attributes.style`:

```js
const s = attributes.style || {};
const color = s.color || {};
const bg = color.background || '#1e1e1e';
const fg = color.text || '#ffffff';
```

### Context-dependent blocks

Use `usesContext: ['postId']` and read `props.context.postId` (not `attributes.__editorPostId` — that pattern is deprecated). The skeleton should still render without a postId — just without real data.

## render.php — Rules

### Read block support values from $attributes['style']

```php
$gf_style  = $attributes['style'] ?? array();
$gf_color  = $gf_style['color'] ?? array();
$gf_bg     = $gf_color['background'] ?? '#1e1e1e';
$gf_fg     = $gf_color['text'] ?? '#ffffff';
```

### Use get_block_wrapper_attributes()

Always wrap the output in a div with block wrapper attributes:

```php
$block_wrapper_attrs = get_block_wrapper_attributes(
    array( 'class' => 'giftflow-my-block' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore ?>>
    ...
</div>
```

### Access context

```php
$gf_post_id = isset( $block->context['postId'] )
    ? (int) $block->context['postId']
    : get_the_ID();
```

### BEM class naming

All block-level classes use BEM naming: `.giftflow-{block}__{element}` and `.giftflow-{block}__{element}--{modifier}`. Match exactly between render.php and style.css.

## style.css — Rules

- Loaded on frontend AND in the editor (editor skeleton uses same classes)
- Base styles: display, position, transitions, cursor, focus-visible
- **Do NOT** hardcode colors, font sizes, padding, border radius — those come from block supports (WordPress applies them as inline styles or CSS custom properties)
- Use CSS custom property fallbacks: `var(--wp--preset--color--base, #e0e0e0)`
- Include `:focus-visible` for accessibility
- Handle `.is-active`, `[hidden]`, `--disabled` modifier states

## Shared utilities

`blocks/_editor-utils.js` provides:
- `ShimmerBar({ height, width })` — animated shimmer line
- `ShimmerBox({ height, width })` — animated shimmer rectangle
- `ShimmerCircle({ size })` — animated shimmer circle
- `ensureShimmerStyles()` — injects shimmer keyframes once
- `BlockPlaceholder({ icon, label, instructions })` — WordPress Placeholder wrapper

Import: `import { ShimmerBar, ensureShimmerStyles } from '../_editor-utils';`

## Build & Registration

1. Add entry to `webpack.config.js` under `entry`:
   ```js
   'blocks/my-block': path.resolve(process.cwd(), 'blocks/my-block/block.js'),
   ```

2. Add block name to `AssetLoader::register_block_editor_scripts()` in the `$blocks` array.

3. Build: `npm run build` → output at `build/blocks/my-block.js` + `build/blocks/my-block.asset.php`.

## Quick checklist

- [ ] block.json: apiVersion 3, uses block supports, only custom attributes for business logic
- [ ] block.js: same BEM classes as render.php, skeleton always visible, only custom controls in InspectorControls, use ToggleGroupControl/SelectControl not raw HTML
- [ ] render.php: reads style from $attributes['style'], uses get_block_wrapper_attributes()
- [ ] style.css: no hardcoded design values (colors, sizes, padding), focus-visible included
- [ ] Added to webpack.config.js entry and AssetLoader $blocks array
- [ ] `npm run build` passes with no errors
