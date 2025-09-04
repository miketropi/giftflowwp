<?php 
use GiftFlowWP\Frontend\Template;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
/**
 * Common functions for the plugin
 * 
 * @package GiftFlowWP
 */

function giftflowwp_svg_icon($name) {
  $icons = array(
    'plgicon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles-icon lucide-sparkles"><path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/><path d="M20 2v4"/><path d="M22 4h-4"/><circle cx="4" cy="20" r="2"/></svg>',
    'info' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
    'mail' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
    'eye-off' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>',
    'loop' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-repeat-icon lucide-repeat"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>',
    'switch' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-toggle-left-icon lucide-toggle-left"><rect width="20" height="12" x="2" y="6" rx="6" ry="6"/><circle cx="8" cy="12" r="2"/></svg>',
    'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>',
    'credit-card' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
    'bank' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>',
    'paypal' => '<svg width="20px" height="20px" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M33.0312 28C39 28 43 25.5 43 20C43 14.5 39 12 33.0312 12H22L17 43H26L28 28H33.0312Z" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/> <path d="M18 36H10L15 5H26.0312C32 5 36 7.5 36 13C36 18.5 32 21 26.0312 21H21" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
    'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
    'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>',
    'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
    'shield' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>',
    'phone' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
    'message' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
    'shield-check' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>',
    'error' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
    'next' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>',
    'prev' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>',
    'checkmark-circle' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>',
    'error-circle' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x-icon lucide-circle-x"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>',
    'refresh' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw-icon lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>',
    'help' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-life-buoy-icon lucide-life-buoy"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 4.24 4.24"/><path d="m14.83 9.17 4.24-4.24"/><path d="m14.83 14.83 4.24 4.24"/><path d="m9.17 14.83-4.24 4.24"/><circle cx="12" cy="12" r="4"/></svg>',
    'circle-alert' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>'
  );

  return isset($icons[$name]) ? $icons[$name] : '';
}

/**
 * Get the raised amount for a campaign
 *
 * @param int $campaign_id The campaign ID
 * @return float The raised amount
 */
function giftflowwp_get_campaign_raised_amount($campaign_id) {
    // Get all donations for this campaign
    $donations = get_posts(array(
        'post_type' => 'donation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_campaign_id',
                'value' => $campaign_id,
                'compare' => '='
            ),
            array(
                'key' => '_status',
                'value' => 'completed',
                'compare' => '='
            )
        )
    ));

    $total_amount = 0;

    // Sum up all completed donations
    foreach ($donations as $donation) {
        $amount = get_post_meta($donation->ID, '_amount', true);
        if ($amount) {
            $total_amount += floatval($amount);
        }
    }

    return $total_amount;
}

/**
 * giftflowwp_get_campaign_goal_amount
 * 
 * @param int $campaign_id The campaign ID
 * @return float The goal amount
 */
function giftflowwp_get_campaign_goal_amount($campaign_id) {
    return get_post_meta($campaign_id, '_goal_amount', true);
}

/**
 * Get the percentage of raised amount compared to goal amount
 *
 * @param int $campaign_id The campaign ID
 * @return float The percentage (0-100)
 */
function giftflowwp_get_campaign_progress_percentage($campaign_id) {
    $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);
    $goal_amount = get_post_meta($campaign_id, '_goal_amount', true);
    
    if (!$goal_amount || floatval($goal_amount) <= 0) {
        return 0;
    }
    
    $percentage = ($raised_amount / floatval($goal_amount)) * 100;
    
    // Ensure percentage is between 0 and 100
    return min(100, max(0, round($percentage, 2)));
}

/**
 * Display the campaign progress percentage
 *
 * @param int $campaign_id The campaign ID
 * @return string HTML formatted progress percentage
 */
function giftflowwp_display_campaign_progress($campaign_id) {
    $percentage = giftflowwp_get_campaign_progress_percentage($campaign_id);
    $raised_amount = giftflowwp_get_campaign_raised_amount($campaign_id);
    $goal_amount = get_post_meta($campaign_id, '_goal_amount', true);
    
    $html = sprintf(
        '<div class="campaign-progress">
            <div class="progress-bar">
                <div class="progress" style="width: %s%%"></div>
            </div>
            <div class="progress-stats">
                <span class="raised">%s</span> / <span class="goal">%s</span> (%s%%)
            </div>
        </div>',
        esc_attr($percentage),
        esc_html(number_format($raised_amount, 2)),
        esc_html(number_format($goal_amount, 2)),
        esc_html($percentage)
    );
    
    return $html;
}

