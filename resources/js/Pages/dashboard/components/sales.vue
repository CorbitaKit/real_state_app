<script setup>
import { ref, watch } from "vue";
import Chart from "primevue/chart";

const props = defineProps({
  sales: Object
})
// Period options
const periods = ["Daily", "Weekly", "Monthly", "Quarterly", "Yearly"];
const selectedPeriod = ref("Daily");

// Reactive chart data and options
const chartData = ref({});
const chartOptions = ref({});

// Dummy Sales Data
const salesData = ref([
  {
    daily: [500, 700, 800, 450, 900, 1200, 950], // Monday to Sunday
    weekly: [3000, 4000, 4500, 3200], // Week 1 to Week 4
    monthly: [12000, 15000, 17000, 14000, 18000, 20000, 21000, 19000, 22000, 24000, 26000, 25000], // Jan to Dec
    quarterly: [45000, 52000, 60000, 58000], // Q1 to Q4
    yearly: [
      { year: "2021", total_sales: 200000 },
      { year: "2022", total_sales: 240000 },
      { year: "2023", total_sales: 280000 },
      { year: "2024", total_sales: 29000 },
    ],
  },
]);

// Generate chart data based on the selected period
const setChartData = (sales, period) => {
  const labels = [];
  const data = [];

  switch (period) {
    case "Daily":
      labels.push(...["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]);
      data.push(...sales.daily);
      break;

    case "Weekly":
      labels.push(...["Week 1", "Week 2", "Week 3", "Week 4"]);
      data.push(...sales.weekly);
      break;

    case "Monthly":
      labels.push(...["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]);
      data.push(...sales.monthly);
      break;

    case "Quarterly":
      labels.push(...["Q1", "Q2", "Q3", "Q4"]);
      data.push(...sales.quarterly);
      break;

    case "Yearly":
      sales.yearly.forEach((yearData) => {
        labels.push(yearData.year);
        data.push(yearData.total_sales);
      });
      break;

    default:
      break;
  }

  return {
    labels,
    datasets: [
      {
        label: `${period} Sales`,
        data,
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
      },
    ],
  };
};

// Chart options for styling
const setChartOptions = () => ({
  responsive: true,
  plugins: {
    legend: {
      display: true,
      position: "top",
    },
  },
  scales: {
    x: {
      grid: {
        display: false,
      },
    },
    y: {
      beginAtZero: true,
      ticks: {
        callback: (value) => `₱${value.toLocaleString()}`, // Add Peso sign
      },
    },
  },
});

// Watch for changes in the selected period and update the chart
watch(
  selectedPeriod,
  () => {
    chartData.value = setChartData(salesData.value[0], selectedPeriod.value);
    chartOptions.value = setChartOptions();
  },
  { immediate: true }
);

// Function to handle period changes
const changePeriod = (period) => {
  selectedPeriod.value = period;
};
</script>

<template>
  <div class="col-lg-7 col-xl-8 col-xxl-5 mb-3">
    <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
      <div class="card-header d-flex justify-content-between">
        <div class="card-heading">
          <h5 class="card-title">Income Analysis</h5>
        </div>
        <div class="dropdown">
          <a
            class="p-2"
            href="#!"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="fe fe-more-horizontal"></i>
          </a>
          <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
            <h6 class="mb-1">Action</h6>
            <a
              class="dropdown-item"
              href="#!"
              v-for="period in periods"
              :key="period"
              @click="changePeriod(period)"
            >
              <i class="fa-fw far fa-file-alt pr-2"></i>{{ period }} View
            </a>
          </div>
        </div>
      </div>
      <div class="card-body pb-0">
        <div class="sales-chart">
          <Chart type="bar" :data="chartData" :options="chartOptions" />
        </div>
      </div>
    </div>
  </div>
</template>
