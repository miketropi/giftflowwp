<?php 
/**
 * Common functions for the plugin
 * 
 * @package GiftFlowWP
 */

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

  $amount = str_replace(array_keys($replace), array_values($replace), $template);

  $amount = apply_filters('giftflowwp_render_currency_formatted_amount', $amount, $currency, $decimals);
  return $amount;
}

// get currency template
function giftflowwp_get_currency_template() {
  $options = get_option('giftflowwp_general_options');
  $currency_template = isset($options['currency_template']) ? $options['currency_template'] : '{{currency_symbol}} {{amount}}';
  return $currency_template;
}

function giftflowwp_get_preset_donation_amounts() {
  $options = get_option('giftflowwp_general_options');
  $preset_donation_amounts = isset($options['preset_donation_amounts']) ? $options['preset_donation_amounts'] : '10, 25, 35';
  return $preset_donation_amounts;
}

