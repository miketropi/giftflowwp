# GiftFlowWP Modal Library

A clean, simple, and easy-to-use modal system with AJAX support, smooth animations, and accessibility features.

## Features

- **🎯 Simple API**: Easy to use with minimal configuration
- **🌐 AJAX Support**: Load content dynamically from URLs
- **✨ Smooth Animations**: Fade, slide, and zoom effects
- **📱 Responsive Design**: Mobile-first approach with touch optimizations
- **♿ Accessibility**: ARIA labels, focus trapping, keyboard navigation
- **🎨 Clean UI**: Modern, professional appearance
- **🔧 Flexible**: Customizable size, position, and behavior
- **📋 Built-in Dialogs**: Alert, confirm, and prompt methods

## Quick Start

### Basic Usage

```javascript
// Create a simple modal
const modal = new GiftFlowWPModal({
    content: '<h2>Hello World!</h2><p>This is a simple modal.</p>',
    closeButton: true
});

modal.open();
```

### Static Methods

```javascript
// Alert dialog
GiftFlowWPModal.alert('Operation completed successfully!', 'Success');

// Confirm dialog
const confirmed = await GiftFlowWPModal.confirm('Are you sure?', 'Confirm Action');
if (confirmed) {
    // User clicked OK
}

// Prompt dialog
const value = await GiftFlowWPModal.prompt('Enter your name:', 'John Doe', 'User Input');
if (value) {
    console.log('User entered:', value);
}
```

## API Reference

### Constructor Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `id` | string | 'giftflowwp-modal' | Modal ID attribute |
| `className` | string | 'giftflowwp-modal' | Additional CSS classes |
| `overlay` | boolean | true | Show overlay background |
| `closeOnOverlay` | boolean | true | Close when clicking overlay |
| `closeOnEscape` | boolean | true | Close when pressing Escape |
| `closeButton` | boolean | true | Show close button |
| `animation` | string | 'fade' | Animation type: 'fade', 'slide', 'zoom' |
| `duration` | number | 300 | Animation duration in milliseconds |
| `easing` | string | 'ease-in-out' | CSS easing function |
| `content` | string | '' | HTML content |
| `ajax` | boolean | false | Enable AJAX content loading |
| `ajaxUrl` | string | '' | URL for AJAX request |
| `ajaxData` | object | {} | Data for AJAX request |
| `ajaxMethod` | string | 'GET' | HTTP method for AJAX |
| `width` | string | 'auto' | Modal width |
| `maxWidth` | string | '90vw' | Maximum modal width |
| `height` | string | 'auto' | Modal height |
| `maxHeight` | string | '90vh' | Maximum modal height |
| `position` | string | 'center' | Position: 'center', 'top', 'bottom' |
| `autoClose` | boolean | false | Auto-close modal |
| `autoCloseDelay` | number | 5000 | Auto-close delay in milliseconds |
| `ariaLabel` | string | 'Modal dialog' | Accessibility label |
| `focusTrap` | boolean | true | Trap focus within modal |

### Methods

#### `open()`
Opens the modal with animation.

```javascript
modal.open();
```

#### `close()`
Closes the modal with animation.

```javascript
modal.close();
```

#### `setContent(content)`
Updates modal content.

```javascript
modal.setContent('<p>New content here</p>');
```

#### `updateOptions(newOptions)`
Updates modal options.

```javascript
modal.updateOptions({
    width: '600px',
    maxWidth: '800px'
});
```

#### `destroy()`
Removes modal from DOM.

```javascript
modal.destroy();
```

### Callbacks

| Callback | Parameters | Description |
|----------|------------|-------------|
| `onOpen` | `(modal)` | Called when modal opens |
| `onClose` | `(modal)` | Called when modal closes |
| `onLoad` | `(content, modal)` | Called after AJAX content loads |
| `onError` | `(error, modal)` | Called if AJAX request fails |

## Usage Examples

### Basic Modal

```javascript
const modal = new GiftFlowWPModal({
    content: `
        <div class="giftflowwp-modal__header">
            <h3>Welcome</h3>
        </div>
        <div class="giftflowwp-modal__body">
            <p>This is a basic modal with custom content.</p>
        </div>
        <div class="giftflowwp-modal__footer">
            <button class="giftflowwp-modal__btn giftflowwp-modal__btn--primary" onclick="this.closest('.giftflowwp-modal').giftflowwpModal.close()">
                Close
            </button>
        </div>
    `,
    closeButton: true,
    animation: 'slide'
});

modal.open();
```

### AJAX Modal

```javascript
const ajaxModal = new GiftFlowWPModal({
    ajax: true,
    ajaxUrl: '/api/get-content',
    ajaxMethod: 'POST',
    ajaxData: { id: 123 },
    onLoad: (content, modal) => {
        console.log('Content loaded:', content);
    },
    onError: (error, modal) => {
        console.error('Failed to load:', error);
    }
});

ajaxModal.open();
```

### Custom Size and Position

```javascript
const customModal = new GiftFlowWPModal({
    content: '<p>Custom sized modal</p>',
    width: '800px',
    maxWidth: '1000px',
    height: '400px',
    position: 'top',
    animation: 'zoom',
    duration: 500
});

customModal.open();
```

### Modal with Callbacks