function giftflowwp_get_common_currency() {
  $currencies = [
    ['code' => 'AFN', 'countries' => ['Afghanistan'], 'name' => 'Afghanistan Afghani', 'symbol' => '&#1547;'],
    ['code' => 'ARS', 'countries' => ['Argentina'], 'name' => 'Argentine Peso', 'symbol' => '&#36;'],
    ['code' => 'AWG', 'countries' => ['Aruba'], 'name' => 'Aruban florin', 'symbol' => '&#402;'],
    ['code' => 'AUD', 'countries' => ['Australia'], 'name' => 'Australian Dollar', 'symbol' => '&#65;&#36;'],
    ['code' => 'AZN', 'countries' => ['Azerbaijan'], 'name' => 'Azerbaijani Manat', 'symbol' => '&#8380;'],
    ['code' => 'BSD', 'countries' => ['The Bahamas'], 'name' => 'Bahamas Dollar', 'symbol' => '&#66;&#36;'],
    ['code' => 'BBD', 'countries' => ['Barbados'], 'name' => 'Barbados Dollar', 'symbol' => '&#66;&#100;&#115;&#36;'],
    ['code' => 'BDT', 'countries' => ['People\'s Republic of Bangladesh'], 'name' => 'Bangladeshi taka', 'symbol' => '&#2547;'],
    ['code' => 'BYN', 'countries' => ['Belarus'], 'name' => 'Belarus Ruble', 'symbol' => '&#66;&#114;'],
    ['code' => 'BZD', 'countries' => ['Belize'], 'name' => 'Belize Dollar', 'symbol' => '&#66;&#90;&#36;'],
    ['code' => 'BMD', 'countries' => ['British Overseas Territory of Bermuda'], 'name' => 'Bermudian Dollar', 'symbol' => '&#66;&#68;&#36;'],
    ['code' => 'BOP', 'countries' => ['Bolivia'], 'name' => 'Boliviano', 'symbol' => '&#66;&#115;'],
    ['code' => 'BAM', 'countries' => ['Bosnia', 'Herzegovina'], 'name' => 'Bosnia-Herzegovina Convertible Marka', 'symbol' => '&#75;&#77;'],
    ['code' => 'BWP', 'countries' => ['Botswana'], 'name' => 'Botswana pula', 'symbol' => '&#80;'],
    ['code' => 'BGN', 'countries' => ['Bulgaria'], 'name' => 'Bulgarian lev', 'symbol' => '&#1083;&#1074;'],
    ['code' => 'BRL', 'countries' => ['Brazil'], 'name' => 'Brazilian real', 'symbol' => '&#82;&#36;'],
    ['code' => 'BND', 'countries' => ['Sultanate of Brunei'], 'name' => 'Brunei dollar', 'symbol' => '&#66;&#36;'],
    ['code' => 'KHR', 'countries' => ['Cambodia'], 'name' => 'Cambodian riel', 'symbol' => '&#6107;'],
    ['code' => 'CAD', 'countries' => ['Canada'], 'name' => 'Canadian dollar', 'symbol' => '&#67;&#36;'],
    ['code' => 'KYD', 'countries' => ['Cayman Islands'], 'name' => 'Cayman Islands dollar', 'symbol' => '&#36;'],
    ['code' => 'CLP', 'countries' => ['Chile'], 'name' => 'Chilean peso', 'symbol' => '&#36;'],
    ['code' => 'CNY', 'countries' => ['China'], 'name' => 'Chinese Yuan Renminbi', 'symbol' => '&#165;'],
    ['code' => 'COP', 'countries' => ['Colombia'], 'name' => 'Colombian peso', 'symbol' => '&#36;'],
    ['code' => 'CRC', 'countries' => ['Costa Rica'], 'name' => 'Costa Rican colón', 'symbol' => '&#8353;'],
    ['code' => 'HRK', 'countries' => ['Croatia'], 'name' => 'Croatian kuna', 'symbol' => '&#107;&#110;'],
    ['code' => 'CUP', 'countries' => ['Cuba'], 'name' => 'Cuban peso', 'symbol' => '&#8369;'],
    ['code' => 'CZK', 'countries' => ['Czech Republic'], 'name' => 'Czech koruna', 'symbol' => '&#75;&#269;'],
    ['code' => 'DKK', 'countries' => ['Denmark', 'Greenland', 'The Faroe Islands'], 'name' => 'Danish krone', 'symbol' => '&#107;&#114;'],
    ['code' => 'DOP', 'countries' => ['Dominican Republic'], 'name' => 'Dominican peso', 'symbol' => '&#82;&#68;&#36;'],
    ['code' => 'XCD', 'countries' => ['Antigua and Barbuda', 'Commonwealth of Dominica', 'Grenada', 'Montserrat', 'St. Kitts and Nevis', 'Saint Lucia and St. Vincent', 'The Grenadines'], 'name' => 'Eastern Caribbean dollar', 'symbol'=> '&#36;'],
    ['code' => 'EGP', 'countries' => ['Egypt'], 'name' => 'Egyptian pound', 'symbol' => '&#163;'],
    ['code' => 'SVC', 'countries' => ['El Salvador'], 'name' => 'Salvadoran colón', 'symbol' => '&#36;'],
    ['code' => 'EEK', 'countries' => ['Estonia'], 'name' => 'Estonian kroon', 'symbol' => '&#75;&#114;'],
    ['code' => 'EUR', 'countries' => ['European Union', 'Italy', 'Belgium', 'Bulgaria', 'Croatia', 'Cyprus', 'Czechia', 'Denmark', 'Estonia', 'Finland', 'France', 'Germany', 'Greece', 'Hungary', 'Ireland', 'Latvia', 'Lithuania', 'Luxembourg', 'Malta', 'Netherlands', 'Poland', 'Portugal', 'Romania', 'Slovakia', 'Slovenia', 'Spain', 'Sweden'], 'name' => 'Euro', 'symbol' => '&#8364;'],
    ['code' => 'FKP', 'countries' => ['Falkland Islands'], 'name' => 'Falkland Islands (Malvinas) Pound', 'symbol' => '&#70;&#75;&#163;'],
    ['code' => 'FJD', 'countries' => ['Fiji'], 'name' => 'Fijian dollar', 'symbol' => '&#70;&#74;&#36;'],
    ['code' => 'GHC', 'countries' => ['Ghana'], 'name' => 'Ghanaian cedi', 'symbol'=> '&#71;&#72;&#162;'],
    ['code' => 'GIP', 'countries' => ['Gibraltar'], 'name' => 'Gibraltar pound', 'symbol' => '&#163;'],
    ['code' => 'GTQ', 'countries' => ['Guatemala'], 'name' => 'Guatemalan quetzal', 'symbol' => '&#81;'],
    ['code' => 'GGP', 'countries' => ['Guernsey'], 'name' => 'Guernsey pound', 'symbol' => '&#81;'],
    ['code' => 'GYD', 'countries' => ['Guyana'], 'name' => 'Guyanese dollar', 'symbol' => '&#71;&#89;&#36;'],
    ['code' => 'HNL', 'countries' => ['Honduras'], 'name' => 'Honduran lempira', 'symbol' => '&#76;'],
    ['code' => 'HKD', 'countries' => ['Hong Kong'], 'name' => 'Hong Kong dollar', 'symbol' => '&#72;&#75;&#36;'],
    ['code' => 'HUF', 'countries' => ['Hungary'], 'name' => 'Hungarian forint', 'symbol' => '&#70;&#116;'],
    ['code' => 'ISK', 'countries' => ['Iceland'], 'name' => 'Icelandic króna', 'symbol' => '&#237;&#107;&#114;'],
    ['code' => 'INR', 'countries' => ['India'], 'name' => 'Indian rupee', 'symbol' => '&#8377;'],
    ['code' => 'IDR', 'countries' => ['Indonesia'], 'name' => 'Indonesian rupiah', 'symbol' => '&#82;&#112;'],
    ['code' => 'IRR', 'countries' => ['Iran'], 'name' => 'Iranian rial', 'symbol' => '&#65020;'],
    ['code' => 'IMP', 'countries' => ['Isle of Man'], 'name' => 'Manx pound', 'symbol' => '&#163;'],
    ['code' => 'ILS', 'countries' => ['Israel', 'Palestinian territories of the West Bank', 'The Gaza Strip'], 'name' => 'Israeli Shekel', 'symbol' => '&#8362;'],
    ['code' => 'JMD', 'countries' => ['Jamaica'], 'name' => 'Jamaican dollar', 'symbol' => '&#74;&#36;'],
    ['code' => 'JPY', 'countries' => ['Japan'], 'name' => 'Japanese yen', 'symbol' => '&#165;'],
    ['code' => 'JEP', 'countries' => ['Jersey'], 'name' => 'Jersey pound', 'symbol' => '&#163;'],
    ['code' => 'KZT', 'countries' => ['Kazakhstan'], 'name' => 'Kazakhstani tenge', 'symbol' => '&#8376;'],
    ['code' => 'KPW', 'countries' => ['North Korea'], 'name' => 'North Korean won', 'symbol' => '&#8361;'],
    ['code' => 'KPW', 'countries' => ['South Korea'], 'name' => 'South Korean won', 'symbol' => '&#8361;'],
    ['code' => 'KGS', 'countries' => ['Kyrgyz Republic'], 'name' => 'Kyrgyzstani som', 'symbol' => '&#1083;&#1074;'],
    ['code' => 'LAK', 'countries' => ['Laos'], 'name' => 'Lao kip', 'symbol' => '&#8365;'],
    ['code' => 'LAK', 'countries' => ['Laos'], 'name' => 'Latvian lats', 'symbol' => '&#8364;'],
    ['code' => 'LVL', 'countries' => ['Laos'], 'name' => 'Latvian lats', 'symbol' => '&#8364;'],
    ['code' => 'LBP', 'countries' => ['Lebanon'], 'name' => 'Lebanese pound', 'symbol' => '&#76;&#163;'],
    ['code' => 'LRD', 'countries' => ['Liberia'], 'name' => 'Liberian dollar', 'symbol' => '&#76;&#68;&#36;'],
    ['code' => 'LTL', 'countries' => ['Lithuania'], 'name' => 'Lithuanian litas', 'symbol' => '&#8364;'],
    ['code' => 'MKD', 'countries' => ['North Macedonia'], 'name' => 'Macedonian denar', 'symbol' => '&#1076;&#1077;&#1085;'],
    ['code' => 'MYR', 'countries' => ['Malaysia'], 'name' => 'Malaysian ringgit', 'symbol' => '&#82;&#77;'],
    ['code' => 'MUR', 'countries' => ['Mauritius'], 'name' => 'Mauritian rupee', 'symbol' => '&#82;&#115;'],
    ['code' => 'MXN', 'countries' => ['Mexico'], 'name' => 'Mexican peso', 'symbol' => '&#77;&#101;&#120;&#36;'],
    ['code' => 'MNT', 'countries' => ['Mongolia'], 'name' => 'Mongolian tögrög', 'symbol' => '&#8366;'],
    ['code' => 'MZN', 'countries' => ['Mozambique'], 'name' => 'Mozambican metical', 'symbol' => '&#77;&#84;'],
    ['code' => 'NAD', 'countries' => ['Namibia'], 'name' => 'Namibian dollar', 'symbol' => '&#78;&#36;'],
    ['code' => 'NPR', 'countries' => ['Federal Democratic Republic of Nepal'], 'name' => 'Nepalese rupee', 'symbol'=> '&#82;&#115;&#46;'],
    ['code' => 'ANG', 'countries' => ['Curaçao', 'Sint Maarten'], 'name' => 'Netherlands Antillean guilder', 'symbol' => '&#402;'],
    ['code' => 'NZD', 'countries' => ['New Zealand', 'The Cook Islands', 'Niue', 'The Ross Dependency', 'Tokelau', 'The Pitcairn Islands'], 'name' => 'New Zealand Dollar', 'symbol' => '&#36;'],
    ['code' => 'NIO', 'countries' => ['Nicaragua'], 'name' => 'Nicaraguan córdoba', 'symbol' => '&#67;&#36;'],
    ['code' => 'NGN', 'countries' => ['Nigeria'], 'name'=> 'Nigerian Naira', 'symbol' => '&#8358;'],
    ['code' => 'NOK', 'countries' => ['Norway and its dependent territories'], 'name' => 'Norwegian krone', 'symbol'=> '&#107;&#114;'],
    ['code' => 'OMR', 'countries' => ['Oman'], 'name' => 'Omani rial', 'symbol' => '&#65020;'],
    ['code' => 'PKR', 'countries' => ['Pakistan'], 'name' => 'Pakistani rupee', 'symbol' => '&#82;&#115;'],
    ['code' => 'PAB', 'countries' => ['Panama'], 'name' => 'Panamanian balboa', 'symbol' => '&#66;&#47;&#46;'],
    ['code' => 'PYG', 'countries' => ['Paraguay'], 'name' => 'Paraguayan Guaraní', 'symbol' => '&#8370;'],
    ['code' => 'PEN', 'countries' => ['Peru'], 'name' => 'Sol', 'symbol' => '&#83;&#47;&#46;'],
    ['code' => 'PHP', 'countries' => ['Philippines'], 'name' => 'Philippine peso', 'symbol' => '&#8369;'],
    ['code' => 'PLN', 'countries' => ['Poland'], 'name' => 'Polish złoty', 'symbol' => '&#122;&#322;'],
    ['code' => 'QAR', 'countries' => ['State of Qatar'], 'name' => 'Qatari Riyal', 'symbol' => '&#65020;'],
    ['code' => 'RON', 'countries' => ['Romania'], 'name' => 'Romanian leu (Leu românesc)', 'symbol' => '&#76;'],
    ['code' => 'RUB', 'countries' => ['Russian Federation', 'Abkhazia and South Ossetia', 'Donetsk and Luhansk'], 'name' => 'Russian ruble', 'symbol' => '&#8381;'],
    ['code' => 'SHP', 'countries' => ['Saint Helena', 'Ascension', 'Tristan da Cunha'], 'name' => 'Saint Helena pound', 'symbol' => '&#163;'],
    ['code' => 'SAR', 'countries' => ['Saudi Arabia'], 'name' => 'Saudi riyal', 'symbol' => '&#65020;'],
    ['code' => 'RSD', 'countries' => ['Serbia'], 'name' => 'Serbian dinar', 'symbol' => '&#100;&#105;&#110;'],
    ['code' => 'SCR', 'countries' => ['Seychelles'], 'name' => 'Seychellois rupee', 'symbol' => '&#82;&#115;'],
    ['code' => 'SGD', 'countries' => ['Singapore'], 'name' => 'Singapore dollar', 'symbol' => '&#83;&#36;'],
    ['code' => 'SBD', 'countries' => ['Solomon Islands'], 'name' => 'Solomon Islands dollar', 'symbol' => '&#83;&#73;&#36;'],
    ['code' => 'SOS', 'countries' => ['Somalia'], 'name' => 'Somali shilling', 'symbol' => '&#83;&#104;&#46;&#83;&#111;'],
    ['code' => 'ZAR', 'countries' => ['South Africa'], 'name' => 'South African rand', 'symbol' => '&#82;'],
    ['code' => 'LKR', 'countries' => ['Sri Lanka'], 'name' => 'Sri Lankan rupee', 'symbol' => '&#82;&#115;'],
    ['code' => 'SEK', 'countries' => ['Sweden'], 'name' => 'Swedish krona', 'symbol' => '&#107;&#114;'],
    ['code' => 'CHF', 'countries' => ['Switzerland'], 'name' => 'Swiss franc', 'symbol' => '&#67;&#72;&#102;'],
    ['code' => 'SRD', 'countries' => ['Suriname'], 'name' => 'Suriname Dollar', 'symbol' => '&#83;&#114;&#36;'],
    ['code' => 'SYP', 'countries' => ['Syria'], 'name' => 'Syrian pound', 'symbol' => '&#163;&#83;'],
    ['code' => 'TWD', 'countries' => ['Taiwan'], 'name' => 'New Taiwan dollar', 'symbol' => '&#78;&#84;&#36;'],
    ['code' => 'THB', 'countries' => ['Thailand'], 'name' => 'Thai baht', 'symbol' => '&#3647;'],
    ['code' => 'TTD', 'countries' => ['Trinidad', 'Tobago'], 'name' => 'Trinidad and Tobago dollar', 'symbol' => '&#84;&#84;&#36;'],
    ['code' => 'TRY', 'countries' => ['Turkey', 'Turkish Republic of Northern Cyprus'], 'name' => 'Turkey Lira', 'symbol' => '&#8378;'],
    ['code' => 'TVD', 'countries' => ['Tuvalu'], 'name' => 'Tuvaluan dollar', 'symbol' => '&#84;&#86;&#36;'],
    ['code' => 'UAH', 'countries' => ['Ukraine'], 'name' => 'Ukrainian hryvnia', 'symbol' => '&#8372;'],
    ['code' => 'GBP', 'countries' => ['United Kingdom', 'Jersey', 'Guernsey', 'The Isle of Man', 'Gibraltar', 'South Georgia', 'The South Sandwich Islands', 'The British Antarctic', 'Territory', 'Tristan da Cunha'], 'name' => 'Pound sterling', 'symbol' => '&#163;'],
    ['code' => 'UGX', 'countries' => ['Uganda'], 'name' => 'Ugandan shilling', 'symbol' => '&#85;&#83;&#104;'],
    ['code' => 'USD', 'countries' => ['United States'], 'name' => 'United States dollar', 'symbol' => '&#36;'],
    ['code' => 'UYU', 'countries' => ['Uruguayan'], 'name' => 'Peso Uruguayolar', 'symbol' => '&#36;&#85;'],
    ['code' => 'UZS', 'countries' => ['Uzbekistan'], 'name' => 'Uzbekistani soʻm', 'symbol' => '&#1083;&#1074;'],
    ['code' => 'VEF', 'countries' => ['Venezuela'], 'name' => 'Venezuelan bolívar', 'symbol' => '&#66;&#115;'],
    ['code' => 'VND', 'countries' => ['Vietnam'], 'name' => 'Vietnamese dong (Đồng)', 'symbol' => '&#8363;'],
    ['code' => 'YER', 'countries' => ['Yemen'], 'name' => 'Yemeni rial', 'symbol' => '&#65020;'],
    ['code' => 'ZWD', 'countries' => ['Zimbabwe'], 'name' => 'Zimbabwean dollar', 'symbol' => '&#90;&#36;'],
  ];

  // apply filter to the currencies
  $currencies = apply_filters('giftflowwp_common_currencies', $currencies);

  return $currencies;
}

