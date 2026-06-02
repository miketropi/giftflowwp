---
name: gf-cpt
description: Use ONLY when creating or modifying a custom post type (CPT) in the GiftFlow WordPress plugin. Follows the Base_Post_Type abstract class pattern. Trigger keywords: "add a CPT", "create post type", "new post type", "register post type", "custom post type".
---

# GiftFlow Custom Post Type

## Pattern Overview

All CPTs inherit from `GiftFlow\Admin\PostTypes\Base_Post_Type` and follow a strict Template Method pattern.

## Steps

1. **Create the class** in `admin/includes/post-types/class-{name}.php`:

```php
<?php
namespace GiftFlow\Admin\PostTypes;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class {Name} extends Base_Post_Type {
    public function __construct() {
        parent::__construct();
    }

    protected function init_post_type() {
        $this->post_type = '{post_type}';

        $this->labels = array(
            'name'          => _x( '{Plural}', 'Post type general name', 'giftflow' ),
            'singular_name' => _x( '{Singular}', 'Post type singular name', 'giftflow' ),
            'menu_name'     => _x( '{Plural}', 'Admin Menu text', 'giftflow' ),
            'add_new'       => __( 'Add New', 'giftflow' ),
            'add_new_item'  => __( 'Add New {Singular}', 'giftflow' ),
            // ... provide ALL label keys
        );

        $this->args = array(
            'labels'      => $this->labels,
            'public'      => true,
            'show_in_menu' => 'giftflow-dashboard', // or boolean/slug
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
            'show_in_rest' => true,
            // ... other register_post_type args
        );

        $this->taxonomies = array(
            array(
                'name' => '{taxonomy-slug}',
                'args' => array(
                    'hierarchical' => true,
                    'labels' => array( /* ... */ ),
                ),
            ),
        );

        // Optional: admin columns
        $this->admin_columns = array( /* ... */ );
        $this->sortable_columns = array( /* ... */ );
    }
}
```

2. **Require the file** in `giftflow.php` inside `giftflow_load_files()`:

```php
require_once GIFTFLOW_PLUGIN_DIR . 'admin/includes/post-types/class-{name}.php';
```

3. **Initialize** in `includes/core/class-loader.php` → `init()` method:

```php
new \GiftFlow\Admin\PostTypes\{Name}();
```

4. **Create meta box class** in `admin/includes/meta-boxes/class-{name}-meta.php` if needed, extending `Base_Meta_Box`.

## Key Rules
- Post type slug: lowercase, underscores, e.g., `event_log`
- Meta keys: prefixed with `_`, e.g., `_my_field`
- All strings use the `'giftflow'` text domain
- Always `exit` after `defined('ABSPATH')` guard
- Override only `init_post_type()` — parent constructor does the rest
- Base_Post_Type auto-hooks into `init` for registration
- Admin columns use `manage_{post_type}_posts_columns` and `manage_{post_type}_posts_custom_column`

## Existing CPTs for Reference
- `admin/includes/post-types/class-campaign.php` — `Campaign` → `campaign`
- `admin/includes/post-types/class-donation.php` — `Donation` → `donation`
- `admin/includes/post-types/class-donor.php` — `Donor` → `donor`