```javascript
const callbackModal = new GiftFlowWPModal({
    content: '<p>Modal with callbacks</p>',
    onOpen: (modal) => {
        console.log('Modal opened');
        // Initialize content, bind events, etc.
    },
    onClose: (modal) => {
        console.log('Modal closed');
        // Clean up, save state, etc.
    }
});

callbackModal.open();
```

### Auto-close Modal

```javascript
const autoCloseModal = new GiftFlowWPModal({
    content: '<p>This modal will close automatically in 3 seconds</p>',
    autoClose: true,
    autoCloseDelay: 3000,
    closeButton: false,
    closeOnOverlay: false,
    closeOnEscape: false
});

autoCloseModal.open();
```

### Form Modal

```javascript
const formModal = new GiftFlowWPModal({
    content: `
        <div class="giftflowwp-modal__header">
            <h3>Contact Form</h3>
        </div>
        <div class="giftflowwp-modal__body">
            <form>
                <div style="margin-bottom: 1rem;">
                    <label for="name">Name:</label>
                    <input type="text" id="name" class="giftflowwp-modal__input" required>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="email">Email:</label>
                    <input type="email" id="email" class="giftflowwp-modal__input" required>
                </div>
            </form>
        </div>
        <div class="giftflowwp-modal__footer">
            <button class="giftflowwp-modal__btn giftflowwp-modal__btn--secondary" onclick="this.closest('.giftflowwp-modal').giftflowwpModal.close()">
                Cancel
            </button>
            <button class="giftflowwp-modal__btn giftflowwp-modal__btn--primary" onclick="submitForm()">
                Submit
            </button>
        </div>
    `,
    closeButton: true,
    width: '500px'
});

formModal.open();

function submitForm() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    
    if (name && email) {
        // Submit form logic here
        console.log('Form submitted:', { name, email });
        formModal.close();
    }
}
```

### Loading State

```javascript
const loadingModal = new GiftFlowWPModal({
    ajax: true,
    ajaxUrl: '/api/slow-endpoint',
    onLoad: (content, modal) => {
        // Content loaded successfully
        console.log('Content loaded');
    },
    onError: (error, modal) => {
        // Handle error
        modal.setContent('<div class="giftflowwp-modal__error">Failed to load content</div>');
    }
});

loadingModal.open();
```

## CSS Classes

The modal generates the following CSS classes for styling:

```css
.giftflowwp-modal__overlay              /* Modal overlay */
.giftflowwp-modal                       /* Main modal container */
.giftflowwp-modal__content              /* Modal content area */
.giftflowwp-modal__close                /* Close button */
.giftflowwp-modal__header               /* Modal header */
.giftflowwp-modal__body                 /* Modal body */
.giftflowwp-modal__footer               /* Modal footer */
.giftflowwp-modal__btn                  /* Button base styles */
.giftflowwp-modal__btn--primary         /* Primary button */
.giftflowwp-modal__btn--secondary       /* Secondary button */
.giftflowwp-modal__btn--danger          /* Danger button */
.giftflowwp-modal__btn--success         /* Success button */
.giftflowwp-modal__input                /* Input field */
.giftflowwp-modal__loading              /* Loading state */
.giftflowwp-modal__error                /* Error message */
.giftflowwp-modal__success              /* Success message */
.giftflowwp-modal__info                 /* Info message */
.giftflowwp-modal__warning              /* Warning message */
```

## Animation Variants

### Fade Animation
```javascript
{
    animation: 'fade',
    duration: 300
}
```

### Slide Animation
```javascript
{
    animation: 'slide',
    duration: 400
}
```

### Zoom Animation
```javascript
{
    animation: 'zoom',
    duration: 300
}
```

## Size Presets

### Small Modal
```javascript
{
    className: 'giftflowwp-modal--small'
}
```

### Medium Modal
```javascript
{
    className: 'giftflowwp-modal--medium'
}
```

### Large Modal
```javascript
{
    className: 'giftflowwp-modal--large'
}
```

### Full Screen Modal
```javascript
{
    className: 'giftflowwp-modal--full'
}
```

## Position Options

### Center (Default)
```javascript
{
    position: 'center'
}
```

### Top
```javascript
{
    position: 'top'
}
```

### Bottom
```javascript
{
    position: 'bottom'
}
```

## Accessibility Features

- **ARIA Labels**: Proper dialog role and labels
- **Focus Trapping**: Tab key navigation within modal
- **Keyboard Support**: Escape key to close
- **Screen Reader**: Semantic HTML structure
- **High Contrast**: Support for high contrast mode
- **Reduced Motion**: Respects user motion preferences

## Browser Support

- **Modern Browsers**: Full feature support
- **IE11+**: Basic functionality with polyfills
- **Mobile Browsers**: Full responsive support
- **Touch Devices**: Optimized touch interactions

## Performance Tips

1. **Destroy unused modals**: Call `destroy()` when done
2. **Use event delegation**: Bind events to modal container
3. **Lazy load content**: Use AJAX for heavy content
4. **Optimize animations**: Use `transform` and `opacity` for smooth performance

## Troubleshooting

### Modal not opening
- Check if modal element exists in DOM
- Verify CSS is loaded
- Check console for JavaScript errors

### AJAX content not loading
- Verify URL is correct
- Check network tab for failed requests
- Ensure server returns proper response

### Animation issues
- Check if CSS transitions are supported
- Verify animation duration is reasonable
- Test with reduced motion preferences

## License

This modal library is part of the GiftFlowWP plugin and follows the same license terms.
