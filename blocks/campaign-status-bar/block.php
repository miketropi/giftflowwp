<?php 

function giftflowwp_campaign_status_bar_block() {

    register_block_type(
        'giftflowwp/campaign-status-bar',
        array(
            'api_version' => 3,
            'render_callback' => 'giftflowwp_campaign_status_bar_block_render',
            'attributes' => array(
                '__editorPostId' => array(
                    'type' => 'number',
                    'default' => 0,
                ),
            ),
        )
    );
}

add_action('init', 'giftflowwp_campaign_status_bar_block');

function giftflowwp_campaign_status_bar_block_render($attributes, $content, $block) {
    $post_id = (int) $block->context['postId'];

    // Check if it is a WP json api request:
    if ( wp_is_serving_rest_request() ) {
      // We can assume it is a server side render callback from Gutenberg
      if ( isset($attributes['__editorPostId']) ) {
        // Value from JS can be a float, we need integer.
        $attributes['__editorPostId'] = (int) $attributes['__editorPostId'];
      }
      $post_id = $attributes['__editorPostId'] ?? $post_id;
    }

    // Get campaign data
    $goal_amount = get_post_meta($post_id, '_goal_amount', true);
    $raised_amount = giftflowwp_get_campaign_raised_amount($post_id);
    $progress_percentage = giftflowwp_get_campaign_progress_percentage($post_id);

    // days left
    $days_left = giftflowwp_get_campaign_days_left($post_id);
    
    // Get donation count
    $donations = get_posts(array(
        'post_type' => 'donation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_campaign_id',
                'value' => $post_id,
                'compare' => '='
            ),
            array(
                'key' => '_status',
                'value' => 'completed',
                'compare' => '='
            )
        )
    ));
    $donation_count = count($donations);
    $raised_amount_formatted = giftflowwp_render_currency_formatted_amount($raised_amount);
    $goal_amount_formatted = giftflowwp_render_currency_formatted_amount($goal_amount);
    ob_start();
    ?>
    <div class="giftflowwp-campaign-status-bar">
        <div class="campaign-progress">
            <div class="progress-stats">
                <!-- template example: $100 raised from $1000 total -->
                <?php echo sprintf(__('%s raised from %s total', 'giftflowwp'), $raised_amount_formatted, $goal_amount_formatted); ?>
            </div>
            <div class="progress-bar" style="height: 0.5rem; background-color: #f1f5f9; overflow: hidden; width: 100%;">
                <div class="progress" style="width: <?php echo esc_attr($progress_percentage); ?>%; height: 100%; background: linear-gradient(90deg, #0ea5e9, #38bdf8);"></div>
            </div>
            <div class="progress-meta">
                <div class="progress-meta-item">
                    <span class="__icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <span class="__text"><?php echo $donation_count; ?> <?php echo _n('donation', 'donations', $donation_count, 'giftflowwp'); ?></span>
                </div>
                <!-- days left -->
                <div class="progress-meta-item">
                    <span class="__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock2-icon lucide-clock-2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 10"/></svg>
                    </span>
                    <span class="__text">
                      <!-- if days left is false, show "Not started", is true, show "Ended" else show days left -->
                      <?php if ($days_left === false) : ?>
                        <?php _e('Not started', 'giftflowwp'); ?>
                      <?php elseif ($days_left === true) : ?>
                        <?php _e('Ended', 'giftflowwp'); ?>
                      <?php else : ?>
                        <?php echo $days_left; ?> <?php echo _n('day left', 'days left', $days_left, 'giftflowwp'); ?>
                      <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

