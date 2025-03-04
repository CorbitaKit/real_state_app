<script setup>
import { ref, computed } from "vue";
import Chart from "primevue/chart";

const props = defineProps({
  clients: Array, // Expecting an array of clients
});

const clientsPerPage = 1; // Show one client at a time
const currentPage = ref(0);

// Get the current client based on pagination
const paginatedClients = computed(() => {
  return props.clients.slice(currentPage.value, currentPage.value + clientsPerPage);
});

const mappedClients = computed(() => {
  return paginatedClients.value.map(client => ({
    name: client.personal_info
      ? `${client.personal_info.first_name} ${client.personal_info.last_name}`
      : "Unknown Client",
    lots: (client.lots || []).map(lot => {
      const totalPrice = lot.lot_group
        ? lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter
        : 0;
      const totalPaid = (client.payments || [])
        .filter(payment => payment.lot_id === lot.id)
        .reduce((sum, payment) => sum + payment.amount, 0);
      const percentagePaid = totalPrice ? (totalPaid / totalPrice) * 100 : 0;
      return {
        name: lot.name,
        percentagePaid: percentagePaid.toFixed(2),
      };
    }),
  }));
});

// Prepare chart data
const chartData = computed(() => {
  const labels = [];
  const data = [];
  const backgroundColors = [];
  const borderColors = [];

  mappedClients.value.forEach(client => {
    client.lots.forEach(lot => {
      labels.push(`${client.name} - ${lot.name}`);
      data.push(lot.percentagePaid);
      backgroundColors.push("rgba(102, 187, 106, 0.6)"); // Soft green with transparency
      borderColors.push("rgba(76, 175, 80, 1)"); // Darker green for contrast
    });
  });

  return {
    labels,
    datasets: [
      {
        label: "Payment Completion (%)",
        backgroundColor: backgroundColors,
        borderColor: borderColors,
        borderWidth: 2,
        borderRadius: 8, // Smooth rounded corners
        hoverBackgroundColor: "rgba(56, 142, 60, 0.8)", // Darker green on hover
        data,
      },
    ],
  };
});

// Chart options
const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: "top",
      labels: {
        color: "#333",
        font: {
          size: 14,
          weight: "bold",
        },
      },
    },
    tooltip: {
      callbacks: {
        label: (tooltipItem) => `${tooltipItem.raw}% completed`, // Show % sign
      },
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      max: 100,
      ticks: {
        callback: value => `${value}%`, // Show percentage on Y-axis
        font: {
          size: 12,
        },
      },
      grid: {
        color: "rgba(200, 200, 200, 0.3)",
      },
    },
    x: {
      grid: {
        display: false,
      },
    },
  },
});

// Pagination navigation
const nextPage = () => {
  if (currentPage.value + 1 < props.clients.length) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 0) {
    currentPage.value--;
  }
};
</script>

<template>
  <div class="col-lg-6 col-xl-6 col-xxl-6 mb-3">
    <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-heading">
          <h5 class="card-title">Client Payment Analysis</h5>
        </div>
        <div class="pagination-controls">
          <button class="btn btn-sm btn-primary" @click="prevPage" :disabled="currentPage === 0">
            <i class="fa fa-chevron-left"></i>
          </button>
          <span class="px-2">Page {{ currentPage + 1 }} / {{ props.clients.length }}</span>
          <button
            class="btn btn-sm btn-primary"
            @click="nextPage"
            :disabled="currentPage + 1 >= props.clients.length"
          >
            <i class="fa fa-chevron-right"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <div v-if="chartData.labels.length > 0">
          <Chart type="bar" :data="chartData" :options="chartOptions" class="custom-chart" />
        </div>
        <div v-else class="text-center">
          <p class="text-muted">No payment data available.</p>
        </div>
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
