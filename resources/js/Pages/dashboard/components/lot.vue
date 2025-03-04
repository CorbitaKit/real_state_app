<template>
    <div class="col-lg-6 col-xl-6 col-xxl-6 mb-3">
      <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
        <div class="card-header d-flex justify-content-between">
          <div class="card-heading">
            <h5 class="card-title">Lot Analysis</h5>
          </div>
        </div>
        <div class="card-body pb-0">
          <div class="sales-chart">
            <Chart type="pie" :data="chartData" :options="chartOptions" class="custom-chart" />
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from "vue";

  const props = defineProps({
    available_lot: Number,
    pending_lot: Number,
    occupied_lot: Number,
  });

  onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
  });

  const chartData = ref();
  const chartOptions = ref(null);

  const setChartData = () => {
    const documentStyle = getComputedStyle(document.body);

    return {
      labels: ["Available", "Pending", "Occupied"],
      datasets: [
        {
          data: [props.available_lot, props.pending_lot, props.occupied_lot],
          backgroundColor: [
            documentStyle.getPropertyValue("--cyan-500"),
            documentStyle.getPropertyValue("--orange-500"),
            documentStyle.getPropertyValue("--gray-500"),
          ],
          hoverBackgroundColor: [
            documentStyle.getPropertyValue("--cyan-400"),
            documentStyle.getPropertyValue("--orange-400"),
            documentStyle.getPropertyValue("--gray-400"),
          ],
        },
      ],
    };
  };

  const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue("--text-color");

    return {
      plugins: {
        legend: {
          position: "bottom", // Move legend to bottom for better alignment
          labels: {
            usePointStyle: true,
            color: textColor,
            font: {
              size: 14, // Adjust legend font size
              weight: "bold",
            },
          },
        },
      },
      maintainAspectRatio: false, // Allow custom sizing
      responsive: true,
    };
  };
  </script>

  <style scoped>
  .custom-chart {
    width: 100% !important;
    height: 300px !important; /* Adjusted height */
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
