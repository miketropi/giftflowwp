# Share Block

A clean and simple WordPress block for social media sharing, email sharing, and URL copying functionality.

## Features

- **Social Media Sharing**: Support for 3 major platforms (Facebook, X/Twitter, LinkedIn)
- **Email Sharing**: Direct email sharing with pre-filled subject and body
- **Copy URL**: One-click URL copying to clipboard with visual feedback
- **Clean & Simple**: Minimal, focused interface with essential sharing options
- **Customizable**: Configurable title and custom URL override
- **Mobile Optimized**: Touch-friendly design with responsive breakpoints
- **Accessibility**: Proper focus states, ARIA labels, and keyboard navigation
- **Modern Clipboard API**: Uses modern clipboard API with fallback for older browsers

## Usage

### In WordPress Editor

1. Add the "Share" block to your post/page
2. Configure sharing options in the block settings sidebar:
   - **Share Settings**: Custom title and URL
   - **Sharing Options**: Toggle social media, email, and copy URL features

### Block Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | string | "Share this" | Heading text above share buttons |
| `customUrl` | string | "" | Custom URL to share (empty = current page) |
| `showSocials` | boolean | true | Display social media sharing buttons |
| `showEmail` | boolean | true | Display email sharing option |
| `showCopyUrl` | boolean | true | Display copy URL to clipboard button |

## Social Media Platforms

The block supports the following social media platforms:

