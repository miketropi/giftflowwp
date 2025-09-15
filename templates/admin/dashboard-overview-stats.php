<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-overview">
    <div class="giftflowwp-widget-header">
        <h3><?php _e('Overview', 'giftflowwp'); ?></h3>
    </div>
    <div class="giftflowwp-widget-content">
        <div class="giftflowwp-overview-stats">
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total donations:', 'giftflowwp'); ?></strong>
                <span><?php echo giftflowwp_render_currency_formatted_amount($total_donations); ?></span>
            </div>
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total campaigns:', 'giftflowwp'); ?></strong>
                <span><?php echo esc_html($total_campaigns); ?></span>
            </div>
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total donors:', 'giftflowwp'); ?></strong>
                <span><?php echo esc_html($total_donors); ?></span>
            </div>
            <div class="giftflowwp-stat-item">
                <strong><?php _e('New donors:', 'giftflowwp'); ?></strong>
                <span><?php echo esc_html($new_donors); ?> (<?php _e('last 7 days', 'giftflowwp'); ?>)</span>
            </div>
            <div class="giftflowwp-stat-item top-donors">
                <strong><?php _e('Top donors:', 'giftflowwp'); ?></strong>
                <table class="giftflowwp-top-donors-table">
                    <tr>
                        <th></th>
                        <th><?php _e('Name', 'giftflowwp'); ?></th>
                        <th><?php _e('Amount', 'giftflowwp'); ?></th>
                    </tr>
                    <?php for($i = 0; $i < count($top_donors); $i++): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td>
                            <a href="<?php echo esc_url($top_donors[$i]['link']); ?>">
                                <?php echo esc_html($top_donors[$i]['name']); ?>
                            </a>
                        </td>
                        <td><?php echo giftflowwp_render_currency_formatted_amount($top_donors[$i]['amount']); ?></td>
                    </tr>
                    <?php endfor; ?>
                </table>
            </div>
        </div>
    </div>
</div>