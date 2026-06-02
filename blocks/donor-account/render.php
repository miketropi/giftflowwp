<?php
/**
 * Render callback for the Donor Account block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-donor-account' )
);

if ( ! is_user_logged_in() ) {
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	giftflow_load_template( 'login-form.php' );
	echo '</div>';
	return;
}

$gf_current_user = wp_get_current_user();
$gf_tabs         = giftflow_donor_account_tabs();
$gf_active_tab   = get_query_var( 'tab', 'dashboard' );

if ( ! isset( $gf_tabs[ $gf_active_tab ] ) ) {
	$gf_active_tab = 'dashboard';
}
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?> data-wp-interactive="giftflow/donor-account">
	<nav class="giftflow-donor-account__nav" role="tablist">
		<?php foreach ( $gf_tabs as $gf_slug => $gf_tab ) : ?>
			<a
				href="<?php echo esc_url( add_query_arg( 'tab', $gf_slug, get_permalink() ) ); ?>"
				class="giftflow-donor-account__nav-item<?php echo $gf_slug === $gf_active_tab ? ' is-active' : ''; ?>"
				role="tab"
				aria-selected="<?php echo $gf_slug === $gf_active_tab ? 'true' : 'false'; ?>"
			>
				<?php echo esc_html( $gf_tab['label'] ); ?>
			</a>
		<?php endforeach; ?>
	</nav>

	<div class="giftflow-donor-account__content">
		<?php
		if ( isset( $gf_tabs[ $gf_active_tab ]['callback'] ) && is_callable( $gf_tabs[ $gf_active_tab ]['callback'] ) ) {
			call_user_func( $gf_tabs[ $gf_active_tab ]['callback'], $gf_current_user );
		}
		?>
	</div>
</div>
