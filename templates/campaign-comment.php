<?php
/**
 * Template for campaign comments
 *
 * @package GiftFlow
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$paged    = get_query_var( 'cpage' ) ? intval( get_query_var( 'cpage' ) ) : 1;
// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$per_page = get_option( 'comments_per_page' );

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$args = array(
	'post_id' => $post_id,
	'status'  => 'approve',
	'number'  => $per_page,
	'offset'  => ( $paged - 1 ) * $per_page,
	'orderby' => 'comment_date_gmt',
	'order'   => 'DESC',
);

// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$comments = get_comments( $args );

?>
<div class="gfw-campaign-comments">
	<?php
	if ( $comments ) {
	  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$total_comments = get_comments(
			array(
				'post_id' => get_the_ID(),
				'status'  => 'approve',
				'count'   => true,
			)
		);
	  // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$total_pages = ceil( $total_comments / $per_page );

		if ( $total_pages > 1 ) {
			echo '<nav class="gfw-campaign-comments-pagination">';
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => add_query_arg( 'cpage', '%#%' ) . '#comment',
						'format'    => '',
						'current'   => $paged,
						'total'     => $total_pages,
						'prev_text' => __( 'Previous', 'giftflow' ),
						'next_text' => __( 'Next', 'giftflow' ),
					)
				)
			);
			echo '</nav> <!-- .gfw-campaign-comments-pagination -->';
		}

		echo '<ol class="gfw-campaign-comments-list">';
		wp_list_comments(
			array(
				'short_ping' => true,
			),
			$comments
		);
		echo '</ol> <!-- .gfw-campaign-comments-list -->';

		if ( $total_pages > 1 ) {
			echo '<nav class="gfw-campaign-comments-pagination">';
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => add_query_arg( 'cpage', '%#%' ) . '#comment',
						'format'    => '',
						'current'   => $paged,
						'total'     => $total_pages,
						'prev_text' => __( 'Previous', 'giftflow' ),
						'next_text' => __( 'Next', 'giftflow' ),
					)
				)
			);
			echo '</nav> <!-- .gfw-campaign-comments-pagination -->';
		}
	} else {

		// check post comment is closed.
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$comment_status = comments_open( get_the_ID() );

		if ( true !== $comment_status ) {
			// return message that comment is closed.
			?>
		<div class="gfw-no-comments">
		<p>
			<?php esc_html_e( 'Comments are closed for this campaign.', 'giftflow' ); ?>
		</p>
		</div>
			<?php
		} else {
			?>
	<div class="gfw-no-comments">
		<p>
			<?php esc_html_e( 'No comments yet. Be the first to share your thoughts!, Your feedback and support mean a lot to this campaign.', 'giftflow' ); ?>
		</p>
	</div>
			<?php
		}
	}
	?>

	<?php
	comment_form(
		array(
			'class_container' => 'gfw-campaign-comments-form',
		)
	);
	?>
</div> <!-- .gfw-campaign-comments -->