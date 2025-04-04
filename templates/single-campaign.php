<?php
/**
 * The template for displaying single campaign posts
 *
 * @package GiftFlowWp
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('campaign-single'); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                
                <div class="campaign-meta">
                    <?php
                    $goal_amount = get_post_meta(get_the_ID(), '_goal_amount', true);
                    $raised_amount = giftflowwp_get_campaign_raised_amount(get_the_ID());
                    $progress_percentage = giftflowwp_get_campaign_progress_percentage(get_the_ID());
                    $start_date = get_post_meta(get_the_ID(), '_start_date', true);
                    $end_date = get_post_meta(get_the_ID(), '_end_date', true);
                    $status = get_post_meta(get_the_ID(), '_status', true);
                    ?>
                    
                    <div class="campaign-progress">
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo esc_attr($progress_percentage); ?>%"></div>
                        </div>
                        <div class="progress-stats">
                            <span class="raised"><?php echo giftflowwp_render_currency_formatted_amount($raised_amount); ?></span>
                            <span class="goal"><?php echo sprintf(__('of %s goal', 'giftflowwp'), giftflowwp_render_currency_formatted_amount($goal_amount)); ?></span>
                        </div>
                    </div>
                    
                    <div class="campaign-dates">
                        <span class="start-date"><?php echo sprintf(__('Started: %s', 'giftflowwp'), date_i18n(get_option('date_format'), strtotime($start_date))); ?></span>
                        <span class="end-date"><?php echo sprintf(__('Ends: %s', 'giftflowwp'), date_i18n(get_option('date_format'), strtotime($end_date))); ?></span>
                    </div>
                    
                    <div class="campaign-status">
                        <span class="status-badge status-<?php echo esc_attr($status); ?>">
                            <?php echo esc_html(ucfirst($status)); ?>
                        </span>
                    </div>
                </div>
            </header>

            <div class="entry-content">
                <?php
                the_content();
                
                // Display campaign gallery if exists
                // $gallery = get_post_meta(get_the_ID(), '_gallery', true);
                // if (!empty($gallery)) {
                //     echo '<div class="campaign-gallery">';
                //     foreach ($gallery as $image_id) {
                //         echo wp_get_attachment_image($image_id, 'large');
                //     }
                //     echo '</div>';
                // }
                ?>
            </div>

            <div class="campaign-donation-section">
                <h2><?php _e('Make a Donation', 'giftflowwp'); ?></h2>
                <?php // echo do_shortcode('[giftflow_donation_form campaign_id="' . get_the_ID() . '"]'); ?>
            </div>

            <div class="campaign-donations-section">
                <h2><?php _e('Recent Donations', 'giftflowwp'); ?></h2>
                <?php // echo do_shortcode('[giftflow_donations campaign_id="' . get_the_ID() . '"]'); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer(); 