<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$posts = $donations['posts'] ?? [];
$total = $donations['total'] ?? 0;
$pagination = $donations['pagination'] ?? 1;
?>

<div class="gfw-donation-list-container">
    <div class="gfw-donation-list-header">
        <span class="gfw-donation-count">
            <?php if ( $total > 0 ) : ?>
                <?php
                /* translators: %s: number of donations */
                printf( esc_html__( '%s donations', 'giftflowwp' ), esc_html( $total ) );
                ?>
            <?php else : ?>
                <?php esc_html_e( 'Be the first to donate', 'giftflowwp' ); ?>
            <?php endif; ?>
        </span>
    </div>

    <?php if (!empty($posts)): ?>
        <div class="gfw-donation-list">
            <?php foreach ($posts as $donation): ?>
                <div class="gfw-donation-item">
                    <div class="gfw-donation-row">
                        <div class="gfw-donation-left">
                            <div class="gfw-donor-info">
                                <?php echo esc_html($donation['donor_meta']['name']); ?>
                                <?php if ($donation['is_anonymous'] !== 'yes' && !empty($donation['donor_meta']['city']) && !empty($donation['donor_meta']['country'])): ?>
                                    <span class="gfw-location">from <?php echo esc_html($donation['donor_meta']['city']); ?>, <?php echo esc_html($donation['donor_meta']['country']); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($donation['message'])): ?>
                                <div class="gfw-donation-message">
                                    "<?php echo esc_html($donation['message']); ?>"
                                </div>
                            <?php endif; ?>
                            
                            <div class="gfw-donation-time">
                                <?php echo esc_html(date('F j, Y – H:i:s', strtotime($donation['date_gmt']))); ?>
                            </div>
                        </div>
                        
                        <div class="gfw-donation-right">
                            <div class="gfw-donation-amount"><?php echo $donation['amount_formatted']; ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pagination > 1): ?>
            <div class="gfw-donation-pagination">
                <span>
                    <?php 
                    /* translators: 1: is paged, 2: total paged number */
                    printf( esc_html__( 'Page %1$d of %2$d', 'giftflowwp' ), $paged, $pagination ); 
                    ?>
                </span>
                <div class="gfw-pagination-buttons">
                  <?php if ($paged > 1): ?>
                    <button 
                      class="gfw-prev-btn" 
                      data-page="<?php echo esc_attr($paged - 1); ?>"
                      data-campaign="<?php echo esc_attr($campaign_id); ?>"
                      onClick="window.giftflowwp.loadDonationListPaginationTemplate_Handle(this)" 
                    >
                      <?php esc_html_e('Previous', 'giftflowwp'); ?>
                    </button>
                  <?php endif; ?>
                  
                  <?php if ($paged < $pagination): ?>
                    <button 
                      class="gfw-next-btn" 
                      data-page="<?php echo esc_attr($paged + 1); ?>"
                      data-campaign="<?php echo esc_attr($campaign_id); ?>" 
                      onClick="window.giftflowwp.loadDonationListPaginationTemplate_Handle(this)">
                      <?php esc_html_e('Next', 'giftflowwp'); ?>
                    </button>
                  <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="gfw-donation-empty">
            <p><?php esc_html_e('No donations yet', 'giftflowwp'); ?></p>
        </div>
    <?php endif; ?>
</div>