// get currency current 
function giftflowwp_get_current_currency() {
  $options = get_option('giftflowwp_general_options');
  $currency = isset($options['currency']) ? $options['currency'] : 'USD';
  return $currency;
}

// get symbol of currency
function giftflowwp_get_currency_symbol($currency) {
  $currencies = giftflowwp_get_common_currency();
  $_currency = array_filter($currencies, function($c) use ($currency) {
    return $c['code'] === $currency;
  });
  $_currency = array_values($_currency);
  return $_currency[0]['symbol'];
}

// get name of currency
function giftflowwp_get_currency_name($currency) {
  $currencies = giftflowwp_get_common_currency();
  $_currency = array_filter($currencies, function($c) use ($currency) {
    return $c['code'] === $currency;
  });
  $_currency = array_values($_currency);
  return $_currency[0]['name'];
}

/**
 * render currency formatted amount
 * 
 * @param float $amount
 * @param float $decimals
 * @param string $currency
 * @param string $template // default: {{currency_symbol}} {{amount}}
 * @return string
 */
function giftflowwp_render_currency_formatted_amount($amount, $decimals = 2, $currency = null, $template = '') {
  
  if (!$currency) {
    $currency = giftflowwp_get_current_currency();
  }
  $currency_symbol = giftflowwp_get_currency_symbol($currency);
  $amount = number_format($amount, $decimals);
  
  // replace array map 
  $replace = array(
    '{{currency_symbol}}' => $currency_symbol,
    '{{amount}}' => $amount,
  );

  if (!$template) {
    $template = giftflowwp_get_currency_template();
  }

  $amount = '<span class="giftflowwp-currency-formatted-amount">' . str_replace(array_keys($replace), array_values($replace), $template) . '</span>';

  $amount = apply_filters('giftflowwp_render_currency_formatted_amount', $amount, $currency, $decimals);
  return $amount;
}

