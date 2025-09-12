<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>
<div class="giftflowwp-widget giftflowwp-widget-charts">
  <div class="giftflowwp-widget-header">
      <h3><?php _e('Statistics Charts', 'giftflowwp'); ?></h3>
  </div>
  <div class="giftflowwp-widget-content">
    <div class="giftflowwp-charts-container">
        <div class="giftflowwp-chart-item">
            <h4><?php _e('Donations by Period', 'giftflowwp'); ?></h4>
            <div class="giftflowwp-chart-period-controls">
                <div class="giftflowwp-chart-buttons">
                    <button class="button button-small active" data-period="day"><?php _e('Day', 'giftflowwp'); ?></button>
                    <button class="button button-small" data-period="week"><?php _e('Week', 'giftflowwp'); ?></button>
                    <button class="button button-small" data-period="month"><?php _e('Month', 'giftflowwp'); ?></button>
                </div>
            </div>
            <div class="giftflowwp-chart-container">
                <canvas id="giftflowwp-donations-period-chart" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="giftflowwp-chart-item">
            <h4><?php _e('Donations by Campaign', 'giftflowwp'); ?></h4>
            <div class="giftflowwp-chart-container">
                <canvas id="giftflowwp-donations-campaign-chart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }

        // Donations by Period Chart
        var periodCtx = document.getElementById('giftflowwp-donations-period-chart').getContext('2d');
        var periodChart = new Chart(periodCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: '<?php _e('Donations', 'giftflowwp'); ?>',
                    data: [1200, 1900, 3000, 5000, 2000, 3000, 4500, 3800, 4200, 3500, 2800, 3100],
                    borderColor: '#2196F3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2196F3',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
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
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString();
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
                            callback: function(value) {
                                return '$' + value.toLocaleString();
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
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Donations by Campaign Pie Chart
        var campaignCtx = document.getElementById('giftflowwp-donations-campaign-chart').getContext('2d');
        var campaignChart = new Chart(campaignCtx, {
            type: 'doughnut',
            data: {
                labels: ['<?php _e('Education Fund', 'giftflowwp'); ?>', '<?php _e('Healthcare', 'giftflowwp'); ?>', '<?php _e('Emergency Relief', 'giftflowwp'); ?>', '<?php _e('Community Development', 'giftflowwp'); ?>', '<?php _e('Others', 'giftflowwp'); ?>'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#2196F3',
                        '#4CAF50', 
                        '#FF9800',
                        '#9C27B0',
                        '#607D8B'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 11
                            },
                            color: '#666'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        cornerRadius: 6,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + percentage + '%';
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Period control buttons
        $('.giftflowwp-chart-buttons button').on('click', function() {
            var period = $(this).data('period');
            
            // Update button states
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            
            // Update chart data based on period
            var newData, newLabels;
            
            switch(period) {
                case 'day':
                    newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    newData = [800, 1200, 900, 1500, 1800, 2200, 1900];
                    break;
                case 'week':
                    newLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                    newData = [8500, 9200, 7800, 9600];
                    break;
                case 'month':
                    newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    newData = [1200, 1900, 3000, 5000, 2000, 3000, 4500, 3800, 4200, 3500, 2800, 3100];
                    break;
            }
            
            periodChart.data.labels = newLabels;
            periodChart.data.datasets[0].data = newData;
            periodChart.update('active');
        });
    });
    </script>
  </div>
</div>