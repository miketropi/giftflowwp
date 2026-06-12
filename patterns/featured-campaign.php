<?php
/**
 * Title: Featured Campaign
 * Slug: giftflow/featured-campaign
 * Description: Two column layout: (details | campaign card). Ideal for highlighting your most important campaign on any page.
 * Categories: giftflow, giftflow-campaigns
 * Keywords: donation, campaign
 * Viewport Width: 1280
 * Block Types: core/template-part/header
 * Inserter: yes
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns {"verticalAlignment":null,"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading -->
<h2 class="wp-block-heading">Featured Campaigns</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Transform lives with our most impactful campaigns. Each featured project has been carefully selected for its transparency, urgency, and long‑term community impact. Explore stories of real people, see exactly where your gift goes, and join hundreds of donors who are already making a difference today.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Sponsors</strong></p>
<!-- /wp:paragraph -->

<!-- wp:giftflow/sponsor-logos {"logos":[{"id":0,"url":"https://pub-0645c3b9d3674132af6b362484df0f3c.r2.dev/alonepro/logoipsum-418.png","alt":""},{"id":0,"url":"https://pub-0645c3b9d3674132af6b362484df0f3c.r2.dev/alonepro/logoipsum-261.png","alt":""},{"id":0,"url":"https://pub-0645c3b9d3674132af6b362484df0f3c.r2.dev/alonepro/logoipsum-263.png","alt":""},{"id":0,"url":"https://pub-0645c3b9d3674132af6b362484df0f3c.r2.dev/alonepro/logoipsum-264.png","alt":""},{"id":0,"url":"https://pub-0645c3b9d3674132af6b362484df0f3c.r2.dev/alonepro/logoipsum-343.png","alt":""}],"gap":44,"logoHeight":28} /-->

<!-- wp:separator {"className":"is-style-wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}},"color":{"background":"#f6f6f6"}}} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-wide" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30);background-color:#f6f6f6;color:#f6f6f6"/>
<!-- /wp:separator -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":{"top":"0","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">View Detail Campaign</a></div>
<!-- /wp:button -->

<!-- wp:button {"backgroundColor":"base","textColor":"contrast","className":"is-style-fill","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background has-link-color wp-element-button">Register Volunteers</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:giftflow/campaign-card {"cardStyle":"overlay","showPresetAmounts":true} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->