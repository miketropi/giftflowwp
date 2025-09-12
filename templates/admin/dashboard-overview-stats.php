<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-overview">
    <div class="giftflowwp-widget-header">
        <h3><?php _e('Overview', 'giftflowwp'); ?></h3>
    </div>
    <div class="giftflowwp-widget-content">
        <div class="giftflowwp-overview-stats">
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total donations:', 'giftflowwp'); ?></strong>
                <span><?php echo giftflowwp_render_currency_formatted_amount($total_donations); ?></span>
            </div>
            <div class="giftflowwp-stat-item">
                <strong><?php _e('Total donors:', 'giftflowwp'); ?></strong>
                <span><?php echo esc_html($total_donors); ?></span>
            </div>
            <div class="giftflowwp-stat-campaigns">
                <div class="giftflowwp-stat-item">
                    <strong><?php _e('Total campaigns:', 'giftflowwp'); ?></strong>
                    <span><?php echo esc_html($total_campaigns); ?></span>
                </div>
                <div class="giftflowwp-stat-chart">
                    <div class="giftflowwp-chart-container">
                        <canvas id="giftflowwp-campaign-chart" width="400" height="200"></canvas>
                    </div>
                    <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        if (typeof Chart === 'undefined') {
                            console.error('Chart.js is not loaded');
                            return;
                        }
                        var campaignData = {
                            labels: ['<?php _e('Active', 'giftflowwp'); ?>', '<?php _e('Closed', 'giftflowwp'); ?>', '<?php _e('Pending', 'giftflowwp'); ?>', '<?php _e('Completed', 'giftflowwp'); ?>'],
                            datasets: [{
                                label: '<?php _e('Campaigns', 'giftflowwp'); ?>',
                                data: [
                                    <?php echo $active_campaigns ? $active_campaigns : 0; ?>,
                                    <?php echo $closed_campaigns ? $closed_campaigns : 0; ?>,
                                    <?php echo $pending_campaigns ? $pending_campaigns : 0; ?>,
                                    <?php echo $completed_campaigns ? $completed_campaigns : 0; ?>
                                ],
                                backgroundColor: [
                                    '#4CAF50',  // Active
                                    '#6C757D',  // Closed  
                                    '#FFC107',  // Pending
                                    '#2196F3'   // Completed
                                ],
                                borderColor: [
                                    '#388E3C',
                                    '#495057', 
                                    '#FF9800',
                                    '#1565C0'
                                ],
                                borderWidth: 1,
                                borderRadius: 2,
                                borderSkipped: false,
                            }]
                        };

                        var ctx = document.getElementById('giftflowwp-campaign-chart').getContext('2d');
                        var chart = new Chart(ctx, {
                            type: 'bar',
                            data: campaignData,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        titleColor: '#fff',
                                        bodyColor: '#fff',
                                        borderColor: '#ddd',
                                        borderWidth: 1,
                                        cornerRadius: 6,
                                        displayColors: false,
                                        callbacks: {
                                            title: function(context) {
                                                return context[0].label;
                                            },
                                            label: function(context) {
                                                return context.parsed.y + ' <?php _e('campaigns', 'giftflowwp'); ?>';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: '#f0f0f0',
                                            drawBorder: false
                                        },
                                        ticks: {
                                            color: '#666',
                                            font: {
                                                size: 11
                                            },
                                            stepSize: 1,
                                            callback: function(value) {
                                                if (Number.isInteger(value)) {
                                                    return value;
                                                }
                                            }
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#666',
                                            font: {
                                                size: 11
                                            }
                                        }
                                    }
                                },
                                animation: {
                                    duration: 1000,
                                    easing: 'easeOutQuart',
                                    delay: function(context) {
                                        return context.dataIndex * 200;
                                    }
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                }
                            }
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>