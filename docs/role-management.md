# Role Management Documentation

The GiftFlow plugin includes a simple role management system that creates a "Donor" role for easy user distinction. This document explains how to use the Role class effectively.

## Overview

The Role class provides basic functionality to:
- Register a "Donor" role similar to WordPress subscriber role
- Assign/remove donor role from users
- Check if a user has the donor role
- Clean up roles on plugin deactivation

## Class Structure

```php
namespace GiftFlow\Core;

class Role extends Base {
    // Role management methods
}
```

## Available Functions

The Role class provides both class methods and global helper functions for easy access. You can use either approach, but the global functions are more convenient for simple usage.

### Global Functions (Recommended)

#### `giftflow_assign_donor_role($user_id)`

Assigns the donor role to a specific user.

#### Parameters
- `$user_id` (int) - The WordPress user ID

#### Returns
- `bool` - True if role was assigned, false if user already has the role or doesn't exist

#### Example Usage

```php
// Simple function call - no class instantiation needed
$success = giftflow_assign_donor_role(123);

if ($success) {
    echo "Donor role assigned successfully!";
} else {
    echo "Failed to assign donor role or user already has it.";
}
```

#### `giftflow_remove_donor_role($user_id)`

Removes the donor role from a specific user.

#### Parameters
- `$user_id` (int) - The WordPress user ID

#### Returns
- `bool` - True if role was removed, false if user didn't have the role or doesn't exist

#### Example Usage

```php
// Simple function call
$success = giftflow_remove_donor_role(123);

if ($success) {
    echo "Donor role removed successfully!";
} else {
    echo "User didn't have donor role or user doesn't exist.";
}
```

#### `giftflow_user_has_donor_role($user_id)`

Checks if a user has the donor role.

#### Parameters
- `$user_id` (int) - The WordPress user ID

#### Returns
- `bool` - True if user has donor role, false otherwise

#### Example Usage

```php
// Simple function call
if (giftflow_user_has_donor_role(123)) {
    echo "User is a donor!";
} else {
    echo "User is not a donor.";
}
```

### Class Methods (Alternative)

You can also use the class methods directly. The class uses a singleton pattern, so you only need to instantiate it once:

```php
// Singleton approach - recommended for multiple operations
$role_manager = \GiftFlow\Core\Role::get_instance();
$success = $role_manager->assign_donor_role(123);

// Or use the helper function to get the instance
$role_manager = giftflow_get_role_manager();
$success = $role_manager->assign_donor_role(123);
```

### Performance Benefits

The singleton pattern ensures that:
- Only one instance of the Role class exists
- No memory waste from multiple instantiations
- Better performance for multiple operations
- Consistent state across your application

## Role Details

### Donor Role
- **Role Name**: `giftflow_donor`
- **Display Name**: "Donor"
- **Capabilities**: `read` (same as WordPress subscriber)
- **Purpose**: Easy identification of users who are donors

## Usage Patterns

### Pattern 1: Global Functions (Simplest)
Best for single operations or simple checks:

```php
// Single operation
giftflow_assign_donor_role($user_id);

// Simple check
if (giftflow_user_has_donor_role($user_id)) {
    // Do something
}
```

### Pattern 2: Singleton Instance (Most Efficient)
Best for multiple operations or when you need the same instance:

```php
// Get the singleton instance once
$role_manager = \GiftFlow\Core\Role::get_instance();

// Use it multiple times
if ($role_manager->user_has_donor_role($user_id)) {
    $role_manager->assign_donor_role($user_id);
    // More operations...
}
```

### Pattern 3: Helper Function (Convenient)
Alternative way to get the singleton instance:

```php
// Get instance using helper function
$role_manager = giftflow_get_role_manager();

// Use it multiple times
$role_manager->assign_donor_role($user_id);
$role_manager->remove_donor_role($user_id);
```

## Common Use Cases

### 1. Assign Role After Donation

```php
// After a successful donation
function handle_successful_donation($user_id, $donation_data) {
    // Simple function call - no class instantiation needed
    giftflow_assign_donor_role($user_id);
    
    // Send thank you email
    // ... email logic here
}
```

### 2. Check User Role Before Showing Content

```php
// In a template or function
function show_donor_content($user_id) {
    if (giftflow_user_has_donor_role($user_id)) {
        echo "<div class='donor-exclusive-content'>";
        echo "Thank you for being a donor!";
        echo "</div>";
    }
}
```

### 3. Bulk Role Assignment

```php
// Assign donor role to multiple users
function assign_donor_role_to_multiple_users($user_ids) {
    $results = array();
    
    foreach ($user_ids as $user_id) {
        $results[$user_id] = giftflow_assign_donor_role($user_id);
    }
    
    return $results;
}

// Usage
$user_ids = array(123, 456, 789);
$results = assign_donor_role_to_multiple_users($user_ids);
```

