
<template>

    <div class="col-lg-4 col-xl-4 col-xxl-4 mb-3">
        <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
        <div class="card-header d-flex justify-content-between">
            <div class="card-heading">
            <h5 class="card-title">Lot Analysis</h5>
            </div>

        </div>
        <div class="card-body pb-0">
            <div class="sales-chart">
            <Chart type="pie" :data="chartData" :options="chartOptions" />
            </div>
        </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
    available_lot : Number,
    pending_lot : Number,
    occupied_lot : Number,
})
onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const chartData = ref();
const chartOptions = ref(null);

const setChartData = () => {
    const documentStyle = getComputedStyle(document.body);

    return {
        labels: ['Available', 'Pending', 'Occupied'],
        datasets: [
            {
                data: [props.available_lot, props.pending_lot, [props.occupied_lot]],
                backgroundColor: [documentStyle.getPropertyValue('--cyan-500'), documentStyle.getPropertyValue('--orange-500'), documentStyle.getPropertyValue('--gray-500')],
                hoverBackgroundColor: [documentStyle.getPropertyValue('--cyan-400'), documentStyle.getPropertyValue('--orange-400'), documentStyle.getPropertyValue('--gray-400')]
            }
        ]
    };
};

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');

    return {
        plugins: {
            legend: {
                labels: {
                    usePointStyle: true,
                    color: textColor
                }
            }
        }
    };
};
</script>
