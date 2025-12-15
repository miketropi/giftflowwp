import { useEffect, useState } from "react";
import { getDashboardStatisticsCharts } from "../ulti/api";
import { Line } from "react-chartjs-2";
import { Ban, ChartColumn } from 'lucide-react'; 
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend
);

export default function DonationsChart() {
  const [dataChart, setDataChart] = useState({
    labels: [],
    donationsData: [],
    donorsData: [],
  });

  // days
  const [period, setPeriod] = useState('7d');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    // Add 15-minute cache for chart data per period
    const fetchChartData = async () => {
      try {
        setLoading(true);
        setError(null);

        // Cache key based on period
        const cacheKey = `giftflowwp_chartdata_${period}`;
        const cacheRaw = localStorage.getItem(cacheKey);
        let cache = null;
        if (cacheRaw) {
          try {
            cache = JSON.parse(cacheRaw);
          } catch (e) {
            cache = null;
          }
        }

        const now = Date.now();
        const FIFTEEN_MINUTES = 15 * 60 * 1000;

        if (cache && cache.timestamp && (now - cache.timestamp < FIFTEEN_MINUTES) && cache.data) {
          setDataChart({
            labels: cache.data.labels || [],
            donationsData: cache.data.donationsData || [],
            donorsData: cache.data.donorsData || [],
          });
        } else {
          const response = await getDashboardStatisticsCharts({ period: period });
          // Fix: API returns nested object, extract the actual chart data
          const chartData = response.donations_overview_chart_by_period || response;

          setDataChart({
            labels: chartData.labels || [],
            donationsData: chartData.donationsData || [],
            donorsData: chartData.donorsData || [],
          });

          // Save to cache
          localStorage.setItem(
            cacheKey,
            JSON.stringify({
              timestamp: now,
              data: {
                labels: chartData.labels || [],
                donationsData: chartData.donationsData || [],
                donorsData: chartData.donorsData || [],
              }
            })
          );
        }
      } catch (err) {
        console.error('Error fetching chart data:', err);
        setError(err.message || 'Failed to load chart data');
      } finally {
        setLoading(false);
      }
    };

    fetchChartData();
  }, [period]);

  // Show loading state
  if (loading) {
    return (
      <div className="giftflowwp-chart-loading __monospace">
        <div className="giftflowwp-chart-loading__spinner"></div>
        <p>Loading chart data...</p>
      </div>
    );
  }

  // Show error state
  if (error) {
    return (
      <div className="giftflowwp-chart-error __monospace">
        <div className="giftflowwp-chart-error__icon">
          <Ban size={24} color="#dc2626" />
        </div>
        <h4>Chart Error</h4>
        <p>{error}</p>
        <button 
          onClick={() => window.location.reload()} 
          className="giftflowwp-chart-error__retry"
        >
          Retry
        </button>
      </div>
    );
  }

  // Show empty state if no data
  if (
    !dataChart.labels.length ||
    (
      (!dataChart.donationsData || Object.keys(dataChart.donationsData).length === 0) &&
      (!dataChart.donorsData || Object.keys(dataChart.donorsData).length === 0)
    )
  ) {
    return (
      <div className="giftflowwp-chart-empty __monospace">
        <div className="giftflowwp-chart-empty__icon">
          <ChartColumn size={24} color="#6b7280" />
        </div>
        <h4>No Data Available</h4>
        <p>No donation data found for the selected period. Try selecting a different time range or check back later.</p>
      </div>
    );
  }

  const data = {
    labels: dataChart.labels,
    datasets: [
      {
        type: "bar",
        label: "Donation Amount",
        data: dataChart.donationsData,
        backgroundColor: "rgba(34, 197, 94, 0.6)",
        borderColor: "rgba(34, 197, 94, 1)",
        borderWidth: 1,
        yAxisID: "y",
        order: 1,
      },
      {
        type: "bar",
        label: "Donors Registered",
        data: dataChart.donorsData,
        backgroundColor: "rgba(59, 130, 246, 0.6)",
        borderColor: "rgba(59, 130, 246, 1)",
        borderWidth: 1,
        yAxisID: "y1",
        order: 2,
      },
    ],
  };

  const options = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      mode: "index",
      intersect: false,
    },
    plugins: {
      title: {
        display: true,
        text: `Donations & Donors Overview (Last ${period})`,
        font: {
          size: 16,
          weight: 'bold'
        },
        color: '#1f2937'
      },
      legend: {
        display: true,
        position: 'top',
        labels: {
          usePointStyle: true,
          padding: 20,
          font: {
            size: 12
          }
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        titleColor: '#fff',
        bodyColor: '#fff',
        borderColor: '#e5e7eb',
        borderWidth: 1,
        cornerRadius: 6,
        displayColors: true,
        callbacks: {
          label: function(context) {
            if (context.datasetIndex === 0) {
              return `Donations: $${context.parsed.y.toFixed(2)}`;
            } else {
              return `Donors: ${context.parsed.y}`;
            }
          }
        }
      }
    },
    scales: {
      x: {
        display: true,
        title: {
          display: true,
          text: 'Date',
          color: '#6b7280',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          display: false
        },
        ticks: {
          color: '#6b7280',
          maxRotation: 45,
          minRotation: 0
        }
      },
      y: {
        type: "linear",
        display: true,
        position: "left",
        title: {
          display: true,
          text: `Donation Amount (${ giftflowwp_admin.currency_symbol })`,
          color: '#6b7280',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.1)',
          drawBorder: false
        },
        ticks: {
          color: '#6b7280',
          callback: function(value) {
            return giftflowwp_admin.currency_symbol + value.toFixed(0);
          }
        }
      },
      y1: {
        type: "linear",
        display: true,
        position: "right",
        title: {
          display: true,
          text: "Number of Donors",
          color: '#6b7280',
          font: {
            size: 12,
            weight: 'bold'
          }
        },
        grid: {
          drawOnChartArea: false,
          drawBorder: false
        },
        ticks: {
          color: '#6b7280',
          stepSize: 1
        }
      },
    },
  };

  return (
    <div className="giftflowwp-chart-container">
      <div className="giftflowwp-chart-wrapper">
        <Line data={data} options={options} />
      </div>
      <div className="giftflowwp-chart-description __monospace">
        <p>
          <strong>Chart Description:</strong> This chart shows donation activity and donor registrations over the <select
            className="giftflowwp-chart-description__select"
            value={period}
            onChange={e => setPeriod(e.target.value)}
          >
            <option value="7d">last 7 days</option>
            <option value="30d">last 30 days</option>
            <option value="6m">last 6 months</option>
          </select> period.
          <br />
           <span style={{ color: "rgba(34, 197, 94, 1)", fontWeight: 'bold' }}>Green bars</span> represent total donation amounts (<b>only completed donations</b>), while the <span style={{ color: "rgba(59, 130, 246, 1)", fontWeight: 'bold' }}>blue bars</span> show the number of new donors registered each day.
        </p>
      {/* Cache info and clear cache button */}
      <div className="giftflowwp-chart-cache-note" style={{ marginTop: '1rem', fontSize: '0.95em', display: 'flex', alignItems: 'center', gap: '1.2em' }}>
        <span>
          <strong>Note:</strong> Chart data is cached for 15 minutes to improve performance.
        </span>
        <button
          className="giftflowwp-chart-clear-cache-btn"
          style={{
            background: '#f1f5f9',
            border: '1px solid #2563eb',
            borderRadius: '4px',
            color: '#2563eb',
            padding: '0.3em 0.8em',
            cursor: 'pointer',
            fontWeight: 500,
            marginLeft: 'auto'
          }}
          onClick={() => {
            // Remove cache for all periods
            ['7d', '30d', '6m', '1y'].forEach(periodKey => {
              localStorage.removeItem(`giftflowwp_chartdata_${periodKey}`);
            });
            // Reload to fetch fresh data
            window.location.reload();
          }}
          title="Clear cached chart data and reload"
        >
          Clear Cache &amp; Reload
        </button>
      </div>
      </div>
    </div>
  );
}
