import { useEffect, useState } from "react";
import { getDashboardStatisticsCharts } from "../ulti/api";
import { Line } from "react-chartjs-2";
import { Ban, ChartColumn, RotateCcw } from 'lucide-react';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  BarController,
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
  BarController,
  Title,
  Tooltip,
  Legend
);

const CHART_COLOR_DONATIONS = { fill: 'rgba(0, 112, 23, 0.55)', stroke: 'rgb(0, 112, 23)' };
const CHART_COLOR_DONORS = { fill: 'rgba(34, 113, 177, 0.5)', stroke: 'rgb(34, 113, 177)' };
const CHART_TEXT = '#1d2327';
const CHART_TEXT_MUTED = '#646970';

const PERIODS = [
  { value: '7d', label: '7 days' },
  { value: '30d', label: '30 days' },
  { value: '6m', label: '6 months' },
];

export default function DonationsChart() {
  const [dataChart, setDataChart] = useState({
    labels: [],
    donationsData: [],
    donorsData: [],
  });
  const [period, setPeriod] = useState('7d');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const currency = typeof giftflow_admin !== 'undefined' ? giftflow_admin.currency_symbol || '$' : '$';

  useEffect(() => {
    let cancelled = false;

    const fetchChartData = async () => {
      try {
        setLoading(true);
        setError(null);

        const cacheKey = `giftflow_chartdata_${period}`;
        const cacheRaw = localStorage.getItem(cacheKey);
        let cache = null;
        if (cacheRaw) {
          try { cache = JSON.parse(cacheRaw); } catch { cache = null; }
        }

        const now = Date.now();
        const FIFTEEN_MINUTES = 15 * 60 * 1000;

        if (cache && cache.timestamp && (now - cache.timestamp < FIFTEEN_MINUTES) && cache.data) {
          if (!cancelled) {
            setDataChart({
              labels: cache.data.labels || [],
              donationsData: cache.data.donationsData || [],
              donorsData: cache.data.donorsData || [],
            });
          }
        } else {
          const response = await getDashboardStatisticsCharts({ period });
          const chartData = response.donations_overview_chart_by_period || response;
          if (!cancelled) {
            setDataChart({
              labels: chartData.labels || [],
              donationsData: chartData.donationsData || [],
              donorsData: chartData.donorsData || [],
            });
          }
          localStorage.setItem(cacheKey, JSON.stringify({
            timestamp: now,
            data: {
              labels: chartData.labels || [],
              donationsData: chartData.donationsData || [],
              donorsData: chartData.donorsData || [],
            }
          }));
        }
      } catch (err) {
        if (!cancelled) {
          console.error('Error fetching chart data:', err);
          setError(err.message || 'Failed to load chart data');
        }
      } finally {
        if (!cancelled) setLoading(false);
      }
    };

    fetchChartData();
    return () => { cancelled = true; };
  }, [period]);

  const clearCache = () => {
    ['7d', '30d', '6m', '1y'].forEach(k => localStorage.removeItem(`giftflow_chartdata_${k}`));
    window.location.reload();
  };

  // ---- Loading state ----
  if (loading) {
    return (
      <div className="giftflow-chart-container">
        <Header period={period} setPeriod={setPeriod} />
        <div className="giftflow-chart-loading">
          <div className="giftflow-chart-loading__spinner" />
          <p>Loading chart data...</p>
        </div>
      </div>
    );
  }

  // ---- Error state ----
  if (error) {
    return (
      <div className="giftflow-chart-container">
        <Header period={period} setPeriod={setPeriod} />
        <div className="giftflow-chart-error">
          <div className="giftflow-chart-error__icon">
            <Ban size={24} strokeWidth={1.75} aria-hidden="true" />
          </div>
          <h4>Chart Error</h4>
          <p>{error}</p>
          <button className="giftflow-chart-error__retry" onClick={() => window.location.reload()}>
            Retry
          </button>
        </div>
      </div>
    );
  }

  // ---- Empty state ----
  const isEmpty =
    !dataChart.labels.length ||
    ((!dataChart.donationsData || Object.keys(dataChart.donationsData).length === 0) &&
     (!dataChart.donorsData || Object.keys(dataChart.donorsData).length === 0));

  if (isEmpty) {
    return (
      <div className="giftflow-chart-container">
        <Header period={period} setPeriod={setPeriod} />
        <div className="giftflow-chart-empty">
          <div className="giftflow-chart-empty__icon">
            <ChartColumn size={24} strokeWidth={1.75} aria-hidden="true" />
          </div>
          <h4>No Data Available</h4>
          <p>No donation data found for the selected period. Try selecting a different time range or check back later.</p>
        </div>
      </div>
    );
  }

  // ---- Data state ----
  const data = {
    labels: dataChart.labels,
    datasets: [
      {
        type: "bar",
        label: "Donation Amount",
        data: dataChart.donationsData,
        backgroundColor: CHART_COLOR_DONATIONS.fill,
        borderColor: CHART_COLOR_DONATIONS.stroke,
        borderWidth: 1,
        yAxisID: "y",
        order: 1,
      },
      {
        type: "bar",
        label: "Donors",
        data: dataChart.donorsData,
        backgroundColor: CHART_COLOR_DONORS.fill,
        borderColor: CHART_COLOR_DONORS.stroke,
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
      title: { display: false },
      legend: {
        display: true,
        position: 'bottom',
        labels: {
          usePointStyle: true,
          padding: 16,
          font: { size: 12 },
          color: CHART_TEXT_MUTED,
        },
      },
      tooltip: {
        backgroundColor: 'rgba(29, 35, 39, 0.92)',
        titleColor: '#fff',
        bodyColor: '#f0f0f1',
        borderColor: '#50575e',
        borderWidth: 1,
        cornerRadius: 4,
        displayColors: true,
        callbacks: {
          label: function(context) {
            if (context.datasetIndex === 0) {
              return `Donations: ${currency}${context.parsed.y.toFixed(2)}`;
            }
            return `Donors: ${context.parsed.y}`;
          }
        }
      }
    },
    scales: {
      x: {
        display: true,
        grid: { display: false },
        ticks: {
          color: CHART_TEXT_MUTED,
          maxRotation: 45,
          minRotation: 0,
          font: { size: 11 },
        },
      },
      y: {
        type: "linear",
        display: true,
        position: "left",
        title: {
          display: true,
          text: `Amount (${currency})`,
          color: CHART_TEXT_MUTED,
          font: { size: 11, weight: 'bold' },
        },
        grid: {
          color: 'rgba(100, 105, 112, 0.15)',
          drawBorder: false,
        },
        ticks: {
          color: CHART_TEXT_MUTED,
          font: { size: 11 },
          callback: function(value) {
            return currency + value.toFixed(0);
          }
        }
      },
      y1: {
        type: "linear",
        display: true,
        position: "right",
        title: {
          display: true,
          text: "Donors",
          color: CHART_TEXT_MUTED,
          font: { size: 11, weight: 'bold' },
        },
        grid: {
          drawOnChartArea: false,
          drawBorder: false,
        },
        ticks: {
          color: CHART_TEXT_MUTED,
          font: { size: 11 },
          stepSize: 1,
        }
      },
    },
  };

  return (
    <div className="giftflow-chart-container">
      <Header period={period} setPeriod={setPeriod} />
      <div className="giftflow-chart-wrapper">
        <Line data={data} options={options} />
      </div>
      <div className="giftflow-chart-footer">
        <span className="giftflow-chart-footer__legend">
          <span className="giftflow-chart-footer__legend-swatch giftflow-chart-footer__legend-swatch--donations" aria-hidden="true" />
          Donation amount (completed only)
        </span>
        <span className="giftflow-chart-footer__legend">
          <span className="giftflow-chart-footer__legend-swatch giftflow-chart-footer__legend-swatch--donors" aria-hidden="true" />
          New donors
        </span>
        <span className="giftflow-chart-footer__cache">
          Cached for 15 min
          <button type="button" className="giftflow-chart-footer__refresh-btn" onClick={clearCache} title="Clear cache and refresh">
            <RotateCcw size={13} strokeWidth={2} /> Refresh
          </button>
        </span>
      </div>
    </div>
  );
}

function Header({ period, setPeriod }) {
  return (
    <div className="giftflow-chart-header">
      <h3 className="giftflow-chart-header__title">Donations &amp; Donors Overview</h3>
      <div className="giftflow-chart-header__periods">
        {PERIODS.map(p => (
          <button
            key={p.value}
            className={`giftflow-chart-header__period${period === p.value ? ' giftflow-chart-header__period--active' : ''}`}
            onClick={() => setPeriod(p.value)}
          >
            {p.label}
          </button>
        ))}
      </div>
    </div>
  );
}
