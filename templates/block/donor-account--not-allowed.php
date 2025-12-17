<?php 
/**
 * Template for not allowed to view this donation
 * @package GiftFlow
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

?>
<div class="gfw-not-allowed">
  <?php esc_html_e('You are not allowed to view this donation.', 'giftflow'); ?>
</div>
<?php