### 4. User Role Management in Admin

```php
// Add custom column to users list
add_filter('manage_users_columns', 'add_donor_column');
function add_donor_column($columns) {
    $columns['donor_status'] = 'Donor Status';
    return $columns;
}

add_filter('manage_users_custom_column', 'show_donor_status', 10, 3);
function show_donor_status($value, $column_name, $user_id) {
    if ($column_name === 'donor_status') {
        return giftflow_user_has_donor_role($user_id) ? 'Yes' : 'No';
    }
    return $value;
}
```

## Integration with WordPress

### User Registration Hook

```php
// Assign donor role when user registers
add_action('user_register', 'assign_donor_role_on_registration');
function assign_donor_role_on_registration($user_id) {
    giftflow_assign_donor_role($user_id);
}
```

### Donation Form Integration

```php
// In donation form processing
function process_donation_form($form_data) {
    // Process donation...
    
    // Get user ID (from form or create new user)
    $user_id = get_current_user_id();
    
    if ($user_id) {
        giftflow_assign_donor_role($user_id);
    }
}
```

## Error Handling

### Check if User Exists

```php
function safe_assign_donor_role($user_id) {
    // Check if user exists
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return new WP_Error('user_not_found', 'User does not exist');
    }
    
    $success = giftflow_assign_donor_role($user_id);
    
    if (!$success) {
        return new WP_Error('role_assignment_failed', 'Failed to assign donor role');
    }
    
    return true;
}
```

### Logging Role Changes

```php
function assign_donor_role_with_logging($user_id) {
    // Check if user already has role
    if (giftflow_user_has_donor_role($user_id)) {
        error_log("User {$user_id} already has donor role");
        return false;
    }
    
    $success = giftflow_assign_donor_role($user_id);
    
    if ($success) {
        error_log("Successfully assigned donor role to user {$user_id}");
    } else {
        error_log("Failed to assign donor role to user {$user_id}");
    }
    
    return $success;
}
```

## Best Practices

### 1. Always Check User Existence

```php
// Good
$user = get_user_by('id', $user_id);
if ($user) {
    giftflow_assign_donor_role($user_id);
}

// Bad
giftflow_assign_donor_role($user_id); // No validation
```

### 2. Handle Return Values

```php
// Good
$success = giftflow_assign_donor_role($user_id);
if ($success) {
    // Success logic
} else {
    // Error handling
}

// Bad
giftflow_assign_donor_role($user_id); // Ignoring return value
```

### 3. Choose the Right Approach

```php
// For single operations - use global functions
if (giftflow_user_has_donor_role($user_id)) {
    // Show donor content
}

// For multiple operations - use singleton instance
$role_manager = \GiftFlow\Core\Role::get_instance();
if ($role_manager->user_has_donor_role($user_id)) {
    $role_manager->assign_donor_role($user_id);
    // More operations...
}

// Or use the helper function
$role_manager = giftflow_get_role_manager();
```

## Troubleshooting

### Common Issues

1. **Role not assigned**: Check if user exists and doesn't already have the role
2. **Class not found**: Ensure the class is properly loaded through the plugin loader
3. **Permission errors**: Make sure the current user has permission to modify user roles

### Debug Mode

```php
// Enable debug logging
function debug_role_assignment($user_id) {
    $role_manager = new \GiftFlow\Core\Role();
    
    error_log("Attempting to assign donor role to user: {$user_id}");
    
    $user = get_user_by('id', $user_id);
    if (!$user) {
        error_log("User {$user_id} not found");
        return false;
    }
    
    $has_role = $role_manager->user_has_donor_role($user_id);
    error_log("User {$user_id} currently has donor role: " . ($has_role ? 'Yes' : 'No'));
    
    $success = $role_manager->assign_donor_role($user_id);
    error_log("Role assignment result: " . ($success ? 'Success' : 'Failed'));
    
    return $success;
}
```

## API Reference

### Class: `\GiftFlow\Core\Role`

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `assign_donor_role($user_id)` | `int $user_id` | `bool` | Assigns donor role to user |
| `remove_donor_role($user_id)` | `int $user_id` | `bool` | Removes donor role from user |
| `user_has_donor_role($user_id)` | `int $user_id` | `bool` | Checks if user has donor role |

### Role Details

| Property | Value | Description |
|----------|-------|-------------|
| Role Name | `giftflow_donor` | Internal WordPress role name |
| Display Name | "Donor" | Human-readable role name |
| Capabilities | `read` | Standard WordPress read capability |

## Support

For additional support or questions about the role management system, please refer to the plugin documentation or contact the development team.
