<?php 

function giftflowwp_campaign_single_content_block() {

  register_block_type(
      'giftflowwp/campaign-single-content',
      array(
          'api_version' => 3,
          'render_callback' => 'giftflowwp_campaign_single_content_block_render',
      )
  );
}

add_action('init', 'giftflowwp_campaign_single_content_block');

function giftflowwp_campaign_single_content_block_render($attributes, $content, $block) {
  $post_id = get_the_ID();

  // Check if it is a WP json api request:
  if ( wp_is_serving_rest_request() ) {
    // We can assume it is a server side render callback from Gutenberg
    if ( isset($attributes['__editorPostId']) ) {
      // Value from JS can be a float, we need integer.
      $attributes['__editorPostId'] = (int) $attributes['__editorPostId'];
    }
    $post_id = $attributes['__editorPostId'] ?? $post_id;
  }

  $icons = [
    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>',
    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-icon lucide-message-circle"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>',
  ];

  // build tabs array
  $tabs = array(
    'campaign' => array(
      'id' => 'campaign',
      'label' => apply_filters('campaign_single_content_tab_campaign_label', __('Campaign', 'giftflowwp')),
      'content' => apply_filters('campaign_single_content_tab_campaign', '', $post_id),
      'icon' => apply_filters('campaign_single_content_tab_campaign_icon', $icons[0]),
      'is_active' => true,
    ),
    'donations' => array(
      'id' => 'donations',
      'label' => apply_filters('campaign_single_content_tab_donations_label', __('Donations', 'giftflowwp')),
      'content' => apply_filters('campaign_single_content_tab_donations', '', $post_id),
      'icon' => apply_filters('campaign_single_content_tab_donations_icon', $icons[1]),
    ),
    'comments' => array(
      'id' => 'comments',
      'label' => apply_filters('campaign_single_content_tab_comments_label', __('Comments', 'giftflowwp')),
      'content' => apply_filters('campaign_single_content_tab_comments', '', $post_id),
      'icon' => apply_filters('campaign_single_content_tab_comments_icon', $icons[2]),
    ),
  );
  
  // Allow filtering of tabs
  $tabs = apply_filters('giftflowwp_campaign_single_content_tabs', $tabs, $post_id);

  ob_start();
  ?>
  <div class="giftflowwp-campaign-single-content">
    <!-- Tab Widget -->
    <div class="giftflowwp-tab-widget">
      <div class="giftflowwp-tab-widget-tabs">
        <?php foreach ($tabs as $tab) : ?>
          <div class="giftflowwp-tab-widget-tab-item <?php echo isset($tab['is_active']) && true === $tab['is_active'] ? 'active' : ''; ?>" data-tab-id="<?php echo $tab['id']; ?>">
            <span class="giftflowwp-tab-widget-tab-item-icon">
              <?php echo $tab['icon']; ?>
            </span>
            <span class="giftflowwp-tab-widget-tab-item-label">
              <?php echo $tab['label']; ?>
            </span> 
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Tab Content -->
    <div class="giftflowwp-tab-widget-content">
      <?php foreach ($tabs as $tab) : ?>
        <div class="giftflowwp-tab-widget-content-item <?php echo isset($tab['is_active']) && true === $tab['is_active'] ? 'active' : ''; ?>" data-tab-id="<?php echo $tab['id']; ?>">
          <?php echo $tab['content']; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <script>
    // make vanila script controller show / hide tab content, call in dom inited
    document.addEventListener('DOMContentLoaded', function() {
      const tabWidget = document.querySelector('.giftflowwp-tab-widget');
      const tabWidgetContent = document.querySelector('.giftflowwp-tab-widget-content');

      tabWidget.querySelectorAll('.giftflowwp-tab-widget-tab-item').forEach(tabItem => {
        tabItem.addEventListener('click', function(e) {
          const target = this;
          const tabId = target.dataset.tabId;
          const tabContent = tabWidgetContent.querySelector(`.giftflowwp-tab-widget-content-item[data-tab-id="${tabId}"]`);
          
          target.classList.add('active');
          tabContent.classList.add('active');

          // remove active class from all other tab contents
          tabWidgetContent.querySelectorAll('.giftflowwp-tab-widget-content-item').forEach(tabContent => {
            if (tabContent.dataset.tabId !== tabId) {
              tabContent.classList.remove('active');
            }
          });

          // remove active class from all other tab items
          tabWidget.querySelectorAll('.giftflowwp-tab-widget-tab-item').forEach(tabItem => {
            if (tabItem.dataset.tabId !== tabId) {
              tabItem.classList.remove('active');
            }
          });
          
        });
      });

      // set init active tab
      const hash = window.location.hash.substring(1);
      if (hash) {
        console.log('hash', hash);
        // if hash include comment then active comments tab
        if (hash.includes('comment')) {
          const commentsTab = tabWidget.querySelector('.giftflowwp-tab-widget-tab-item[data-tab-id="comments"]');
          if (commentsTab) {
            commentsTab.click();
          }
        }
      }
    });
  </script>
  <?php
  return ob_get_clean();
}

// add filter campaign_single_content_tab_campaign
add_filter('campaign_single_content_tab_campaign', 'giftflowwp_campaign_single_content_tab_campaign', 10, 2);

function giftflowwp_campaign_single_content_tab_campaign($content, $post_id) {
  ob_start();
  ?>
  <div class="campaign-post-content">

    <?php echo do_shortcode('[giftflow_donation_form campaign_id="' . $post_id . '"]'); ?>

    <!-- campaign post content by id -->
    <?php echo get_the_content($post_id); ?>
  </div>
  <?php
  return ob_get_clean();
}

// add filter campaign_single_content_tab_donations
add_filter('campaign_single_content_tab_donations', 'giftflowwp_campaign_single_content_tab_donations', 10, 2);

function giftflowwp_campaign_single_content_tab_donations($content, $post_id) {
  ob_start();
  ?>
  <div class="campaign-post-donations">
    <!-- description -->
    <strong><?php _e('Below are the donations for this campaign.', 'giftflowwp'); ?></strong>

    <div class="__campaign-post-donations-list __donations-list-by-campaign-<?php echo $post_id ?>">
      <?php
        // get all donations for the campaign
        // meta query status = completed
        $args = array(
          'meta_query' => array(
            array(
              'key' => '_status',
              'value' => 'completed',
              'compare' => '='
            )
          )
        );
        $_paged = 1;
        $results = giftflowwp_get_campaign_donations($post_id, $args, $_paged);
        giftflowwp_load_template('donation-list-of-campaign.php', array(
          'donations' => $results,
          'paged' => $_paged,
          'campaign_id' => $post_id,
        ));
      ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

add_filter('campaign_single_content_tab_comments', 'giftflowwp_campaign_single_content_tab_comments', 10, 2);

function giftflowwp_campaign_single_content_tab_comments($content, $post_id) {
  ob_start();
  ?>
  <div class="campaign-post-comments">
    <!-- description -->
    <strong><?php _e('Below are the comments for this campaign.', 'giftflowwp'); ?></strong>

    <div class="campaign-post-comments-content">
      <?php
        // load comments template
        giftflowwp_load_template('campaign-comment.php', array(
          'post_id' => $post_id,
        ));
      ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}