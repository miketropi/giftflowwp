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
            <h3><?php _e('Chart for donations', 'giftflowwp'); ?></h3>
            <div class="giftflowwp-chart-period-controls">
                <div class="giftflowwp-chart-filters">
                    <div class="giftflowwp-filter-group">
                        <label for="giftflowwp-chart-campaign-filter"><?php _e('Campaign', 'giftflowwp'); ?></label>
                        <select id="giftflowwp-chart-campaign-filter" class="giftflowwp-form-control gf-select2">
                            <option value=""><?php _e('All Campaigns', 'giftflowwp'); ?></option>
                            <?php
                            $campaigns = get_posts(array(
                                'post_type' => 'campaign', 'post_status' => 'publish',
                                'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC'
                            ));
                            foreach ($campaigns as $campaign) {
                                echo '<option value="' . esc_attr($campaign->ID) . '">' . esc_html($campaign->post_title) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="giftflowwp-filter-group">
                        <label for="giftflowwp-chart-period-filter"><?php _e('Time Period', 'giftflowwp'); ?></label>
                        <select id="giftflowwp-chart-period-filter" class="giftflowwp-form-control">
                            <option value="week" selected><?php _e('This Week', 'giftflowwp'); ?></option>
                            <option value="month"><?php _e('This Month', 'giftflowwp'); ?></option>
                            <option value="year"><?php _e('This Year', 'giftflowwp'); ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="giftflowwp-chart-container">
                <canvas id="giftflowwp-donations-period-chart" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="giftflowwp-chart-item">
            <h3><?php _e('Chart for donations statuses', 'giftflowwp'); ?></h3>
            <div class="giftflowwp-chart-period-controls">
                <div class="giftflowwp-chart-filters-2">
                    <div class="giftflowwp-filter-group">
                        <label for="giftflowwp-chart-campaign-filter-2"><?php _e('Campaign', 'giftflowwp'); ?></label>
                        <select id="giftflowwp-chart-campaign-filter-2" class="giftflowwp-form-control gf-select2">
                            <option value=""><?php _e('All Campaigns', 'giftflowwp'); ?></option>
                            <?php
                            $campaigns = get_posts(array(
                                'post_type' => 'campaign', 'post_status' => 'publish',
                                'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC'
                            ));
                            foreach ($campaigns as $campaign) {
                                echo '<option value="' . esc_attr($campaign->ID) . '">' . esc_html($campaign->post_title) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="giftflowwp-chart-container">
                <canvas id="giftflowwp-donations-statuses-chart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Initialize Select2 for campaign filter
        $('.gf-select2').select2({
            placeholder: '<?php _e('Select a campaign...', 'giftflowwp'); ?>',
            allowClear: true,
            width: '100%',
            dropdownParent: $('.giftflowwp-widget-charts')
        });
        
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }

        // Define ajaxurl if not already defined
        if (typeof ajaxurl === 'undefined') {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        }

        // Donations by Period Chart
        var periodCtx = document.getElementById('giftflowwp-donations-period-chart').getContext('2d');
        var periodChart = new Chart(periodCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: '<?php _e('Donations', 'giftflowwp'); ?>',
                    data: [],
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
                    legend: { display: false },
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
                            font: { size: 11 },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#666',
                            font: { size: 11 }
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

        // Function to load chart data via AJAX
        function loadChartData() {
            var campaignId = $('#giftflowwp-chart-campaign-filter').val();
            var period = $('#giftflowwp-chart-period-filter').val();
            
            // Show loading state
            periodChart.data.labels = ['<?php _e('Loading...', 'giftflowwp'); ?>'];
            periodChart.data.datasets[0].data = [0];
            periodChart.update();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'giftflowwp_get_chart_data',
                    campaign_id: campaignId,
                    period: period,
                    nonce: '<?php echo wp_create_nonce('giftflowwp_chart_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        periodChart.data.labels = response.data.labels;
                        periodChart.data.datasets[0].data = response.data.data;
                        periodChart.update('active');
                    } else {
                        console.error('Error loading chart data:', response.data);
                        // Show error state
                        periodChart.data.labels = ['<?php _e('Error loading data', 'giftflowwp'); ?>'];
                        periodChart.data.datasets[0].data = [0];
                        periodChart.update();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    // Show error state
                    periodChart.data.labels = ['<?php _e('Error loading data', 'giftflowwp'); ?>'];
                    periodChart.data.datasets[0].data = [0];
                    periodChart.update();
                }
            });
        }

        // Auto-update chart when filters change
        $('#giftflowwp-chart-campaign-filter, #giftflowwp-chart-period-filter').on('change', function() {
            loadChartData();
        });

        // Load initial data after window loads (default: This Week)
        $(window).on('load', function() {
            loadChartData();
            loadStatusChartData();
        });

        // Donations by Status Chart
        var statusCtx = document.getElementById('giftflowwp-donations-statuses-chart').getContext('2d');
        var statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [],
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
                            font: { size: 11 },
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
                                return context.label + ': ' + context.parsed + ' donations (' + percentage + '%)';
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

        // Function to load status chart data via AJAX
        function loadStatusChartData() {
            var campaignId = $('#giftflowwp-chart-campaign-filter-2').val();
            
            // Show loading state
            statusChart.data.labels = ['<?php _e('Loading...', 'giftflowwp'); ?>'];
            statusChart.data.datasets[0].data = [1];
            statusChart.data.datasets[0].backgroundColor = ['#f0f0f0'];
            statusChart.update();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'giftflowwp_get_status_chart_data',
                    campaign_id: campaignId,
                    nonce: '<?php echo wp_create_nonce('giftflowwp_chart_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        statusChart.data.labels = response.data.labels;
                        statusChart.data.datasets[0].data = response.data.data;
                        statusChart.data.datasets[0].backgroundColor = response.data.colors;
                        statusChart.update('active');
                    } else {
                        console.error('Error loading status chart data:', response.data);
                        // Show error state
                        statusChart.data.labels = ['<?php _e('Error loading data', 'giftflowwp'); ?>'];
                        statusChart.data.datasets[0].data = [1];
                        statusChart.data.datasets[0].backgroundColor = ['#f0f0f0'];
                        statusChart.update();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    // Show error state
                    statusChart.data.labels = ['<?php _e('Error loading data', 'giftflowwp'); ?>'];
                    statusChart.data.datasets[0].data = [1];
                    statusChart.data.datasets[0].backgroundColor = ['#f0f0f0'];
                    statusChart.update();
                }
            });
        }

        // Auto-update status chart when campaign filter changes
        $('#giftflowwp-chart-campaign-filter-2').on('change', function() {
            loadStatusChartData();
        });
    });
    </script>
  </div>
</div>