// get symbol
function giftflowwp_get_global_currency_symbol() {
  $currency = giftflowwp_get_current_currency();
  $currencies = giftflowwp_get_common_currency();
  $_currency = array_filter($currencies, function($c) use ($currency) {
    return $c['code'] === $currency;
  });
  $_currency = array_values($_currency);
  return $_currency[0]['symbol'];
}

// get currency template
function giftflowwp_get_currency_template() {
  $options = get_option('giftflowwp_general_options');
  $currency_template = isset($options['currency_template']) ? $options['currency_template'] : '{{currency_symbol}}{{amount}}';
  return $currency_template;
}

function giftflowwp_get_currency_js_format_template() {
  $temp = giftflowwp_get_currency_template();
  $symbol = giftflowwp_get_global_currency_symbol();
  $template = str_replace('{{currency_symbol}}', $symbol, $temp);
  $template = str_replace('{{amount}}', '{{value}}', $template);
  return $template;
}

function giftflowwp_get_preset_donation_amounts() {
  $options = get_option('giftflowwp_general_options');
  $preset_donation_amounts = isset($options['preset_donation_amounts']) ? $options['preset_donation_amounts'] : '10, 25, 35';
  return $preset_donation_amounts;
}

/**
 * get preset donation amounts by campaign id
 * 
 * @param int $campaign_id
 * @return array
 */
