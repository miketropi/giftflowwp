# GiftFlow Design System

A comprehensive reference for UI design and development in the GiftFlow WordPress plugin. Use this document to generate new blocks, admin interfaces, and frontend components that are visually and structurally consistent with the existing plugin.

---

## Table of Contents

- [Overview](#overview)
- [Design Principles](#design-principles)
- [File Conventions](#file-conventions)
- [CSS Architecture](#css-architecture)
- [Design Tokens](#design-tokens)
- [Typography](#typography)
- [Spacing & Sizing](#spacing--sizing)
- [Color System](#color-system)
- [Block Architecture](#block-architecture)
- [Block Pattern Catalog](#block-pattern-catalog)
- [Interaction & Animation](#interaction--animation)
- [Responsive Design](#responsive-design)
- [Accessibility](#accessibility)
- [Icon System](#icon-system)
- [Admin UI Patterns](#admin-ui-patterns)
- [Template System](#template-system)
- [Generating New UI](#generating-new-ui)

---

## Overview

GiftFlow UI lives in three environments:
1. **Frontend blocks** — Gutenberg blocks rendered on the public site
2. **Frontend forms** — Donation forms, login forms, donor account pages
3. **Admin UI** — Settings pages, dashboard, meta boxes

The design system bridges these with shared tokens, naming conventions, and interaction patterns.

---

## Design Principles

1. **iOS-inspired** — clean, minimal, with subtle depth through shadows and blur. Transitions use Apple-style easing curves.
2. **Block supports first** — standard design properties (colors, typography, spacing, border) come from WordPress block supports, NOT custom attributes.
3. **CSS custom properties for theming** — colors, radii, and spacing are exposed as CSS variables for per-instance overrides.
4. **Progressive enhancement** — skeletons render before data loads. Disabled states are explicit.
5. **Accessible by default** — focus-visible outlines, ARIA attributes, semantic HTML.

---

## File Conventions

### Block directory structure

```
blocks/{block-name}/
├── block.json      # Block registration + supports + attributes
├── render.php      # Server-side render callback (dynamic block)
├── block.js        # Editor entry point (edit() only, no save())
└── style.css       # Styles loaded on frontend AND in editor
```

### Naming convention

| Scope | Pattern | Example |
|-------|---------|---------|
| Block slug | `giftflow/{kebab-case}` | `giftflow/campaign-card` |
| PHP function (register) | `giftflow_{snake_case}_block` | `giftflow_campaigns_grid_block` |
| PHP function (render) | `giftflow_{snake_case}_block_render` | `giftflow_campaigns_grid_block_render` |
| Editor script handle | `giftflow-block-{kebab-case}` | `giftflow-block-campaign-card` |
| CSS class | `.giftflow-{kebab-case}` | `.giftflow-campaign-card` |

### Legacy files

Some blocks retain a `block.php` for backward-compatible helper functions. New blocks do NOT need `block.php` — use `render.php` exclusively.

---

## CSS Architecture

### BEM naming

All block-level classes use BEM:

```
.giftflow-{block}                  Block root
.giftflow-{block}__{element}       Child element
.giftflow-{block}--{modifier}      Block modifier
.giftflow-{block}__{element}--{modifier}  Element modifier
```

```
<!-- Concrete example — campaign-card -->
<div class="giftflow-campaign-card giftflow-campaign-card--classic">
  <div class="giftflow-campaign-card__media">
    <img class="giftflow-campaign-card__image">
  </div>
  <div class="giftflow-campaign-card__content">
    <h3 class="giftflow-campaign-card__title">
      <a class="giftflow-campaign-card__title-link">Campaign Name</a>
    </h3>
    <div class="giftflow-campaign-card__progress">
      <div class="giftflow-campaign-card__progress-ring">...</div>
      <div class="giftflow-campaign-card__progress-stats">
        <div class="giftflow-campaign-card__progress-stat">
          <span class="giftflow-campaign-card__progress-stat-value">$12K</span>
          <span class="giftflow-campaign-card__progress-stat-label">raised</span>
        </div>
      </div>
    </div>
    <div class="giftflow-campaign-card__actions">
      <button class="giftflow-campaign-card__button giftflow-campaign-card__button--filled">
        <span class="giftflow-campaign-card__button-label">Donate Now</span>
        <span class="giftflow-campaign-card__button-arrow">→</span>
      </button>
    </div>
  </div>
</div>
```

### Block-root class on every element

Every block MUST output a root element with the block class AND `get_block_wrapper_attributes()`:

```php
$block_wrapper_attrs = get_block_wrapper_attributes(
    array( 'class' => 'giftflow-my-block' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
```

### CSS custom properties for dynamic values

**NEVER** set colors, sizes, or radii directly as inline styles on elements. Always use CSS custom properties so the hover/focus cascade works:

```php
// CORRECT — variables cascade through states
$vars = array(
    '--gf-btn-bg:' . esc_attr( $bg ),
    '--gf-btn-fg:' . esc_attr( $fg ),
);
echo '<button style="' . esc_attr( implode( ';', $vars ) ) . '">';

// WRONG — inline colors break hover states
echo '<button style="background-color:' . $bg . '">';
```

```css
/* style.css — reads the variable */
.giftflow-donation-button__btn {
    background-color: var(--gf-btn-bg, #1e1e1e);
    color: var(--gf-btn-fg, #ffffff);
}
.giftflow-donation-button__btn:hover {
    /* background-color override works because it uses the variable */
}
```

### Frontend form classes

Frontend forms use a different class prefix:

| Context | Prefix | Example |
|---------|--------|---------|
| Frontend forms | `.donation-form` (no `giftflow-` prefix) | `.donation-form__header` |
| Common utilities | `.gfw-` | `.gfw-loading-spinner`, `.gfw-donation-list__row` |

---

## Design Tokens

### Global CSS custom properties

Defined in `assets/css/common.bundle.css` on `:root`:

```css
:root {
    --giftflow-primary-color: #000000;
    --giftflow-secondary-color: #10B981;
    --giftflow-text-color: black;
    --giftflow-text-light: #6B7280;
    --giftflow-border-color: #E5E7EB;
    --giftflow-background-color: #F9FAFB;
    --giftflow-error-color: #EF4444;
    --giftflow-success-color: #10B981;
    --giftflow-border-radius: 6px;
    --giftflow-transition: all 0.3s ease;
    --giftflow-disabled: #fafafa;
    --giftflow-disabled-text: #999;
    --giftflow-font-family-monospace: monospace, "SF Mono", "Monaco", ...;
}
```

### Per-block scoped tokens

Each block declares its own CSS custom properties on the root class, providing sensible defaults that can be overridden:

```css
.giftflow-campaign-card {
    --gf-cc-accent: #2563eb;
    --gf-cc-accent-glow: rgba(37,99,235,0.14);
    --gf-cc-accent-soft: rgba(37,99,235,0.08);
    --gf-cc-overlay-opacity: 0.6;
    --gf-cc-radius: 22px;
    --gf-cc-inner-radius: 14px;
    --gf-cc-text: #1d1d1f;
    --gf-cc-text-secondary: #6e6e73;
    --gf-cc-text-tertiary: #8e8e93;
    --gf-cc-fill: #f2f2f7;
    --gf-cc-fill-track: #e5e5ea;
}
```

### Token naming convention for per-block tokens

```
--gf-{block-initials}-{property}

Examples:
--gf-cc-accent        → campaign-card accent color
--gf-btn-bg           → donation-button button background
--gf-grid-accent      → campaigns-grid accent
```

### WordPress preset fallbacks

Block styles should fall back to WordPress theme presets when available:

```css
/* Pattern: var(--wp--preset--color--{slug}, fallback) */
background: var(--wp--preset--color--surface, #fafafa);
border: 1px solid var(--wp--preset--color--base, #e5e7eb);
color: var(--wp--preset--color--contrast, #111827);
color: var(--gf-grid-accent, var(--wp--preset--color--primary, #2563eb));
```

Standard WP presets to reference:
- `--wp--preset--color--base` (light gray, backgrounds)
- `--wp--preset--color--contrast` (near-black text)
- `--wp--preset--color--contrast-2` (secondary text)
- `--wp--preset--color--primary` (brand accent)
- `--wp--preset--color--surface` (card surfaces)
- `--wp--preset--spacing--20`, `--wp--preset--spacing--40` (spacing scale)

---

## Typography

### Font family

```css
font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'Segoe UI', sans-serif;
```

Blocks inherit the theme font. Monospace is `--giftflow-font-family-monospace`.

### Font size scale

| Role | Size | Weight | Letter-spacing | Line-height |
|------|------|--------|----------------|-------------|
| Page heading | 1.5rem | 700 | -0.02em | 1.25 |
| Card title | 1.2rem | 700 | -0.01em | 1.3 |
| Subtitle / meta | 0.95rem | 650 | — | 1.3 |
| Body | 0.85–1rem | 400 | — | 1.55 |
| Small text / label | 0.78rem | 500 | — | 1.3 |
| Caption / badge | 0.68rem | 600 | 0.04em | 1.3 |
| Tiny badge | 0.58rem | 600 | 0.04em | 1.1 |

### Text utilities

- Uppercase badges: `text-transform: uppercase; letter-spacing: 0.04em;`
- Truncated excerpt: `-webkit-line-clamp: 2; overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical;`

---

## Spacing & Sizing

### Base unit

All spacing uses `rem`. Common values:

| Token | Value | Usage |
|-------|-------|-------|
| `--giftflow-border-radius` | 6px | Form elements |
| xs | 0.25rem | Tight gaps |
| sm | 0.5rem | Icon gaps |
| md | 0.75–1rem | Internal padding |
| lg | 1.25–1.5rem | Section padding, card padding |
| xl | 2rem | Page sections |
| 2xl | 2.5–4rem | Empty states |

### Border radius scale

| Token | Value | Usage |
|-------|-------|-------|
| Small | 4–6px | Badges, form inputs |
| Medium | 8–10px | Buttons, progress bars, images |
| Large | 12–16px | Cards, panels |
| Extra large | 18–22px | Card outer radius |
| Pill | 99px | Fully rounded buttons, badges |

### Card dimensions

```css
.giftflow-campaign-card {
    --gf-cc-radius: 22px;        /* outer */
    --gf-cc-inner-radius: 14px;  /* inner media/sections */
}
```

### Button sizing

```css
/* Regular */
padding: 0.85rem 1.5rem;
border-radius: 14px;
font-size: 0.9rem;

/* Pill */
border-radius: 99px;

/* Small/preset */
padding: 0.45rem 0.85rem;
border-radius: 10px;
font-size: 0.8rem;
```

---

## Color System

### Semantic colors (global)

| Token | Value | Usage |
|-------|-------|-------|
| `--giftflow-primary-color` | #000000 | Default brand |
| `--giftflow-secondary-color` | #10B981 | Success, secondary accents |
| `--giftflow-text-color` | black | Primary text |
| `--giftflow-text-light` | #6B7280 | Secondary text |
| `--giftflow-border-color` | #E5E7EB | Borders, dividers |
| `--giftflow-background-color` | #F9FAFB | Page backgrounds |
| `--giftflow-error-color` | #EF4444 | Error, destructive |
| `--giftflow-success-color` | #10B981 | Success, confirmation |

### Block accent colors (defaults)

| Block | Variable | Default |
|-------|----------|---------|
| campaign-card | `--gf-cc-accent` | #2563eb |
| campaigns-grid | `--gf-grid-accent` | falls to `--wp--preset--color--primary` → #2563eb |
| donation-button | `--gf-btn-bg` | #1e1e1e |

### iOS-inspired neutral palette

```css
/* Text */
--gf-cc-text: #1d1d1f;           /* Primary text (near-black) */
--gf-cc-text-secondary: #6e6e73; /* Secondary text (gray) */
--gf-cc-text-tertiary: #8e8e93;  /* Tertiary text (light gray) */

/* Fills */
--gf-cc-fill: #f2f2f7;           /* Surface fill (light) */
--gf-cc-fill-track: #e5e5ea;     /* Track/divider (medium) */
```

### Overlay pattern

```css
/* Overlay gradient for hero images */
background: linear-gradient(
    to top,
    rgba(0,0,0, calc(var(--gf-cc-overlay-opacity, 0.6) * 1.1)) 0%,
    rgba(0,0,0, calc(var(--gf-cc-overlay-opacity, 0.6) * 0.5)) 50%,
    rgba(0,0,0, 0) 100%
);

/* Frosted glass panel inside overlay */
background: rgba(0,0,0,0.25);
backdrop-filter: blur(20px);
-webkit-backdrop-filter: blur(20px);
```

---

## Block Architecture

### block.json template

```json
{
    "$schema": "https://schemas.wp.org/trunk/block.json",
    "apiVersion": 3,
    "name": "giftflow/{block-name}",
    "version": "1.0.0",
    "title": "Block Title",
    "category": "giftflow",
    "icon": "star-filled",
    "description": "Description shown in block inserter.",
    "keywords": ["keyword1", "keyword2"],
    "supports": {
        "color": {
            "background": true,
            "text": true,
            "gradients": true
        },
        "typography": {
            "fontSize": true,
            "fontWeight": true,
            "letterSpacing": true,
            "textTransform": true,
            "lineHeight": true
        },
        "border": {
            "color": true,
            "radius": true,
            "style": true,
            "width": true
        },
        "spacing": {
            "margin": true,
            "padding": true
        },
        "shadow": true,
        "align": ["wide", "full"],
        "html": false
    },
    "attributes": {
        "customProperty": { "type": "string", "default": "" }
    },
    "usesContext": ["postId", "postType"],
    "render": "file:./render.php",
    "editorScript": "giftflow-block-{block-name}",
    "style": "file:./style.css"
}
```

### What goes in `supports` vs `attributes`

| Put in `supports` (free from WP) | Do NOT add as custom attributes |
|-----------------------------------|----------------------------------|
| `color.background`, `color.text` | `backgroundColor`, `textColor` |
| `color.gradients` | `gradient` |
| `typography.fontSize` | `fontSize` |
| `typography.fontWeight` | `fontWeight` |
| `typography.letterSpacing` | `letterSpacing` |
| `typography.textTransform` | `textTransform` |
| `border.radius`, `border.width`, `border.color` | `borderRadius`, `borderWidth` |
| `spacing.padding`, `spacing.margin` | `paddingX`, `marginY` |
| `shadow` | `boxShadow` |

**Only add custom attributes for BUSINESSe LOGIC**: `campaignId`, `buttonText`, `buttonStyle`, `hoverEffect`, `showImage`, `cardStyle`, `logos[]`, `scrollSpeed`, etc.

### render.php template

```php
<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

// 1. Extract attributes with defaults
$gf_my_val = $attributes['myAttribute'] ?? 'default';

// 2. Resolve post context
$gf_post_id = (int) ( $attributes['campaignId'] ?? 0 );
if ( 0 === $gf_post_id && isset( $block->context['postId'] ) ) {
    $gf_post_id = (int) $block->context['postId'];
}
if ( 0 === $gf_post_id ) {
    $gf_post_id = get_the_ID();
}

// 3. Read block support values from style object
$gf_style = $attributes['style'] ?? array();
$gf_color = $gf_style['color'] ?? array();
$gf_bg    = $gf_color['background'] ?? '#default';

// 4. Build CSS custom properties (NOT inline styles)
$gf_vars = array();
$gf_vars[] = '--gf-block-bg:' . esc_attr( $gf_bg );

// 5. Build classes
$gf_classes = array( 'giftflow-my-block__element' );
if ( $condition ) {
    $gf_classes[] = 'giftflow-my-block__element--modifier';
}

// 6. Get block wrapper
$block_wrapper_attrs = get_block_wrapper_attributes(
    array( 'class' => 'giftflow-my-block' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore ?>>
    <!-- Output with BEM classes and CSS vars -->
    <div class="<?php echo esc_attr( implode( ' ', $gf_classes ) ); ?>"
         style="<?php echo esc_attr( implode( ';', $gf_vars ) ); ?>">
        <?php echo esc_html( $gf_my_val ); ?>
    </div>
</div>
```

### block.js editor template

```js
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleGroupControl, ToggleGroupControlOption } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
    ShimmerBar, ShimmerBox, ShimmerCircle,
    ensureShimmerStyles, useCampaignSelector
} from '../_editor-utils';

registerBlockType('giftflow/{block-name}', {
    apiVersion: 3,
    title: __('Block Title', 'giftflow'),
    icon: 'star-filled',
    category: 'giftflow',
    attributes: {
        /* mirror block.json exactly */
    },
    edit: (props) => {
        const { attributes, setAttributes, context } = props;
        const blockProps = useBlockProps({ className: 'giftflow-{block-name}' });
        ensureShimmerStyles();

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Content', 'giftflow')}>
                        {/* Custom business-logic controls only */}
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {/* Editor skeleton using SAME BEM classes as render.php */}
                </div>
            </>
        );
    },
});
```

---

## Block Pattern Catalog

### Card pattern (campaign-card)

- Root: `display: flex; flex-direction: column;` with `overflow: hidden; border-radius`
- Media: fixed aspect-ratio image with rounded corners
- Content: `padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem`
- Title: linked heading
- Meta: category badge (pill), location row (icon + text)
- Progress: SVG ring or linear bar
- Actions: presets (pill buttons) + CTA button
- Modifiers: `--classic`, `--overlay` (hero mode)

### Grid pattern (campaigns-grid)

- Container: CSS Grid with `grid-template-columns: repeat(var(--giftflow-grid-columns, 3), 1fr)`
- Items: card with `border-radius: 16px; overflow: hidden; border; bg`
- Hover: `translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.07)`
- Styles: `--flat`, `--minimal`

### Button pattern (donation-button)

- Root wrapper: transparent, no styling
- Button: `display: inline-flex; align-items: center; gap: 0.5rem`
- Modifiers: `--filled`, `--outline`, `--soft`, `--pill`
- States: `--disabled`, `--full-width`, `--has-hover-color`
- Hover effects: `--hover-lift`, `--hover-scale`, `--hover-glow`
- Icon slot: before/after text via `__icon` element
- Shimmer effect: gradient overlay `::after` pseudo-element

### Progress bar pattern (campaign-status-bar)

- Track: `height: 8px; border-radius: 99px; background`
- Fill: `height: 100%; border-radius: 99px; transition: width 0.6s ease`
- Stats: flex row with raised amount, donor count, days left
- ARIA: `role="progressbar" aria-valuenow="..." aria-valuemin="0" aria-valuemax="100"`

### CTA pattern (volunteer-cta)

- Centered layout with icon, heading, description, action button
- Icon: inline SVG heart icon
- Heading: `h2` element linked via `aria-labelledby`
- Rich text description via `wpautop()` + `wp_kses_post()`

### Marquee / scroller pattern (sponsor-logos)

- CSS scroll: `--gf-scroll-speed`, `--gf-scroll-gap`, `--gf-logo-height`
- Seamless loop via duplicated logo set
- Direction: `--left` / `--right`

---

## Interaction & Animation

### Transition curves

```css
/* Standard hover — iOS ease-out */
transition: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);

/* Card transform */
transition: transform 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94),
            border-color 0.3s ease,
            box-shadow 0.25s ease;

/* Button press */
transition: transform 0.15s ease, box-shadow 0.25s ease, opacity 0.2s ease;

/* Progress bar fill */
transition: width 0.6s ease, stroke-dashoffset 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
```

### Hover states

```css
/* Lift */
.giftflow-*-*:hover { transform: translateY(-2px); }

/* Lift (card) */
.giftflow-*-*:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.07); }

/* Border accent */
.giftflow-*-*:hover { border-color: var(--gf-*-accent, #2563eb); }

/* Arrow reveal */
.giftflow-*-*__button:hover .giftflow-*-*__button-arrow {
    transform: translateX(3px);
}
```

### Active/press states

```css
.giftflow-*-*:active { transform: scale(0.98); }
.giftflow-*-*-__preset:active { transform: scale(0.96); }
```

### Focus states

```css
.giftflow-*-*__button:focus-visible {
    outline: 2px solid var(--gf-*-accent, #2563eb);
    outline-offset: 3px;
}
```

### Loading skeletons (shimmer)

All skeletons use the same gradient animation:

```css
@keyframes gf-shimmer {
    0% { background-position: -400px 0; }
    100% { background-position: 400px 0; }
}

.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
    background-size: 800px 100%;
    animation: gf-shimmer 1.8s ease-in-out infinite;
}
```

Editor JS has shared components in `_editor-utils.js`:
- `ShimmerBar({ height, width, borderRadius, style })`
- `ShimmerBox({ height, width, style })`
- `ShimmerCircle({ size, style })`
- `ensureShimmerStyles()` — injects keyframes once

### Button pulse animation

```css
@keyframes gf-cc-btn-pulse {
    0%, 100% { box-shadow: 0 0 0 0 var(--gf-cc-accent-glow, rgba(37,99,235,0.15)); }
    50%      { box-shadow: 0 0 0 10px rgba(37,99,235,0); }
}
```

### Disabled state

```css
.giftflow-*-*--disabled {
    opacity: 0.45;
    cursor: not-allowed;
    pointer-events: none;
    animation: none; /* stop pulse/glow */
}
```

---

## Responsive Design

### Breakpoints

| Name | Width | Use |
|------|-------|-----|
| Mobile | max-width: 480px | Single-column, smaller forms |
| Mobile wide | max-width: 560px | Card adjustments |
| Tablet | max-width: 768px | Grid collapse |
| Tablet wide | max-width: 860px | Intermediate |
| Desktop | default | All columns |

### Grid behavior

```css
/* Desktop: fixed columns */
.giftflow-grid__items {
    grid-template-columns: repeat(var(--giftflow-grid-columns, 3), 1fr);
}

/* Tablet: auto-fill */
@media (max-width: 768px) {
    .giftflow-grid__items {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}
```

### Card responsive

```css
@media (max-width: 860px) {
    .giftflow-card__content { padding: 1.25rem; }
    .giftflow-card--overlay { min-height: 380px; }
}

@media (max-width: 560px) {
    .giftflow-card--overlay { min-height: 340px; }
    .giftflow-card__title { font-size: 1.1rem; }
    /* Stack ring + stats vertically */
    .giftflow-card__progress {
        flex-direction: column;
        align-items: center;
    }
}
```

---

## Accessibility

### Required patterns

1. **Focus-visible** on all interactive elements:
   ```css
   .element:focus-visible {
       outline: 2px solid var(--gf-*-accent, #2563eb);
       outline-offset: 2px;
   }
   ```

2. **ARIA on progress bars**:
   ```html
   <div role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
   ```

3. **Semantic headings** with label relationships:
   ```html
   <h2 id="giftflow-volunteer-cta-heading">Become a Volunteer</h2>
   <div aria-labelledby="giftflow-volunteer-cta-heading">...</div>
   ```

4. **Inline SVG icons** with `aria-hidden="true"`:
   ```html
   <span class="icon" aria-hidden="true">
       <svg ...>...</svg>
   </span>
   ```

5. **Disabled buttons** with both `disabled` attribute AND CSS:
   ```php
   $is_disabled = ! $is_published;
   ?>
   <button <?php echo $is_disabled ? 'disabled' : ''; ?>
           class="<?php echo $is_disabled ? 'giftflow-*--disabled' : ''; ?>">
   ```

6. **All user-facing strings** use i18n:
   ```php
   esc_html__( 'Donate Now', 'giftflow' );
   esc_html_e( 'No campaign selected.', 'giftflow' );
   ```

7. **Links vs buttons**: Use `<a>` for navigation, `<button>` for actions. Do not use `href="#"` with `onclick` — use `<button>`.

---

## Icon System

### Inline SVGs

Icons are defined inline in PHP as SVG markup — no icon font, no external sprite sheet. This allows stroke-based icons to inherit `currentColor`.

```php
// In render.php — define icons inline
$gf_icons = array(
    'heart' => '<svg width="18" height="18" viewBox="0 0 24 24"
        fill="none" stroke="currentColor" stroke-width="2.5"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5..."/></svg>',
);

// Output via icon element
echo '<span class="giftflow-*-*__icon" aria-hidden="true">' . $gf_icons['heart'] . '</span>';
```

### SVG attributes

- `fill="none"` — stroke-based rendering
- `stroke="currentColor"` — inherits text color
- `stroke-width="2"` or `"2.5"` — consistent weight
- `stroke-linecap="round" stroke-linejoin="round"` — rounded corners
- `viewBox="0 0 24 24"` — standard 24px grid

### Icon sizing

```css
.giftflow-*-*__icon {
    display: inline-flex;
    align-items: center;
    line-height: 0;
}
```

SVG `width`/`height` are set directly on the `<svg>` element (typically 18px).

### Available icon placeholders

| Name | Usage |
|------|-------|
| heart | Donate, favorite |
| sparkle | Featured, special |
| gift | Giving |
| arrow | Navigation, CTA |
| ribbon-heart | Campaign |

---

## Admin UI Patterns

### Meta Boxes (Field Builder)

Meta boxes use `GiftFlow_Field` class for rendering. See [`docs/field-builder.md`](field-builder.md) for full API.

CSS classes applied by the field builder:
- `.giftflow-field` — wrapper div
- `.giftflow-field-{type}` — type-specific (e.g., `.giftflow-field-textfield`)
- `.giftflow-field-input` — the input element

### Supported field types

`textfield`, `number`, `currency`, `select`, `multiple_select`, `textarea`, `checkbox`, `switch`, `datetime`, `color`, `gallery`, `googlemap`

### Admin settings pages

- URL pattern: `admin.php?page=giftflow-settings`, `admin.php?page=giftflow-dashboard`
- Standard WordPress Settings API with sections and fields
- Admin CSS in `assets/css/admin.bundle.css`

---

## Template System

### Directory structure

```
templates/
├── admin/                # Admin page templates
├── block/                # Block template overrides
├── classic/              # Classic theme templates
├── email/                # Email notification templates
├── campaign-single/      # Single campaign page
├── campaign-archive/     # Campaign archive
├── campaign-page/        # Campaign listing page
├── donor-account/        # Donor dashboard
├── payment-gateway/      # Gateway payment forms
├── thank-donor/          # Thank-you pages
├── campaign-grid.php     # Shared campaign grid
├── donation-form.php     # Donation form (shared)
├── donation-form-thank-you.php
├── donation-form-error.php
├── donation-list-of-campaign.php
├── login-form.php
└── campaign-comment.php
```

### Loading templates

```php
giftflow_load_template( 'campaign-grid.php', $template_args );
```

### Template override

Users can override templates by copying them to `{theme}/giftflow/{template-name}`.

---

## Generating New UI

### Checklist: New block

- [ ] Create `blocks/{block-name}/` directory
- [ ] Write `block.json` — apiVersion 3, `supports` for design, only custom attributes for business logic
- [ ] Write `render.php` — `get_block_wrapper_attributes()`, CSS custom properties for colors, BEM classes
- [ ] Write `style.css` — no hardcoded design values, WP preset fallbacks, focus-visible, states
- [ ] Write `block.js` — same BEM classes as render, editor skeleton always visible, shared shimmer utils
- [ ] Add entry to `webpack.config.js`
- [ ] Register in `AssetLoader::register_block_editor_scripts()`
- [ ] `npm run build`
- [ ] `composer lint`

### Checklist: New frontend form

- [ ] Use `.gfw-` or descriptive prefix (e.g., `.donation-form`)
- [ ] Reference global tokens: `--giftflow-border-color`, `--giftflow-text-light`, etc.
- [ ] Template in `templates/` directory
- [ ] Load with `giftflow_load_template()`
- [ ] BEM naming for elements and modifiers
- [ ] Responsive: `@media (max-width: 768px)` and `(max-width: 480px)`
- [ ] Loading state: `.gfw-loading-spinner` class

### Checklist: New admin UI

- [ ] Meta box extends `Base_Meta_Box`
- [ ] Fields use `GiftFlow_Field` with appropriate types
- [ ] Settings use WordPress Settings API
- [ ] Nonce verification in all form handlers
- [ ] `current_user_can()` capability checks

### Guidelines for AI/LLM generation

When prompting an LLM to generate GiftFlow UI, include these constraints:

1. **CSS**: BEM with `.giftflow-{block}` root. CSS custom properties for colors/sizes on the root class. Never inline styles on elements — use `style` attribute with CSS variables. WordPress preset fallbacks. `:focus-visible` on interactive elements. `transition: 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94)` for hover.

2. **PHP**: `get_block_wrapper_attributes()` on root div. Read `$attributes['style']` for block support values. Build `$gf_vars[]` array for CSS variables. BEM class arrays with `implode( ' ', $classes )`. Escape with `esc_attr()`, `esc_html()`, `wp_kses_post()`. All strings in `__()` / `esc_html__()` with `'giftflow'` text domain.

3. **JS**: `registerBlockType` with apiVersion 3. `useBlockProps({ className: 'giftflow-{block}' })`. Skeleton always visible — use shared `ShimmerBar`/`ShimmerBox`/`ShimmerCircle`. `ensureShimmerStyles()` called once. `InspectorControls` for business controls only. `useCampaignSelector()` for campaign pickers.

4. **Tokens**: Reference `--giftflow-*` global tokens. Declare `--gf-{initials}-*` per-block tokens with defaults. Fall through to `--wp--preset--color--*`.

5. **States**: `.giftflow-*--disabled` opacity 0.45, no pointer-events. `:active` scale(0.98). `:hover` translateY(-1px to -4px). Loading: skeleton shimmer or spinner.
