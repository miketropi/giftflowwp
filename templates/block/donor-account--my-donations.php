<?php 
/**
 * Template for my donations
 * @package GiftflowWP
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// echo '<pre>'; print_r($donations); echo '</pre>';
$donations = $donations ?? null;
$page = $page ?? 1;

// cache process bar
$cache_process_bar = [];
?>
<div class="gfw-my-donations-header">
  <h2 class="gfw-donor-account__title"><?php esc_html_e('My Donations', 'giftflowwp'); ?></h2>
  <p class="gfw-my-donations-desc">
    <?php esc_html_e('Here you can view a detailed record of your recent donations. Each contribution helps us create lasting impact and drive positive change in our community.', 'giftflowwp'); ?>
  </p>
</div>


<?php
// Check if $donations is a WP_Query object and has posts
if ( $donations instanceof WP_Query && $donations->have_posts() ) : ?>
  <table class="giftflow-table gfw-my-donations-table" style="">
    <thead>
      <tr>
        <th></th>
        <th width="40%"><?php esc_html_e('Campaign', 'giftflowwp'); ?></th>
        <th><?php esc_html_e('Amount', 'giftflowwp'); ?></th>
        <th><?php esc_html_e('Date', 'giftflowwp'); ?></th>
        <th><?php esc_html_e('Status', 'giftflowwp'); ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php while ( $donations->have_posts() ) : $donations->the_post();
        $donation_id = get_the_ID();
        $date = get_the_date( 'Y-m-d H:i:s', $donation_id );
        $amount = get_post_meta( $donation_id, '_amount', true );
        $status = get_post_status( $donation_id );
        $campaign_id = get_post_meta( $donation_id, '_campaign_id', true );
        $campaign_title = $campaign_id ? get_the_title( $campaign_id ) : esc_html__('N/A', 'giftflowwp');
        $amount_formatted = giftflowwp_render_currency_formatted_amount( $amount );
        $payment_status = get_post_meta( $donation_id, '_status', true );
      ?>
        <tr>
          <td>
            <span style="font-family: monospace;">#<?php echo esc_html( $donation_id ); ?></span>
          </td>
          <td>
            <?php if ( $campaign_id ) : ?>
              <?php
                if (!isset($cache_process_bar[$campaign_id])) {
                  ob_start();
                  giftflowwp_process_bar_of_campaign_donations($campaign_id);
                  $process_bar_html = ob_get_clean();
                  $cache_process_bar[$campaign_id] = $process_bar_html;
                  echo $process_bar_html;
                } else {
                  echo $cache_process_bar[$campaign_id];
                }
              ?>
              <a class="gfw-campaign-title-link" href="<?php echo esc_url( get_permalink( $campaign_id ) ); ?>" target="_blank">
                <?php echo esc_html( $campaign_title ); ?>
              </a>
            <?php else : ?>
              <?php echo esc_html( $campaign_title ); ?>
            <?php endif; ?>
          </td>
          <td><?php echo $amount_formatted; ?></td>
          <td>
            <span class="gfw-donation-date">
              <?php echo esc_html( $date ); ?>
            </span>
          </td>
          <td>
            <span class="donation-status status-<?php echo esc_attr( $payment_status ); ?>">
              <?php echo esc_html( ucfirst( $payment_status ) ); ?>
            </span>
          </td>
          <td>
            <a class="gfw-view-detail-link" href="<?php echo giftflowwp_donor_account_page_url('donations?_id=' . $donation_id); ?>" style="white-space: nowrap;">
              <?php echo giftflowwp_svg_icon('eye'); ?>
              <?php esc_html_e('View Detail', 'giftflowwp'); ?>
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <?php
  // Pagination (if needed)
  if ( isset( $donations->max_num_pages ) && $donations->max_num_pages > 1 ) :
    $big = 999999999; // need an unlikely integer
    $current_page = max( 1, $page );
    
    ?>
    <div class="gfw-pagination">
      <?php
      echo paginate_links( array(
        'base'      => str_replace( $big, '%#%', esc_url( giftflowwp_donor_account_page_url('donations?_page=' . $big ) ) ),
        'format'    => '?_page=%#%',
        'current'   => $current_page,
        'total'     => $donations->max_num_pages,
        'prev_text' => esc_html__('Previous', 'giftflowwp'),
        'next_text' => esc_html__('Next', 'giftflowwp'),
      ) );
      ?>
    </div>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
<?php else : ?>
  <div class="gfw-no-donations">
    <?php esc_html_e('You have not made any donations yet.', 'giftflowwp'); ?>
  </div>
<?php endif; ?>