function giftflowwp_get_preset_donation_amounts_by_campaign($campaign_id) {
  $preset_donation_amounts = get_post_meta($campaign_id, '_preset_donation_amounts', true);

  // unserialize if exists
  if (is_serialized($preset_donation_amounts)) {
    $preset_donation_amounts = unserialize($preset_donation_amounts);
  }
  

  return array_map(function($item) {
    return array(
      'amount' => (float)trim($item['amount']),
    );
  }, $preset_donation_amounts);
}

function giftflowwp_get_campaign_days_left($campaign_id) {
  $start_date = get_post_meta($campaign_id, '_start_date', true);
  $end_date = get_post_meta($campaign_id, '_end_date', true);
  
  if (!$start_date) {
    return 0;
  }
  

  // current date
  $current_date = current_time('timestamp');

  $start_date = strtotime($start_date);
  $end_date = strtotime($end_date);

  // if start date is in the future, return false
  if ($start_date > $current_date) {
    return false;
  }

  // if end date empty, return ''
  if (!$end_date) {
    return '';
  }

  
  // if end date is in the past, return true
  if ($end_date < $current_date) {
    return true;
  }

  $days_left = ceil(($end_date - $current_date) / 86400);

  // apply filter
  $days_left = apply_filters('giftflowwp_get_campaign_days_left', $days_left, $campaign_id);
  
  return $days_left;
}

