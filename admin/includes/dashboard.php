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

// Include dashboard functions
require_once GIFTFLOWWP_PLUGIN_DIR . 'admin/includes/dashboard-functions.php';

/**
 * Register the GiftFlowWP dashboard page
 */
function giftflowwp_register_dashboard_page() {
    $icon_base64 = base64_encode(giftflowwp_svg_icon('plgicon'));
    add_menu_page(
        __('GiftFlow Dashboard', 'giftflowwp'),
        __('GiftFlow', 'giftflowwp'),
        'manage_options',
        'giftflowwp-dashboard',
        'giftflowwp_dashboard_page',
        'data:image/svg+xml;base64,' . $icon_base64,
        // 'dashicons-heart',
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
                <?php esc_html_e('Overview', 'giftflowwp'); ?>
            </a>
            <!-- <a href="<?php echo esc_url(admin_url('admin.php?page=giftflowwp-dashboard&tab=help')); ?>" 
               class="nav-tab <?php echo $current_tab === 'help' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Help', 'giftflowwp'); ?>
            </a> -->
        </nav>
        
        <div class="tab-content">
            <?php
            // Include the appropriate tab content
            switch ($current_tab) {
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
    giftflowwp_load_template('admin/dashboard-view.php');
}

/**
 * Display the help tab content
 */
function giftflowwp_dashboard_help_tab() {
    giftflowwp_load_template('admin/dashboard-helps.php');
}


