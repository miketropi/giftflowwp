<?php
/**
 * Render callback for the Campaign Single Content block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_post_id = isset( $block->context['postId'] )
	? (int) $block->context['postId']
	: get_the_ID();

if ( ! $gf_post_id ) {
	$block_wrapper_attrs = get_block_wrapper_attributes();
	echo '<div ' . $block_wrapper_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html_e( 'No campaign found.', 'giftflow' );
	echo '</div>';
	return;
}

$gf_tabs = array(
	'campaign'  => array(
		'id'       => 'campaign',
		'label'    => __( 'Campaign', 'giftflow' ),
		'callback' => function ( $id ) {
			do_action( 'giftflow_campaign_single_content_tab_campaign_before', $id );
			echo apply_filters( 'the_content', get_the_content( null, false, $id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter applies wp_kses_post by default.
			do_action( 'giftflow_campaign_single_content_tab_campaign_after', $id );
		},
		'active'   => true,
	),
	'donations' => array(
		'id'       => 'donations',
		'label'    => __( 'Donations', 'giftflow' ),
		'callback' => function ( $id ) {
			$gf_args      = array( 'post_id' => $id );
			$gf_paged     = max( 1, (int) get_query_var( 'donation_page', 1 ) );
			$gf_donations = giftflow_get_campaign_donations( $id, $gf_args, $gf_paged );

			echo '<div class="__donations-list-by-campaign-' . (int) $id . '">';
			giftflow_load_template(
				'donation-list-of-campaign.php',
				array(
					'donations'     => $gf_donations,
					'paged'         => $gf_paged,
					'campaign_id'   => $id,
				)
			);
			echo '</div>';
		},
	),
	'comments'  => array(
		'id'       => 'comments',
		'label'    => __( 'Comments', 'giftflow' ),
		'callback' => function ( $id ) {
			giftflow_load_template( 'campaign-comment.php', array( 'post_id' => $id ) );
		},
	),
);

$gf_tabs = apply_filters( 'giftflow_campaign_single_content_tabs', $gf_tabs, $gf_post_id );

$gf_tab_style = $attributes['tabStyle'] ?? 'pills';

$gf_tab_accent  = $attributes['tabAccentColor'] ?? '';
$gf_tab_accent_style = $gf_tab_accent ? '--gf-tab-accent:' . esc_attr( $gf_tab_accent ) . ';' : '';

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-tab-widget', 'style' => $gf_tab_accent_style )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes returns safe HTML. ?>>
	<nav class="giftflow-tab-widget__tabs giftflow-tab-widget__tabs--<?php echo esc_attr( $gf_tab_style ); ?>" role="tablist">
		<?php foreach ( $gf_tabs as $gf_tab ) : ?>
			<button
				class="giftflow-tab-widget__tab<?php echo ! empty( $gf_tab['active'] ) ? ' is-active' : ''; ?>"
				role="tab"
				aria-selected="<?php echo ! empty( $gf_tab['active'] ) ? 'true' : 'false'; ?>"
				data-tab-id="<?php echo esc_attr( $gf_tab['id'] ); ?>"
				tabindex="<?php echo ! empty( $gf_tab['active'] ) ? '0' : '-1'; ?>"
			>
				<?php echo esc_html( $gf_tab['label'] ); ?>
			</button>
		<?php endforeach; ?>
	</nav>

	<div class="giftflow-tab-widget__content">
		<?php foreach ( $gf_tabs as $gf_tab ) : ?>
			<div
				class="giftflow-tab-widget__panel<?php echo ! empty( $gf_tab['active'] ) ? ' is-active' : ''; ?>"
				role="tabpanel"
				data-tab-panel="<?php echo esc_attr( $gf_tab['id'] ); ?>"
				<?php echo ! empty( $gf_tab['active'] ) ? '' : 'hidden'; ?>
				aria-hidden="<?php echo ! empty( $gf_tab['active'] ) ? 'false' : 'true'; ?>"
			>
				<?php
				if ( is_callable( $gf_tab['callback'] ) ) {
					call_user_func( $gf_tab['callback'], $gf_post_id );
				}
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
