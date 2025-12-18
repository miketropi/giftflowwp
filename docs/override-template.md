# Overriding Templates in Themes

GiftFlow allows theme developers to override any template by placing a file with the same name in their theme directory. This documentation explains how to override templates and the template hierarchy.

## Template Hierarchy

GiftFlow follows WordPress template hierarchy best practices. When looking for a template, the system checks the following locations in order:

1. Theme directory
2. Theme's GiftFlow subdirectory
3. Plugin's template directory

## Available Templates

### Campaign Templates

- `single-campaign.php` - Template for individual campaign pages
- `archive-campaign.php` - Template for campaign archive pages

### Donation Templates

- `donation-form.php` - Template for donation forms
- `donations.php` - Template for displaying donations list

## How to Override Templates

### Method 1: Direct Theme Override

Place the template file directly in your theme's root directory:

```
your-theme/
├── single-campaign.php
└── archive-campaign.php
```

### Method 2: GiftFlow Subdirectory

Place the template file in a GiftFlow subdirectory within your theme:

```
your-theme/
└── giftflow/
    ├── single-campaign.php
    └── archive-campaign.php
```

## Template Structure

When overriding templates, maintain the same basic structure as the original templates:

```php
<?php
/**
 * Template Name: Single Campaign
 * Template Post Type: campaign
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <!-- Your custom template content here -->
    <?php endwhile; ?>
</main>

<?php
get_footer();
```

## Available Template Functions

When overriding templates, you can use these helper functions:

- `giftflow_get_campaign_raised_amount($campaign_id)` - Get total raised amount
- `giftflow_get_campaign_progress_percentage($campaign_id)` - Get progress percentage
- `giftflow_render_currency_formatted_amount($amount)` - Format amount with currency
- `giftflow_get_campaign_status($campaign_id)` - Get campaign status

## Example: Custom Single Campaign Template

Here's an example of a custom single campaign template:

```php
<?php
/**
 * Template Name: Single Campaign
 * Template Post Type: campaign
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('campaign-single'); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                
                <div class="campaign-meta">
                    <?php
                    $goal_amount = get_post_meta(get_the_ID(), '_goal_amount', true);
                    $raised_amount = giftflow_get_campaign_raised_amount(get_the_ID());
                    $progress_percentage = giftflow_get_campaign_progress_percentage(get_the_ID());
                    ?>
                    
                    <div class="campaign-progress">
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo esc_attr($progress_percentage); ?>%"></div>
                        </div>
                        <div class="progress-stats">
                            <span class="raised"><?php echo giftflow_render_currency_formatted_amount($raised_amount); ?></span>
                            <span class="goal"><?php echo sprintf(__('of %s goal', 'giftflow'), giftflow_render_currency_formatted_amount($goal_amount)); ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <div class="campaign-donation-section">
                <?php echo do_shortcode('[giftflow_donation_form campaign_id="' . get_the_ID() . '"]'); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
```

## Best Practices

1. Always maintain the basic template structure
2. Use the provided helper functions for consistent data formatting
3. Keep template overrides in a `giftflow` subdirectory for better organization
4. Document your template overrides
5. Test your overrides after plugin updates

## Troubleshooting

If your template override isn't working:

1. Verify the file is in the correct location
2. Check file permissions
3. Clear any caching plugins
4. Verify the template name matches exactly
5. Check the WordPress debug log for errors

## Support

For additional help with template overrides, please refer to:
- WordPress Template Hierarchy documentation
- GiftFlow support documentation
- WordPress theme development documentation