// get all donations for the campaign id  
function giftflowwp_get_campaign_donations($campaign_id, $args = array(), $paged = 1) {

  $args = wp_parse_args($args, array(
    'posts_per_page' => apply_filters('giftflowwp_campaign_donations_per_page', 20),
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
    'post_type' => 'donation',
  ));

  $args['meta_key'] = '_campaign_id';
  $args['meta_value'] = $campaign_id;

  $donations = new WP_Query($args);

  // return donation posts, total, and pagination
  return array(
    'posts' => array_map(function($post) {
      $is_anonymous = get_post_meta($post->ID, '_anonymous_donation', true);

      $donor_meta = [];
      $donor_meta['id'] = get_post_meta($post->ID, '_donor_id', true);
      $donor_meta['name'] = get_the_title($donor_meta['id']);
      $donor_meta['email'] = get_post_meta($donor_meta['id'], '_email', true);
      $donor_meta['phone'] = get_post_meta($donor_meta['id'], '_phone', true);
      $donor_meta['address'] = get_post_meta($donor_meta['id'], '_address', true);
      $donor_meta['city'] = get_post_meta($donor_meta['id'], '_city', true);
      $donor_meta['state'] = get_post_meta($donor_meta['id'], '_state', true);
      $donor_meta['postal_code'] = get_post_meta($donor_meta['id'], '_postal_code', true);
      $donor_meta['country'] = get_post_meta($donor_meta['id'], '_country', true);

      if ($is_anonymous == 'yes') {
        $donor_meta['name'] = __('Anonymous 🍀', 'giftflowwp');
        $donor_meta['email'] = '';
        $donor_meta['phone'] = '';
        $donor_meta['address'] = '';
        $donor_meta['city'] = '';
        $donor_meta['state'] = '';
        $donor_meta['postal_code'] = '';
        $donor_meta['country'] = '';
      }

      return array(
        'id' => $post->ID,
        'amount' => get_post_meta($post->ID, '_amount', true),
        'amount_formatted' => giftflowwp_render_currency_formatted_amount(get_post_meta($post->ID, '_amount', true)),
        'payment_method' => get_post_meta($post->ID, '_payment_method', true),
        'status' => get_post_meta($post->ID, '_status', true),
        'transaction_id' => get_post_meta($post->ID, '_transaction_id', true),
        'donor_id' => get_post_meta($post->ID, '_donor_id', true),
        'donor_meta' => $donor_meta,
        'campaign_id' => get_post_meta($post->ID, '_campaign_id', true),
        'message' => get_post_meta($post->ID, '_donor_message', true),
        // anonymous
        'is_anonymous' => get_post_meta($post->ID, '_anonymous_donation', true),
        'date' => get_the_date('', $post->ID),
        'date_gmt' => get_gmt_from_date(get_the_date('Y-m-d H:i:s', $post->ID))
      );
    }, $donations->posts),
    'total' => $donations->found_posts,
    'pagination' => $donations->max_num_pages,
  );
}

