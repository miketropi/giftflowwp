<?php 
/**
 * API 
 * 
 * @package GiftFlowWp
 * @since v1.0.0
 */

add_action('rest_api_init', function () {
    register_rest_route('giftflowwp/v1', '/campaigns', array(
        'methods' => 'GET',
        'callback' => 'giftflowwp_get_campaigns',
        'permission_callback' => '__return_true', // Public endpoint, adjust as needed,
        // params
        'args' => array(
            'per_page' => array(
                'type' => 'integer',
                'default' => 3,
            ),
            'page' => array(
                'type' => 'integer',
                'default' => 1,
            ),
            'search' => array(
                'type' => 'string',
                'default' => '',
            ),
            'order' => array(
                'type' => 'string',
                'default' => 'desc',
            ),
            'orderby' => array(
                'type' => 'string',
                'default' => 'date',
            ),
            // include
            'include' => array(
                'type' => 'array',
                'default' => [],
            ),
            // exclude
            'exclude' => array(
                'type' => 'array',
                'default' => [],
            ),
        ),
    ));
});

/**
 * Handle GET /wp-json/giftflowwp/v1/campaigns
 * Returns a list of campaigns.
 */
function giftflowwp_get_campaigns($request) {
    // Example: Fetch campaigns from a custom post type 'campaign'
    $args = array(
        'post_type'      => 'campaign',
        'post_status'    => 'publish',
        'posts_per_page' => $request->get_param('per_page'),
        'paged' => $request->get_param('page'),
        's' => $request->get_param('search'),
        'order' => $request->get_param('order'),
        'orderby' => $request->get_param('orderby'),
        'post__in' => $request->get_param('include'),
        'post__not_in' => $request->get_param('exclude'),
    );
    $query = new WP_Query($args);

    $campaigns = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $goal_amount = (float) get_post_meta(get_the_ID(), '_goal_amount', true);
            $raised_amount = (float) giftflowwp_get_campaign_raised_amount(get_the_ID());
            $percentage = giftflowwp_get_campaign_progress_percentage(get_the_ID());

            // convert to currency
            $__goal_amount = giftflowwp_render_currency_formatted_amount($goal_amount);
            $__raised_amount = giftflowwp_render_currency_formatted_amount($raised_amount);

            $campaigns[] = array(
                'id'      => get_the_ID(),
                'title'   => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'goal_amount' => $goal_amount,
                'start_date' => get_post_meta(get_the_ID(), '_start_date', true),
                'end_date' => get_post_meta(get_the_ID(), '_end_date', true),
                'status' => get_post_meta(get_the_ID(), '_status', true),
                'link' => get_the_permalink(),
                // Add more fields as needed, e.g. goal, raised, percent, etc.
                'percentage' => $percentage,
                'raised_amount' => $raised_amount,
                '__goal_amount' => $__goal_amount,
                '__raised_amount' => $__raised_amount,
            );
        }
        wp_reset_postdata();
    }

    return rest_ensure_response($campaigns);
}

