<?php
/**
 * Title: Featured Campaign List
 * Slug: giftflow/featured-campaign-list
 * Description: Display campaign list UI, with heading, campaigns maybe filter, oderby, etc
 * Categories: giftflow, giftflow-campaigns
 * Keywords: donation, campaign
 * Viewport Width: 1280
 * Inserter: yes
 *
 * @package GiftFlow
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|10","left":"var:preset|spacing|10"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--10)"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Featured campaigns</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Featured campaigns</strong>&nbsp;are a curated selection of high-impact, transparent projects designed for urgent, long-term community change. They include fully tracked initiatives like reforestation, veterans' housing, and cancer research, ensuring donors see exactly where their money goes.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"25px"} -->
<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:giftflow/campaign-list {"perPage":5,"imageRatio":"16/9","showPagination":false,"accentColor":"#ff5c28"} /--></div>
<!-- /wp:group -->