function giftflowwp_stripe_payment_method_callback($method) {
  $icons = array(
		'error' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
    'checked' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check-icon lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>',
  );

  ?>
  <label class="donation-form__payment-method">
      <input type="radio" checked name="payment_method" value="<?php echo esc_attr($method['name']); ?>" required>
      <span class="donation-form__payment-method-content">
          <?php echo $method['icon']; ?>
          <span class="donation-form__payment-method-title"><?php echo esc_html($method['label']); ?></span>
      </span>
  </label>
  <div 
    class="donation-form__payment-method-description donation-form__payment-method-description--stripe donation-form__fields" 
    >
      <div class="donation-form__payment-notification">
        <?php echo $icons['checked']; ?>
        <p><?php _e('We use Stripe to process payments. Your payment information is encrypted and never stored on our servers.', 'giftflowwp'); ?></p>
      </div>

      <?php // name on card field ?>
      <div class="donation-form__field">
        <label for="card_name" class="donation-form__field-label"><?php _e('Name on card', 'giftflowwp'); ?></label>
        <input type="text" id="card_name" name="card_name" class="donation-form__field-input" required data-validate="required">

        <div class="donation-form__field-error custom-error-message">
          <?php echo $icons['error']; ?>
          <span class="custom-error-message-text">
            <?php _e('Name on card is required', 'giftflowwp'); ?>
          </span>
        </div>
      </div>
      
      <?php // card element ?>
      <div 
        class="donation-form__field" 
        data-custom-validate="true" 
        data-custom-validate-status="false" >
        <label for="card_number" class="donation-form__field-label"><?php _e('Card number', 'giftflowwp'); ?></label>
        <div id="STRIPE-CARD-ELEMENT"></div> <?php // Render card via stripe.js ?>

        <div class="donation-form__field-error custom-error-message">
          <?php echo $icons['error']; ?>
          <span class="custom-error-message-text">
            <?php _e('Card information is incomplete', 'giftflowwp'); ?>
          </span>
        </div>
      </div>
  </div>
  <?php
}

