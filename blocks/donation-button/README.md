# Donation Button Block

A customizable WordPress block for displaying donation buttons with campaign integration.

## Features

- **Campaign Selection**: Choose from available campaigns or auto-detect current post
- **Customizable Button Text**: Editable button label
- **Button Styling**: Custom background color, text color, and border radius
- **Full Width Option**: Toggle to make button span full container width
- **Campaign Integration**: Automatically displays campaign progress and status
- **Mobile Optimized**: Touch-friendly design with responsive breakpoints
- **Accessibility**: Proper focus states, ARIA labels, and keyboard navigation

## Usage

### In WordPress Editor

1. Add the "Donation Button" block to your post/page
2. Optionally select a specific campaign, or leave as "Auto-detect" to use the current post
3. Customize button text, styling, and width options using the block settings in the sidebar

### Block Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `campaignId` | number | 0 | ID of the selected campaign (0 = auto-detect current post) |
| `buttonText` | string | "Donate Now" | Custom text for the button |
| `backgroundColor` | string | "#000000" | Button background color (hex) |
| `textColor` | string | "#ffffff" | Button text color (hex) |
| `borderRadius` | number | 0 | Button border radius in pixels |
| `fullWidth` | boolean | false | Make button span full width of container |

## Campaign Selection Behavior

The block has two modes of operation:

1. **Specific Campaign**: When you select a campaign from the dropdown, the button will always use that campaign regardless of where the block is placed.

2. **Auto-detect (Default)**: When left as "Use Current Post (Auto-detect)", the block automatically detects the current post ID and uses it as the campaign. This is perfect for:
   - Campaign single post pages
   - Any page where you want the button to automatically match the current context
   - Reusable blocks that adapt to different campaigns

## Block Editor Controls

The block includes organized controls in the WordPress editor sidebar:

### Campaign Settings Panel
- **Select Campaign**: Dropdown to choose a specific campaign or use "Auto-detect"

### Button Settings Panel
- **Button Text**: Customize the button label
- **Full Width Button**: Toggle to make button span full container width

### Button Styling Panel
- **Background Color**: Color picker for button background
- **Text Color**: Color picker for button text
- **Border Radius**: Slider to adjust button corner roundness (0-20px)

## CSS Classes

The block generates the following CSS classes for styling:

```css
.giftflowwp-donation-button                    /* Main container */
.donation-btn                                  /* Base button styles */
.donation-btn--disabled                        /* Disabled state */
.donation-btn--full-width                     /* Full width button */
.donation-button-info                          /* Campaign info display */
.donation-button-status                        /* Status messages */
.donation-button-error                         /* Error messages */
```

## Responsive Design

The block automatically adapts to different screen sizes:

- **Desktop**: Full-featured layout with hover effects
- **Tablet (≤768px)**: Optimized spacing and touch targets
- **Mobile (≤480px)**: Compact layout with full-width buttons
- **Touch Devices**: Enhanced touch targets and feedback

### Full Width Behavior

- **Desktop**: Button respects full-width setting
- **Mobile**: All buttons are full-width by default for better mobile UX
- **Responsive**: Full-width setting works across all screen sizes

## Accessibility Features

- Minimum 44px touch targets on mobile
- Proper focus indicators
- ARIA labels and screen reader support
- High contrast mode support
- Reduced motion preferences respected
- Keyboard navigation support

## Integration

The block automatically integrates with:

- Campaign post types
- Donation tracking
- Currency formatting
- Progress calculations
- Campaign status detection

## Examples

### Basic Usage (Auto-detect)
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/donation-button /-->'); ?>
```

### With Specific Campaign
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/donation-button {"campaignId":123} /-->'); ?>
```

### With Custom Styling
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/donation-button {"campaignId":123,"buttonText":"Support This Cause","backgroundColor":"#10b981","textColor":"#ffffff","borderRadius":12} /-->'); ?>
```

### With Full Width
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/donation-button {"fullWidth":true} /-->'); ?>
```

### In Block Template
```html
<!-- wp:giftflowwp/donation-button -->
<!-- wp:giftflowwp/donation-button {"campaignId":123,"buttonText":"Support This Cause","backgroundColor":"#10b981","textColor":"#ffffff","borderRadius":12,"fullWidth":true} /-->
<!-- /wp:giftflowwp/donation-button -->
```

### Common Use Cases

#### Auto-detect Button (Default)
```json
{
  "campaignId": 0,
  "buttonText": "Donate Now",
  "backgroundColor": "#000000",
  "textColor": "#ffffff",
  "borderRadius": 0,
  "fullWidth": false
}
```

#### Full-Width Primary Button
```json
{
  "campaignId": 0,
  "buttonText": "Support This Cause",
  "backgroundColor": "#000000",
  "textColor": "#ffffff",
  "borderRadius": 0,
  "fullWidth": true
}
```

#### Specific Campaign Button
```json
{
  "campaignId": 123,
  "buttonText": "Support This Campaign",
  "backgroundColor": "#000000",
  "textColor": "#ffffff",
  "borderRadius": 0,
  "fullWidth": false
}
```

#### Custom Branded Full-Width Button
```json
{
  "campaignId": 0,
  "buttonText": "Give Today",
  "backgroundColor": "#8b5cf6",
  "textColor": "#ffffff",
  "borderRadius": 20,
  "fullWidth": true
}
```

## Development

### Building

The block is automatically built when running the webpack build process:

```bash
npm run build
# or
npm run watch
```

### File Structure

```
blocks/donation-button/
├── block.js          # Block registration and editor
├── block.php         # Server-side rendering
└── README.md         # This documentation
```

### Dependencies

- WordPress 5.0+
- PHP 7.4+
- Modern browser support
- CSS Grid and Flexbox support

## Browser Support

- **Modern Browsers**: Full feature support
- **IE11+**: Basic functionality (limited CSS features)
- **Mobile Browsers**: Full responsive support
- **Touch Devices**: Optimized touch interactions

## Notes

- The button uses inline styles for colors and border-radius to ensure maximum customization
- When no specific campaign is selected, the block automatically uses the current post ID
- This makes the block perfect for campaign pages where you want automatic context detection
- Full-width option works across all screen sizes and is especially useful for mobile layouts
- All styling options are applied directly to the button element
- The block automatically handles campaign status and progress display
