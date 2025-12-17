<?php
/**
 * GiftFlow Dashboard
 *
 * @package GiftFlow
 * @subpackage Admin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include dashboard functions
require_once GIFTFLOW_PLUGIN_DIR . 'admin/includes/dashboard-functions.php';

/**
 * Register the GiftFlow dashboard page
 */
function giftflow_register_dashboard_page() {
    $icon_base64 = base64_encode(giftflow_svg_icon('plgicon'));
    add_menu_page(
        __('GiftFlow Dashboard', 'giftflow'),
        __('GiftFlow', 'giftflow'),
        'manage_options',
        'giftflow-dashboard',
        'giftflow_dashboard_page',
        'data:image/svg+xml;base64,' . $icon_base64,
        // 'dashicons-heart',
        30
    );

    add_submenu_page(
        'giftflow-dashboard',
        __('Dashboard', 'giftflow'),
        __('Dashboard', 'giftflow'),
        'manage_options',
        'giftflow-dashboard',
        'giftflow_dashboard_page',
        0
    );
    
}
add_action('admin_menu', 'giftflow_register_dashboard_page');

/**
 * Display the GiftFlow dashboard page content
 */
function giftflow_dashboard_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get the current tab
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $current_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'overview';
    
    // Include the header
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <nav class="nav-tab-wrapper">
            <a href="<?php echo esc_url(admin_url('admin.php?page=giftflow-dashboard&tab=overview')); ?>" 
               class="nav-tab <?php echo $current_tab === 'overview' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Overview', 'giftflow'); ?>
            </a>
            <!-- <a href="<?php echo esc_url(admin_url('admin.php?page=giftflow-dashboard&tab=help')); ?>" 
               class="nav-tab <?php echo $current_tab === 'help' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Help', 'giftflow'); ?>
            </a> -->
        </nav>
        
        <div class="tab-content">
            <?php
            // Include the appropriate tab content
            switch ($current_tab) {
                case 'help':
                    giftflow_dashboard_help_tab();
                    break;
                case 'overview':
                default:
                    giftflow_dashboard_overview_tab();
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
function giftflow_dashboard_overview_tab() {
    giftflow_load_template('admin/dashboard-view.php');
}

/**
 * Display the help tab content
 */
function giftflow_dashboard_help_tab() {
    giftflow_load_template('admin/dashboard-helps.php');
}


