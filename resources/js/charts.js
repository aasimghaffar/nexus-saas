/**
 * Nexus SaaS Admin - ApexCharts Initializations & Configurations
 * Supports dynamic theme switching (Dark / Light) without reloading.
 */

document.addEventListener('DOMContentLoaded', () => {
  // Server-injected live data (Phase 5). Each key falls back to the demo series.
  const LIVE = window.NexusChartData || {};

  if (typeof ApexCharts === 'undefined') {
    console.warn('ApexCharts library not found. Charts will not render.');
    return;
  }

  const isDarkMode = () => document.documentElement.classList.contains('dark');

  // Common Theme Options
  const getThemeOptions = () => ({
    mode: isDarkMode() ? 'dark' : 'light',
    palette: 'palette1',
  });

  const getGridColor = () => isDarkMode() ? '#334155' : '#E2E8F0';
  const getTextColor = () => isDarkMode() ? '#94A3B8' : '#64748B';

  const chartInstances = {};

  /* ==========================================================================
     1. Dashboard 1: SaaS Main Overview (MRR & Revenue)
     ========================================================================== */
  const mrrChartEl = document.getElementById('chart-revenue-mrr');
  if (mrrChartEl) {
    const mrrOptions = {
      series: LIVE.mrr || [
        {
          name: 'Gross MRR ($)',
          data: [28400, 31200, 34800, 38100, 42900, 47500, 53200, 58400, 64100, 69800, 75400, 84200]
        },
        {
          name: 'Net ARR ($ in 10s)',
          data: [22000, 24500, 27300, 30100, 34200, 39000, 43800, 48200, 52900, 57400, 63100, 71500]
        }
      ],
      chart: {
        type: 'area',
        height: 320,
        toolbar: { show: false },
        zoom: { enabled: false },
        fontFamily: 'inherit',
        background: 'transparent'
      },
      colors: ['#4F46E5', '#10B981'],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.45,
          opacityTo: 0.05,
          stops: [20, 100]
        }
      },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 2.5 },
      xaxis: {
        categories: LIVE.mrrCategories || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        labels: { style: { colors: getTextColor(), fontSize: '12px' } },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: {
        labels: {
          formatter: (val) => '$' + (val / 1000).toFixed(0) + 'k',
          style: { colors: getTextColor(), fontSize: '12px' }
        }
      },
      grid: {
        borderColor: getGridColor(),
        strokeDashArray: 4,
        yaxis: { lines: { show: true } }
      },
      tooltip: {
        theme: isDarkMode() ? 'dark' : 'light',
        y: { formatter: (val) => '$' + val.toLocaleString() }
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        labels: { colors: getTextColor() }
      }
    };
    chartInstances.mrrChart = new ApexCharts(mrrChartEl, mrrOptions);
    chartInstances.mrrChart.render();
  }

  // Plan Breakdown Donut Chart
  const planDonutEl = document.getElementById('chart-plan-distribution');
  if (planDonutEl) {
    const planDonutOptions = {
      series: LIVE.plans || [45, 32, 18, 5],
      labels: LIVE.planLabels || ['Enterprise Pro', 'Team Growth', 'Starter Core', 'Legacy Custom'],
      chart: {
        type: 'donut',
        height: 280,
        fontFamily: 'inherit',
        background: 'transparent'
      },
      colors: ['#4F46E5', '#6366F1', '#06B6D4', '#F59E0B'],
      dataLabels: { enabled: false },
      legend: {
        position: 'bottom',
        labels: { colors: getTextColor() }
      },
      stroke: {
        colors: [isDarkMode() ? '#1e293b' : '#ffffff'],
        width: 2
      },
      plotOptions: {
        pie: {
          donut: {
            size: '72%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total Customers',
                formatter: () => '2,840',
                color: isDarkMode() ? '#F1F5F9' : '#1E293B'
              }
            }
          }
        }
      },
      tooltip: {
        theme: isDarkMode() ? 'dark' : 'light',
        y: { formatter: (val) => val + '%' }
      }
    };
    chartInstances.planDonut = new ApexCharts(planDonutEl, planDonutOptions);
    chartInstances.planDonut.render();
  }

  /* ==========================================================================
     2. Dashboard 2: CRM & Sales Pipeline
     ========================================================================== */
  const crmPipelineEl = document.getElementById('chart-crm-pipeline');
  if (crmPipelineEl) {
    const crmPipelineOptions = {
      series: LIVE.pipeline || [
        {
          name: 'Deals Count',
          data: [142, 98, 64, 42, 28]
        }
      ],
      chart: {
        type: 'bar',
        height: 300,
        toolbar: { show: false },
        fontFamily: 'inherit',
        background: 'transparent'
      },
      plotOptions: {
        bar: {
          borderRadius: 6,
          horizontal: true,
          distributed: true,
          barHeight: '60%',
          dataLabels: { position: 'bottom' }
        }
      },
      colors: ['#6366F1', '#3B82F6', '#06B6D4', '#10B981', '#F59E0B'],
      dataLabels: {
        enabled: true,
        textAnchor: 'start',
        style: { colors: ['#ffffff'], fontSize: '12px', fontWeight: 600 },
        formatter: (val, opt) => opt.w.globals.labels[opt.dataPointIndex] + ': ' + val + ' deals',
        offsetX: 10
      },
      xaxis: {
        categories: ['Discovery / Leads', 'Proposal Sent', 'Technical Demo', 'Negotiation', 'Closed Won'],
        labels: { style: { colors: getTextColor() } }
      },
      yaxis: { labels: { show: false } },
      grid: {
        borderColor: getGridColor(),
        strokeDashArray: 4
      },
      tooltip: {
        theme: isDarkMode() ? 'dark' : 'light',
        y: { formatter: (val) => val + ' active opportunities' }
      },
      legend: { show: false }
    };
    chartInstances.crmPipeline = new ApexCharts(crmPipelineEl, crmPipelineOptions);
    chartInstances.crmPipeline.render();
  }

  // Monthly Deal Revenue Bar Chart
  const dealRevenueEl = document.getElementById('chart-deal-revenue');
  if (dealRevenueEl) {
    const dealRevenueOptions = {
      series: LIVE.deals || [
        { name: 'Won Deals ($)', data: [45000, 52000, 48000, 61000, 73000, 89000, 95000] },
        { name: 'Lost Deals ($)', data: [12000, 15000, 9000, 14000, 11000, 16000, 13000] }
      ],
      chart: {
        type: 'bar',
        height: 300,
        toolbar: { show: false },
        fontFamily: 'inherit',
        background: 'transparent'
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '45%',
          borderRadius: 4
        }
      },
      colors: ['#10B981', '#F43F5E'],
      dataLabels: { enabled: false },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        labels: { style: { colors: getTextColor() } }
      },
      yaxis: {
        labels: {
          formatter: (val) => '$' + (val / 1000) + 'k',
          style: { colors: getTextColor() }
        }
      },
      grid: { borderColor: getGridColor(), strokeDashArray: 4 },
      legend: { position: 'top', labels: { colors: getTextColor() } },
      tooltip: {
        theme: isDarkMode() ? 'dark' : 'light',
        y: { formatter: (val) => '$' + val.toLocaleString() }
      }
    };
    chartInstances.dealRevenue = new ApexCharts(dealRevenueEl, dealRevenueOptions);
    chartInstances.dealRevenue.render();
  }

  /* ==========================================================================
     3. Dashboard 3: Analytics & Traffic Metrics
     ========================================================================== */
  const trafficOverviewEl = document.getElementById('chart-traffic-overview');
  if (trafficOverviewEl) {
    const trafficOptions = {
      series: LIVE.traffic || [
        {
          name: 'Unique Visitors',
          data: [12400, 14200, 16800, 15400, 18900, 22400, 25800, 29100, 31400, 34200, 38900, 44500]
        },
        {
          name: 'Pageviews',
          data: [35000, 39000, 48000, 43000, 56000, 68000, 79000, 88000, 96000, 108000, 122000, 145000]
        }
      ],
      chart: {
        type: 'line',
        height: 320,
        toolbar: { show: false },
        fontFamily: 'inherit',
        background: 'transparent'
      },
      colors: ['#3B82F6', '#8B5CF6'],
      stroke: { curve: 'smooth', width: [3, 2], dashArray: [0, 4] },
      dataLabels: { enabled: false },
      xaxis: {
        categories: ['1 Aug', '3 Aug', '6 Aug', '9 Aug', '12 Aug', '15 Aug', '18 Aug', '21 Aug', '24 Aug', '27 Aug', '30 Aug', '31 Aug'],
        labels: { style: { colors: getTextColor() } }
      },
      yaxis: {
        labels: {
          formatter: (val) => (val / 1000).toFixed(0) + 'k',
          style: { colors: getTextColor() }
        }
      },
      grid: { borderColor: getGridColor(), strokeDashArray: 4 },
      legend: { position: 'top', horizontalAlign: 'right', labels: { colors: getTextColor() } },
      tooltip: { theme: isDarkMode() ? 'dark' : 'light' }
    };
    chartInstances.trafficOverview = new ApexCharts(trafficOverviewEl, trafficOptions);
    chartInstances.trafficOverview.render();
  }

  // Device Usage Donut
  const deviceChartEl = document.getElementById('chart-device-breakdown');
  if (deviceChartEl) {
    const deviceOptions = {
      series: LIVE.devices || [58, 32, 10],
      labels: LIVE.deviceLabels || ['Desktop (Chrome/Safari)', 'Mobile (iOS/Android)', 'Tablet & Others'],
      chart: {
        type: 'donut',
        height: 260,
        fontFamily: 'inherit',
        background: 'transparent'
      },
      colors: ['#4F46E5', '#06B6D4', '#E2E8F0'],
      legend: { position: 'bottom', labels: { colors: getTextColor() } },
      stroke: { colors: [isDarkMode() ? '#1e293b' : '#ffffff'], width: 2 },
      plotOptions: {
        pie: {
          donut: {
            size: '70%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total Sessions',
                formatter: () => '142.8k',
                color: isDarkMode() ? '#F1F5F9' : '#1E293B'
              }
            }
          }
        }
      },
      tooltip: { theme: isDarkMode() ? 'dark' : 'light', y: { formatter: (val) => val + '%' } }
    };
    chartInstances.deviceChart = new ApexCharts(deviceChartEl, deviceOptions);
    chartInstances.deviceChart.render();
  }

  /* ==========================================================================
     4. Sparkline Mini Charts
     ========================================================================== */
  document.querySelectorAll('.sparkline-chart').forEach((el, index) => {
    const dataSets = [
      [12, 18, 14, 22, 19, 28, 34],
      [42, 38, 35, 29, 24, 22, 18],
      [10, 15, 12, 18, 20, 26, 32],
      [5, 9, 14, 12, 19, 25, 30]
    ];
    const colors = ['#10B981', '#F43F5E', '#3B82F6', '#8B5CF6'];
    const data = dataSets[index % dataSets.length];
    const color = colors[index % colors.length];

    const sparkOptions = {
      series: [{ data }],
      chart: {
        type: 'line',
        width: 100,
        height: 35,
        sparkline: { enabled: true },
        background: 'transparent'
      },
      stroke: { curve: 'smooth', width: 2 },
      colors: [color],
      tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } }
    };
    new ApexCharts(el, sparkOptions).render();
  });

  /* ==========================================================================
     5. Re-render / Update on Theme Switch
     ========================================================================== */
  window.addEventListener('nexusThemeChanged', () => {
    const dark = isDarkMode();
    const newTextColor = dark ? '#94A3B8' : '#64748B';
    const newGridColor = dark ? '#334155' : '#E2E8F0';

    Object.values(chartInstances).forEach(chart => {
      if (chart && typeof chart.updateOptions === 'function') {
        chart.updateOptions({
          theme: { mode: dark ? 'dark' : 'light' },
          grid: { borderColor: newGridColor },
          xaxis: { labels: { style: { colors: newTextColor } } },
          yaxis: { labels: { style: { colors: newTextColor } } },
          legend: { labels: { colors: newTextColor } }
        });
      }
    });
  });
});
