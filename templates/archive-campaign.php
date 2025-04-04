<?php
/**
 * The template for displaying archive campaign posts
 *
 * @package GiftFlowWp
 */

get_header();
?>

<main id="primary" class="site-main">
    <header class="page-header">
        <h1 class="page-title"><?php _e('Campaigns', 'giftflowwp'); ?></h1>
    </header>

    <div class="campaigns-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('campaign-card'); ?>>
                    <div class="campaign-thumbnail">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="campaign-content">
                        <header class="entry-header">
                            <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                        </header>

                        <div class="campaign-meta">
                            <?php
                            $goal_amount = get_post_meta(get_the_ID(), '_goal_amount', true);
                            $raised_amount = giftflowwp_get_campaign_raised_amount(get_the_ID());
                            $progress_percentage = giftflowwp_get_campaign_progress_percentage(get_the_ID());
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
                                <span class="end-date"><?php echo sprintf(__('Ends: %s', 'giftflowwp'), date_i18n(get_option('date_format'), strtotime($end_date))); ?></span>
                            </div>
                            
                            <div class="campaign-status">
                                <span class="status-badge status-<?php echo esc_attr($status); ?>">
                                    <?php echo esc_html(ucfirst($status)); ?>
                                </span>
                            </div>
                        </div>

                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="campaign-actions">
                            <a href="<?php the_permalink(); ?>" class="button"><?php _e('View Campaign', 'giftflowwp'); ?></a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php
            the_posts_pagination(array(
                'prev_text' => __('Previous', 'giftflowwp'),
                'next_text' => __('Next', 'giftflowwp'),
            ));
            ?>

        <?php else : ?>
            <p><?php _e('No campaigns found.', 'giftflowwp'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer(); 