/**
 * Donation form thank you template
 */
function giftflowwp_donation_form_thank_you_section_html($args = array()) {
  // load template thank you
  $template = new Template();
  $template->load_template('donation-form-thank-you.php', $args);
}

// donation form error section
function giftflowwp_donation_form_error_section_html() {
  // load template error
  $template = new Template();
  $template->load_template('donation-form-error.php');
}

function giftflowwp_load_template($template_name, $args = array()) {
  $template = new Template();
  $template->load_template($template_name, $args);
}

// get donation data by donation id
function giftflowwp_get_donation_data_by_id($donation_id) {
  $donation_data = get_post($donation_id);

  if(!$donation_data) {
    return false;
  }

  $campaign_id = get_post_meta($donation_id, '_campaign_id', true);
  $donation_data->campaign_name = $campaign_id ? get_the_title($campaign_id) : '???';
  $donation_data->campaign_url = $campaign_id ? get_the_permalink($campaign_id) : '#';

  $donor_id = get_post_meta($donation_id, '_donor_id', true);
  // donor_name = first name + last name
  $donation_data->donor_name = $donor_id ? get_post_meta($donor_id, '_first_name', true) . ' ' . get_post_meta($donor_id, '_last_name', true) : '???';
  $donation_data->donor_email = $donor_id ? get_post_meta($donor_id, '_email', true) : '';

  $donation_data->amount = get_post_meta($donation_id, '_amount', true);
  $donation_data->__amount_formatted = giftflowwp_render_currency_formatted_amount($donation_data->amount);

  $donation_data->status = get_post_meta($donation_id, '_status', true);
  $donation_data->payment_method = get_post_meta($donation_id, '_payment_method', true);
  $donation_data->__date = get_the_date('', $donation_id);
  $donation_data->__date_gmt = get_gmt_from_date(get_the_date('Y-m-d H:i:s', $donation_id));

  return $donation_data;
}