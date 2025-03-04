<script setup>
import { ref, computed } from "vue";
import Chart from "primevue/chart";
import moment from "moment";
import Calendar from "primevue/calendar";

const props = defineProps({
  daily: Array,
  weekly: Array,
  monthly: Array,
});

const periods = ["Daily", "Weekly", "Monthly"];
const selectedPeriod = ref("Daily");
const selectedDate = ref(new Date()); // Default to today

// Function to get the start date based on the selected period
const getStartDate = () => {
  if (selectedPeriod.value === "Daily") return moment(selectedDate.value).startOf("day");
  if (selectedPeriod.value === "Weekly") return moment(selectedDate.value).startOf("isoWeek");
  return moment(selectedDate.value).startOf("month");
};

// Function to get the end date based on the selected period
const getEndDate = () => {
  if (selectedPeriod.value === "Daily") return moment(selectedDate.value).endOf("day");
  if (selectedPeriod.value === "Weekly") return moment(selectedDate.value).endOf("isoWeek");
  return moment(selectedDate.value).endOf("month");
};

// Filter sales data based on the selected period and date range
const filteredSales = computed(() => {
  return props[selectedPeriod.value.toLowerCase()]?.filter((sale) => {
    const saleDate = moment(sale.date_of_payment);
    return saleDate.isBetween(getStartDate(), getEndDate(), null, "[]");
  }) || [];
});

// Group sales based on selected period
const groupSalesData = computed(() => {
  const groupedSales = {};

  if (selectedPeriod.value === "Daily") {
    // Group by weekdays (Sunday, Monday, etc.)
    ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"].forEach(day => {
      groupedSales[day] = 0;
    });

    filteredSales.value.forEach((sale) => {
      const dayOfWeek = moment(sale.date_of_payment).format("dddd");
      groupedSales[dayOfWeek] += sale.amount;
    });

  } else if (selectedPeriod.value === "Weekly") {
    // Group into 4 weeks (Week 1, Week 2, etc.)
    for (let i = 1; i <= 4; i++) {
      groupedSales[`Week ${i}`] = 0;
    }

    filteredSales.value.forEach((sale) => {
      const weekNumber = moment(sale.date_of_payment).isoWeek() % 4 || 4;
      groupedSales[`Week ${weekNumber}`] += sale.amount;
    });

  } else {
    // Group by months (January, February, etc.)
    moment.months().forEach(month => {
      groupedSales[month] = 0;
    });

    filteredSales.value.forEach((sale) => {
      const monthName = moment(sale.date_of_payment).format("MMMM");
      groupedSales[monthName] += sale.amount;
    });
  }

  return groupedSales;
});

// Prepare chart data
const chartData = computed(() => {
  return {
    labels: Object.keys(groupSalesData.value),
    datasets: [
      {
        label: "Sales Amount",
        backgroundColor: "#42A5F5",
        data: Object.values(groupSalesData.value),
      },
    ],
  };
});

// Chart options
const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      labels: {
        color: "#333",
      },
    },
  },
});

// Navigate between dates
const changeDate = (direction) => {
  if (selectedPeriod.value === "Daily") {
    selectedDate.value = moment(selectedDate.value).add(direction, "days").toDate();
  } else if (selectedPeriod.value === "Weekly") {
    selectedDate.value = moment(selectedDate.value).add(direction, "weeks").toDate();
  } else {
    selectedDate.value = moment(selectedDate.value).add(direction, "months").toDate();
  }
};

// Change period and reset date
const changePeriod = (period) => {
  selectedPeriod.value = period;
  selectedDate.value = new Date(); // Reset to today
};
</script>

<template>
  <div class="col-lg-6 col-xl-6 col-xxl-6 mb-3">
    <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-heading">
          <h5 class="card-title">Income Analysis</h5>
        </div>
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-primary btn-sm mx-2" @click="changeDate(-1)">
            <i class="fa fa-chevron-left"></i>
          </button>
          <Calendar v-model="selectedDate" dateFormat="yy-mm-dd" class="mx-2" />
          <button class="btn btn-outline-primary btn-sm mx-2" @click="changeDate(1)">
            <i class="fa fa-chevron-right"></i>
          </button>
          <div class="dropdown mx-2">
            <a class="btn btn-outline-secondary dropdown-toggle" href="#" data-toggle="dropdown">
              {{ selectedPeriod }}
            </a>
            <div class="dropdown-menu">
              <a
                class="dropdown-item"
                v-for="period in periods"
                :key="period"
                @click="changePeriod(period)"
              >
                {{ period }}
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <Chart type="bar" :data="chartData" :options="chartOptions" />
      </div>
    </div>
  </div>
</template>

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
  display: flex;
  justify-content: space-between;
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

.pagination-controls {
  display: flex;
  align-items: center;
}

.btn-primary {
  background-color: #007bff;
  border: none;
}

.btn-primary:disabled {
  background-color: #d6d6d6;
  cursor: not-allowed;
}
</style>