### **Facebook**
- **Share URL**: `https://www.facebook.com/sharer/sharer.php?u={url}`
- **Icon**: Facebook icon
- **Hover Color**: Facebook blue (#1877f2)

### **X/Twitter**
- **Share URL**: `https://twitter.com/intent/tweet?url={url}&text={title}`
- **Icon**: Twitter icon
- **Hover Color**: Twitter blue (#1da1f2)

### **LinkedIn**
- **Share URL**: `https://www.linkedin.com/sharing/share-offsite/?url={url}`
- **Icon**: LinkedIn icon
- **Hover Color**: LinkedIn blue (#0077b5)

## Sharing Methods

### **Social Media**
- **Platforms**: Facebook, X/Twitter, LinkedIn
- **Behavior**: Opens sharing dialog in new tab
- **Content**: Automatically includes page URL and title

### **Email Sharing**
- **Method**: Direct `mailto:` links with pre-filled content
- **Subject**: "Check out: [Page Title]"
- **Body**: Page title, description, and URL
- **Icon**: Mail icon with hover color (#ea4335)

### **Copy URL**
- **Functionality**: One-click URL copying to clipboard
- **Feedback**: Visual confirmation message
- **Fallback**: Works on all browsers with graceful degradation
- **Icon**: Link icon with primary color hover

## CSS Classes

The block generates the following CSS classes for styling:

```css
.giftflowwp-share                           /* Main container */
.giftflowwp-share__title                    /* Title heading */
.giftflowwp-share__buttons                  /* Button container */
.giftflowwp-share__button                   /* Base button styles */
.giftflowwp-share__button--facebook         /* Facebook button */
.giftflowwp-share__button--twitter          /* X/Twitter button */
.giftflowwp-share__button--linkedin         /* LinkedIn button */
.giftflowwp-share__button--email            /* Email button */
.giftflowwp-share__button--copy-url         /* Copy URL button */
.giftflowwp-share__icon                     /* Button icon */
.giftflowwp-share__text                     /* Button text */
.giftflowwp-share__copy-feedback            /* Copy feedback message */
.giftflowwp-share__copy-message             /* Copy success text */
```

## Responsive Design

The block automatically adapts to different screen sizes:

- **Desktop**: Clean horizontal layout with hover effects
- **Tablet (≤768px)**: Optimized spacing and touch targets
- **Mobile (≤480px)**: Vertical stack layout for better mobile UX
- **Touch Devices**: Enhanced touch targets and feedback

### Mobile Layout Behavior

- **Desktop/Tablet**: Horizontal button arrangement
- **Mobile**: Vertical stack layout for optimal mobile experience
- **Touch Targets**: Minimum 44px for iOS compatibility

## Accessibility Features

- Minimum 44px touch targets on mobile
- Proper focus indicators with outline
- ARIA labels and screen reader support
- Keyboard navigation support
- High contrast mode support
- Reduced motion preferences respected

## Integration

The block automatically integrates with:

- **Current page/post data**: Automatically detects and uses current page information
- **WordPress permalinks**: Uses proper permalink structure
- **Post titles and excerpts**: Automatically includes current content metadata
- **Site description fallback**: Falls back to site description when needed
- **Custom URL override option**: Allows manual URL specification
- **Context-aware URL detection**: Automatically detects different WordPress page types

### **Smart URL Detection**

When `customUrl` is empty, the block automatically detects the current page context and generates appropriate sharing URLs:

#### **Post/Page Context (`is_singular()`)**
- **URL**: Current post/page permalink
- **Title**: Post/page title
- **Description**: Post excerpt or site description

#### **Home/Front Page (`is_home()` / `is_front_page()`)**
- **URL**: Site homepage URL
- **Title**: Site name
- **Description**: Site description

#### **Category Pages (`is_category()`)**
- **URL**: Category archive URL
- **Title**: Category name
- **Description**: Category description or site description

#### **Tag Pages (`is_tag()`)**
- **URL**: Tag archive URL
- **Title**: Tag name
- **Description**: Tag description or site description

#### **Author Pages (`is_author()`)**
- **URL**: Author posts archive URL
- **Title**: Author display name
- **Description**: Author bio or site description

#### **Date Archives (`is_date()`)**
- **URL**: Date archive URL
- **Title**: Current date
- **Description**: Site description

#### **Search Results (`is_search()`)**
- **URL**: Search results URL with query
- **Title**: "Search results for: [query]"
- **Description**: Site description

#### **Fallback Context**
- **URL**: Homepage with current query arguments
- **Title**: Site name
- **Description**: Site description

## Examples

### Basic Usage
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/share /-->'); ?>
```

### With Custom Title
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/share {"title":"Share this campaign"} /-->'); ?>
```

### With Custom URL
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/share {"customUrl":"https://example.com/custom-page"} /-->'); ?>
```

### Social Media Only
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/share {"showEmail":false,"showCopyUrl":false} /-->'); ?>
```

### Email and Copy URL Only
```php
<!-- In PHP template -->
<?php echo do_blocks('<!-- wp:giftflowwp/share {"showSocials":false} /-->'); ?>
```

### In Block Template
```html
<!-- wp:giftflowwp/share -->
<!-- wp:giftflowwp/share {"title":"Share this campaign","customUrl":"https://example.com/campaign"} /-->
<!-- /wp:giftflowwp/share -->
```

### Common Use Cases

#### Basic Social Sharing
```json
{
  "title": "Share this",
  "customUrl": "",
  "showSocials": true,
  "showEmail": true,
  "showCopyUrl": true
}
```

#### Campaign Sharing
```json
{
  "title": "Share this campaign",
  "customUrl": "https://example.com/campaign",
  "showSocials": true,
  "showEmail": true,
  "showCopyUrl": true
}
```

#### Social Media Only
```json
{
  "title": "Share on social media",
  "customUrl": "",
  "showSocials": true,
  "showEmail": false,
  "showCopyUrl": false
}
```

#### Email and Copy Only
```json
{
  "title": "Share via email or copy link",
  "customUrl": "",
  "showSocials": false,
  "showEmail": true,
  "showCopyUrl": true
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
blocks/share/
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

- **Modern Browsers**: Full feature support with modern clipboard API
- **IE11+**: Basic functionality with fallback clipboard support
- **Mobile Browsers**: Full responsive support
- **Touch Devices**: Optimized touch interactions

## Notes

- The block automatically detects the current page/post for sharing
- Custom URLs override the automatic detection
- All social media links open in new tabs with proper security attributes
- Copy URL functionality works across all modern browsers
- The block is fully responsive and mobile-optimized
- Platform-specific colors are applied on hover for better UX
- Email sharing pre-fills subject and body with page information
- Clean, simple design focused on essential sharing functionality
