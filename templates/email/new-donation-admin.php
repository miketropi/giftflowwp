<?php 

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

$campaign_name = $campaign_name ?? '';
$campaign_url = $campaign_url ?? '';
$donor_name = $donor_name ?? '';
$donor_email = $donor_email ?? '';
$amount = $amount ?? '';
$date = $date ?? '';
$status = $status ?? '';
$payment_method = $payment_method ?? '';

// Get site information
$site_name = get_bloginfo('name');
$site_url = home_url();
$admin_email = get_option('admin_email');
$accent_color = '#0b57d0';
?>

<h2 style="font-size: 1rem;     font-weight: 600;     margin: 0 0 .3rem 0; color: black;">
    <?php esc_html_e('New Donation Received', 'giftflowwp'); ?>
</h2>

<p style="margin: 0 0 1.8rem 0;     line-height: 1.6;     opacity: .8;     font-size: .9rem;">
    <?php esc_html_e('A new donation has been received on your website.', 'giftflowwp'); ?>
</p>

<table id="giftflowwp-email-table" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%; border-collapse: collapse; margin: 1.8rem 0 0; background: #ffffff; border: 1px solid #e9ecef; border-radius: 0.3rem; overflow: hidden;">
    
    <!-- Donation Details Header -->
    <tr>
        <td style="padding: .8rem 0; border-bottom: 1px solid #e9ecef;">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: black;">
                <?php esc_html_e('Donation Details', 'giftflowwp'); ?>
            </h3>
        </td>
    </tr>
    
    <!-- Amount -->
    <tr>
        <td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="width: 30%; padding: 0; vertical-align: top;">
                        <p style="font-size: 0.9rem;">
                            <?php esc_html_e('Amount:', 'giftflowwp'); ?>
                        </p>
                    </td>
                    <td style="width: 70%; padding: 0; vertical-align: top;">
                        <span style="color: <?php echo esc_attr($accent_color); ?>; font-weight: 600;">
                            <?php echo esc_html($amount); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- Donor Name -->
    <tr>
        <td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="width: 30%; padding: 0; vertical-align: top;">
                        <p style="font-size: 0.9rem;">
                            <?php esc_html_e('Donor:', 'giftflowwp'); ?>
                        </p>
                    </td>
                    <td style="width: 70%; padding: 0; vertical-align: top;">
                        <span style="">
                            <?php echo esc_html($donor_name); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- Donor Email -->
    <tr>
        <td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="width: 30%; padding: 0; vertical-align: top;">
                        <p style="font-size: 0.9rem;">
                            <?php esc_html_e('Email:', 'giftflowwp'); ?>
                        </p>
                    </td>
                    <td style="width: 70%; padding: 0; vertical-align: top;">
                        <a href="mailto:<?php echo esc_attr($donor_email); ?>" style="color: <?php echo esc_attr($accent_color); ?>; text-decoration: none;">
                            <?php echo esc_html($donor_email); ?>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- Campaign -->
    <?php if (!empty($campaign_name)): ?>
    <tr>
        <td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="width: 30%; padding: 0; vertical-align: top;">
                        <p style="font-size: 0.9rem;">
                            <?php esc_html_e('Campaign:', 'giftflowwp'); ?>
                        </p>
                    </td>
                    <td style="width: 70%; padding: 0; vertical-align: top;">
                        <?php if (!empty($campaign_url)): ?>
                            <a href="<?php echo esc_url($campaign_url); ?>" style="color: <?php echo esc_attr($accent_color); ?>; text-decoration: none; font-size: 0.95rem;">
                                <?php echo esc_html($campaign_name); ?>
                            </a>
                        <?php else: ?>
                            <span style="">
                                <?php echo esc_html($campaign_name); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php endif; ?>
    
    <!-- Date -->
    <tr>
        <td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="width: 30%; padding: 0; vertical-align: top;">
                        <p style="font-size: 0.9rem;">
                            <?php esc_html_e('Date:', 'giftflowwp'); ?>
                        </p>
                    </td>
                    <td style="width: 70%; padding: 0; vertical-align: top;">
                        <span style="">
                            <?php echo esc_html($date); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- Payment Method -->
    <?php if (!empty($payment_method)): ?>
    <tr>
        <td style="padding: .8rem 0; border-bottom: 1px solid #f1f3f4;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="width: 30%; padding: 0; vertical-align: top;">
                        <p style="font-size: 0.9rem;">
                            <?php esc_html_e('Payment Method:', 'giftflowwp'); ?>
                        </p>
                    </td>
                    <td style="width: 70%; padding: 0; vertical-align: top;">
                        <span style="">
                            <?php echo esc_html($payment_method); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php endif; ?>
    
    <!-- Status -->
    <tr>
        <td style="padding: .8rem 0;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="width: 30%; padding: 0; vertical-align: top;">
                        <p style="font-size: 0.9rem;">
                            <?php esc_html_e('Status:', 'giftflowwp'); ?>
                        </p>
                    </td>
                    <td style="width: 70%; padding: 0; vertical-align: top;">
                        <span style="">
                            <?php echo esc_html($status); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
</table>