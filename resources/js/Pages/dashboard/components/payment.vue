<template>
    <div class="col-lg-6 col-xl-6 col-xxl-6 mb-3">
      <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
        <div class="card-header d-flex justify-content-between">
          <div class="card-heading">
            <h5 class="card-title">Payment Analysis</h5>
          </div>
        </div>
        <div class="card-body pb-0">
          <div class="sales-chart">
            <Chart type="bar" :data="chartData" :options="chartOptions" class="custom-chart" />
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from "vue";

  const props = defineProps({
    approved_payment: Number,
    pending_payment: Number,
  });

  onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
  });

  const chartData = ref();
  const chartOptions = ref();

  const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
      labels: ["Payments"],
      datasets: [
        {
          type: "bar",
          label: "Approved",
          backgroundColor: documentStyle.getPropertyValue("--cyan-500"),
          data: [props.approved_payment],
        },
        {
          type: "bar",
          label: "Pending",
          backgroundColor: documentStyle.getPropertyValue("--orange-500"),
          data: [props.pending_payment],
        },
      ],
    };
  };

  const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue("--text-color");

    return {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            color: textColor,
            font: {
              size: 14,
            },
          },
          grid: {
            color: documentStyle.getPropertyValue("--gray-300"),
          },
        },
        x: {
          ticks: {
            color: textColor,
            font: {
              size: 14,
            },
          },
          grid: {
            display: false,
          },
        },
      },
      plugins: {
        legend: {
          position: "top",
          labels: {
            color: textColor,
            font: {
              size: 14,
              weight: "bold",
            },
          },
        },
      },
    };
  };
  </script>

  <style scoped>
  .custom-chart {
    width: 100% !important;
    height: 300px !important;
    max-height: 350px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .card {
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background: #fff;
  }

  .card-header {
    background: transparent;
    border-bottom: none;
  }

  .card-title {
    font-size: 18px;
    font-weight: 600;
  }

  .sales-chart {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
  }
  </style>
