# Template Loading System

The GiftFlow plugin includes a flexible template loading system that allows for theme overrides and customization. This document explains how the template system works and how to use it effectively.

## Overview

The template loading system is implemented in the `Template` class (`includes/frontend/class-template.php`). It provides a robust way to load templates while supporting theme overrides and customization through hooks.

## Core Features

- Default template loading from plugin's `templates/` directory
- Theme override support
- Template part support
- Comprehensive hook system for customization
- Template hierarchy similar to WordPress core

## Template Hierarchy

The system follows this hierarchy when looking for templates:

1. Theme root directory
2. Theme's `giftflow/` subdirectory
3. Plugin's `templates/` directory

## Usage

### Basic Template Loading

```php
// Initialize the template loader
$template_loader = new \GiftFlow\Frontend\Template();

// Load a full template
$template_loader->load_template('payment-form.php', [
    'some_var' => 'value'
]);
```

### Loading Template Parts

```php
// Load a template part
$template_loader->get_template_part('content', 'single', [
    'post' => $post
]);
```

## Theme Override

Theme developers can override any template by placing their version in one of these locations:

1. Theme root directory:
   ```
   wp-content/themes/your-theme/template-name.php
   ```

2. Theme's giftflow subdirectory:
   ```
   wp-content/themes/your-theme/giftflow/template-name.php
   ```

## Available Hooks

### Filters

1. `giftflow_template_path`
   - Filter the template path
   - Parameters:
     - `$template_path` (string): Current template path
     - `$template_name` (string): Template name
   - Example:
     ```php
     add_filter('giftflow_template_path', function($path, $name) {
         return 'custom/path/' . $name;
     }, 10, 2);
     ```

2. `giftflow_template_file`
   - Filter the template file
   - Parameters:
     - `$template_file` (string): Full path to template file
     - `$template_name` (string): Template name
     - `$args` (array): Template arguments
   - Example:
     ```php
     add_filter('giftflow_template_file', function($file, $name, $args) {
         return $file;
     }, 10, 3);
     ```

3. `giftflow_get_template_part`
   - Filter template part location
   - Parameters:
     - `$template` (string): Template file path
     - `$slug` (string): Template slug
     - `$name` (string): Template name
   - Example:
     ```php
     add_filter('giftflow_get_template_part', function($template, $slug, $name) {
         return $template;
     }, 10, 3);
     ```

### Actions

1. `giftflow_before_template_load`
   - Fires before template is loaded
   - Parameters:
     - `$template_name` (string): Template name
     - `$template_file` (string): Full path to template file
     - `$args` (array): Template arguments
   - Example:
     ```php
     add_action('giftflow_before_template_load', function($name, $file, $args) {
         // Do something before template loads
     }, 10, 3);
     ```

2. `giftflow_after_template_load`
   - Fires after template is loaded
   - Parameters:
     - `$template_name` (string): Template name
     - `$template_file` (string): Full path to template file
     - `$args` (array): Template arguments
   - Example:
     ```php
     add_action('giftflow_after_template_load', function($name, $file, $args) {
         // Do something after template loads
     }, 10, 3);
     ```

## Best Practices

1. **Theme Development**
   - Place template overrides in the theme's `giftflow/` subdirectory
   - Use the provided hooks for advanced customization
   - Maintain template structure similar to the plugin's original templates

2. **Plugin Development**
   - Use `load_template()` for full templates
   - Use `get_template_part()` for reusable template parts
   - Pass necessary data through the `$args` parameter
   - Document template requirements and available variables

3. **Performance**
   - Cache template paths when possible
   - Minimize template file checks
   - Use template parts for reusable components

## Example Implementation

### Theme Override Example

To override the payment form template:

1. Create file at:
   ```
   wp-content/themes/your-theme/giftflow/payment-form.php
   ```

2. Customize the template as needed:
   ```php
   <?php
   /**
    * Custom payment form template
    */
   ?>
   <div class="custom-payment-form">
       <!-- Your custom template content -->
   </div>
   ```

### Hook Usage Example

To modify template loading behavior:

```php
// Change template path
add_filter('giftflow_template_path', function($path, $name) {
    if ('payment-form.php' === $name) {
        return 'custom/path/';
    }
    return $path;
}, 10, 2);

// Add custom data before template loads
add_action('giftflow_before_template_load', function($name, $file, $args) {
    if ('payment-form.php' === $name) {
        // Add custom data
        $args['custom_data'] = 'value';
    }
}, 10, 3);
```
