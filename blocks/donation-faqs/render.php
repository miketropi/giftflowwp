<?php
/**
 * Render callback for the Donation FAQs block.
 *
 * @package GiftFlow
 * @subpackage Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gf_faqs_json = $attributes['faqsJson'] ?? '';
$gf_faqs      = json_decode( $gf_faqs_json, true );
$gf_open_first = $attributes['openFirst'] ?? false;

if ( ! is_array( $gf_faqs ) || empty( $gf_faqs ) ) {
	return;
}

$gf_faqs = array_values(
	array_filter(
		$gf_faqs,
		function ( $faq ) {
			return ! empty( $faq['question'] );
		}
	)
);

if ( empty( $gf_faqs ) ) {
	return;
}

$block_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'giftflow-donation-faqs' )
);
?>
<div <?php echo $block_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> itemscope itemtype="https://schema.org/FAQPage">
	<?php foreach ( $gf_faqs as $gf_index => $gf_faq ) : ?>
		<?php
		$gf_question = $gf_faq['question'] ?? '';
		$gf_answer   = $gf_faq['answer'] ?? '';
		if ( empty( $gf_question ) ) {
			continue;
		}

		$gf_is_open = ( 0 === $gf_index && $gf_open_first );
		$gf_item_id = 'giftflow-faq-' . $gf_index;
		?>
		<div class="giftflow-donation-faqs__item<?php echo $gf_is_open ? ' is-open' : ''; ?>" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
			<button
				class="giftflow-donation-faqs__question"
				aria-expanded="<?php echo $gf_is_open ? 'true' : 'false'; ?>"
				aria-controls="<?php echo esc_attr( $gf_item_id ); ?>"
				itemprop="name"
			>
				<span class="giftflow-donation-faqs__question-text"><?php echo esc_html( $gf_question ); ?></span>
				<span class="giftflow-donation-faqs__toggle" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
						<line x1="12" y1="5" x2="12" y2="19"></line>
						<line x1="5" y1="12" x2="19" y2="12"></line>
					</svg>
				</span>
			</button>
			<div
				id="<?php echo esc_attr( $gf_item_id ); ?>"
				class="giftflow-donation-faqs__answer"
				role="region"
				aria-hidden="<?php echo $gf_is_open ? 'false' : 'true'; ?>"
				itemscope itemprop="acceptedAnswer"
				itemtype="https://schema.org/Answer"
			>
				<div class="giftflow-donation-faqs__answer-inner" itemprop="text">
					<?php echo wp_kses_post( wpautop( $gf_answer ) ); ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
