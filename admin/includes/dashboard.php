<?php
/**
 * GiftFlowWP Dashboard
 *
 * @package GiftFlowWP
 * @subpackage Admin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the GiftFlowWP dashboard page
 */
function giftflowwp_register_dashboard_page() {
    add_menu_page(
        __('GiftFlow Dashboard', 'giftflowwp'),
        __('GiftFlow', 'giftflowwp'),
        'manage_options',
        'giftflowwp-dashboard',
        'giftflowwp_dashboard_page',
        'dashicons-megaphone',
        30
    );

    add_submenu_page(
        'giftflowwp-dashboard',
        __('Dashboard', 'giftflowwp'),
        __('Dashboard', 'giftflowwp'),
        'manage_options',
        'giftflowwp-dashboard',
        'giftflowwp_dashboard_page',
        0
    );
    
}
add_action('admin_menu', 'giftflowwp_register_dashboard_page');

/**
 * Display the GiftFlowWP dashboard page content
 */
function giftflowwp_dashboard_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get the current tab
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'overview';
    
    // Include the header
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <nav class="nav-tab-wrapper">
            <a href="<?php echo esc_url(admin_url('admin.php?page=giftflowwp-dashboard&tab=overview')); ?>" 
               class="nav-tab <?php echo $current_tab === 'overview' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Overview', 'giftflowwp'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=giftflowwp-dashboard&tab=settings')); ?>" 
               class="nav-tab <?php echo $current_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Settings', 'giftflowwp'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=giftflowwp-dashboard&tab=help')); ?>" 
               class="nav-tab <?php echo $current_tab === 'help' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Help', 'giftflowwp'); ?>
            </a>
        </nav>
        
        <div class="tab-content">
            <?php
            // Include the appropriate tab content
            switch ($current_tab) {
                case 'settings':
                    giftflowwp_dashboard_settings_tab();
                    break;
                case 'help':
                    giftflowwp_dashboard_help_tab();
                    break;
                case 'overview':
                default:
                    giftflowwp_dashboard_overview_tab();
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Display the overview tab content
 */
function giftflowwp_dashboard_overview_tab() {
    ?>
    <div class="giftflowwp-dashboard-overview">
        <div class="giftflowwp-dashboard-welcome">
            <h2><?php _e('Welcome to GiftFlowWP', 'giftflowwp'); ?></h2>
            <p><?php _e('Thank you for using GiftFlowWP. A comprehensive WordPress plugin for managing donations, donors, and campaigns with modern features and extensible architecture.', 'giftflowwp'); ?></p>
        </div>
        
        <div class="giftflowwp-dashboard-stats">
            <h3><?php _e('Quick Statistics', 'giftflowwp'); ?></h3>
            <div class="giftflowwp-stats-grid">
                <div class="giftflowwp-stat-box">
                    <h4><?php _e('Total Donations', 'giftflowwp'); ?></h4>
                    <p class="giftflowwp-stat-number"><?php echo esc_html(giftflowwp_get_total_gifts()); ?></p>
                </div>
                <div class="giftflowwp-stat-box">
                    <h4><?php _e('Active Campaigns', 'giftflowwp'); ?></h4>
                    <p class="giftflowwp-stat-number"><?php echo esc_html(giftflowwp_get_active_flows()); ?></p>
                </div>
                <div class="giftflowwp-stat-box">
                    <h4><?php _e('Total Donors', 'giftflowwp'); ?></h4>
                    <p class="giftflowwp-stat-number"><?php echo esc_html(giftflowwp_get_completed_flows()); ?></p>
                </div>
            </div>
        </div>
        
        <div class="giftflowwp-dashboard-recent">
            <h3><?php _e('Recent Activity', 'giftflowwp'); ?></h3>
            <?php giftflowwp_display_recent_activity(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display the settings tab content
 */
function giftflowwp_dashboard_settings_tab() {
    ?>
    <div class="giftflowwp-dashboard-settings">
        <h2><?php _e('GiftFlowWP Settings', 'giftflowwp'); ?></h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('giftflowwp_options');
            do_settings_sections('giftflowwp-dashboard');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Display the help tab content
 */
function giftflowwp_dashboard_help_tab() {
    ?>
    <div class="giftflowwp-dashboard-help">
        <h2><?php _e('GiftFlowWP Help', 'giftflowwp'); ?></h2>
        
        <div class="giftflowwp-help-section">
            <h3><?php _e('Getting Started', 'giftflowwp'); ?></h3>
            <p><?php _e('To get started with GiftFlowWP, follow these steps:', 'giftflowwp'); ?></p>
            <ol>
                <li><?php _e('Configure your basic settings in the Settings tab', 'giftflowwp'); ?></li>
                <li><?php _e('Create your first gift flow', 'giftflowwp'); ?></li>
                <li><?php _e('Add the gift flow shortcode to your pages or posts', 'giftflowwp'); ?></li>
            </ol>
        </div>
        
        <div class="giftflowwp-help-section">
            <h3><?php _e('Shortcodes', 'giftflowwp'); ?></h3>
            <p><?php _e('Use the following shortcodes to display gift flows on your site:', 'giftflowwp'); ?></p>
            <ul>
                <li><code>[giftflowwp id="1"]</code> - <?php _e('Display a specific gift flow by ID', 'giftflowwp'); ?></li>
                <li><code>[giftflowwp_list]</code> - <?php _e('Display a list of all gift flows', 'giftflowwp'); ?></li>
            </ul>
        </div>
        
        <div class="giftflowwp-help-section">
            <h3><?php _e('Need More Help?', 'giftflowwp'); ?></h3>
            <p><?php _e('If you need additional help, please contact our support team or visit our documentation site.', 'giftflowwp'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Helper function to get total gifts count
 * 
 * @return int Total number of gifts
 */
function giftflowwp_get_total_gifts() {
    // This is a placeholder - implement actual logic to count gifts
    return 0;
}

/**
 * Helper function to get active flows count
 * 
 * @return int Total number of active flows
 */
function giftflowwp_get_active_flows() {
    // This is a placeholder - implement actual logic to count active flows
    return 0;
}

/**
 * Helper function to get completed flows count
 * 
 * @return int Total number of completed flows
 */
function giftflowwp_get_completed_flows() {
    // This is a placeholder - implement actual logic to count completed flows
    return 0;
}

/**
 * Display recent activity
 */
function giftflowwp_display_recent_activity() {
    // This is a placeholder - implement actual logic to display recent activity
    echo '<p>' . __('No recent activity to display.', 'giftflowwp') . '</p>';
}


