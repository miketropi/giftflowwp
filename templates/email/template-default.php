<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$header = $header ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$content = $content ?? '';
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$footer = $footer ?? '';

// Get site information for branding
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$site_name = get_bloginfo('name');
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$site_url = home_url();
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$admin_email = get_option('admin_email');
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$accent_color = '#0b57d0';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($site_name); ?></title>
    <style>
        /* Reset styles for email clients */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        
        /* Main styles */
        body {
            height: 100% !important;
            margin: 0 !important;
            width: 100% !important;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 0;
            overflow: hidden;
            padding: 1rem !important;
            box-sizing: border-box;
        }
        
        .email-header {
            padding: 2em 0;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            /* letter-spacing: -0.5px; */
        }
        
        .email-header p {
            margin: 10px 0 0 0;
            font-size: .8rem;
            opacity: 0.9;
        }
        
        .email-content {
            padding: 2em;
            background: white;
            border-radius: .3rem;
            margin-bottom: 2rem;
            font-size: .9rem; 
        }
        
        .email-content h2 {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px 0;
            line-height: 1.3;
        }
        
        .email-content h3 {
            color: #34495e;
            font-size: 20px;
            font-weight: 600;
            margin: 30px 0 15px 0;
        }
        
        .email-content p {
            margin: 0 0 20px 0;
            color: #555555;
            line-height: 1.6;
        }
        
        .email-content a {
            color: <?php echo esc_attr($accent_color); ?>;
            text-decoration: none;
            font-weight: 500;
        }
        
        .email-content a:hover {
            color: <?php echo esc_attr($accent_color); ?>;
            text-decoration: underline;
        }
        
        .button {
            display: inline-block;
            background: <?php echo esc_attr($accent_color); ?>;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
        }
        
        .button:hover {
            background: <?php echo esc_attr($accent_color); ?>;
            opacity: 0.9;
            text-decoration: none !important;
        }
        
        .highlight-box {
            background-color: #f8f9fa;
            border-left: 4px solid <?php echo esc_attr($accent_color); ?>;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 6px 6px 0;
        }
        
        .email-footer {
            padding: 0;
        }
        
        .email-footer p {
            margin: 0 0 10px 0;
            color: #6c757d;
            font-size: .9rem;
        }
        
        .email-footer a {
            color: <?php echo esc_attr($accent_color); ?>;
            text-decoration: none;
        }
        
        .social-links {
            margin: 20px 0 0 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: <?php echo esc_attr($accent_color); ?>;
            text-decoration: none;
            font-size: .8rem;
        }

        #giftflowwp-email-table p {
          margin-bottom: 0;
        }
        
        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            
        }
    </style>
</head>
<body>
    <div style="padding: 20px 0; background-color: #f4f4f4;">
        <div class="email-container">
            <!-- Header Section -->
            <div class="email-header">
              <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="padding:0; vertical-align:middle;">
                            <h1 style="margin:0; font-size:1.3rem; font-weight:600;"><?php echo esc_html($site_name); ?></h1>
                        </td>
                        <td style="padding:0; text-align:right; vertical-align:middle;">
                            <p style="margin:0; font-size:.8rem;">
                              <?php if (!empty($header)): ?>
                                <?php echo wp_kses_post($header); ?>
                              <?php else: ?>
                                <?php esc_html_e( 'Thank you for your support', 'giftflowwp' ); ?>
                              <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Main Content Section -->
            <div class="email-content">
                <?php echo wp_kses_post($content); ?>
            </div>
            
            <!-- Footer Section -->
            <div class="email-footer">
                <?php if (!empty($footer)): ?>
                    <?php echo wp_kses_post($footer); ?>
                <?php endif; ?>
                <p style="margin-top: 20px; font-size: .9rem; text-align: center;">
                    <?php
                    printf(
                        /* translators: 1: Site name, 2: Unsubscribe link */
                        esc_html__(
                            'This email was sent from %1$s. This is an automated notification. Please do not reply to this email. If you have any questions, please contact us at %2$s or email %3$s.',
                            'giftflowwp'
                        ),
                        '<a href="' . esc_url($site_url) . '" style="color: ' . esc_attr($accent_color) . ';">' . esc_html($site_name) . '</a>',
                        '<a href="' . esc_url($site_url) . '" style="color: ' . esc_attr($accent_color) . ';">' . esc_html__('Our website', 'giftflowwp') . '</a>',
                        '<a href="mailto:' . esc_attr($admin_email) . '" style="color: ' . esc_attr($accent_color) . ';">' . esc_html($admin_email) . '</a>'
                    );
                    ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
