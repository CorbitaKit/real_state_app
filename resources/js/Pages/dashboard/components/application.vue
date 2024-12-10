<script setup>
import Chart from "primevue/chart";
import { ref } from "vue";
import { onMounted } from "vue";

const props = defineProps({
    lot_application: Number,
    site_visit: Number
})

const chartData = ref();
const chartOptions = ref(null);

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const setChartData = () => {
    const documentStyle = getComputedStyle(document.body);

    return {
        labels: ['Lot Application', 'Site Visit'],
        datasets: [
            {
                data: [props.lot_application, props.site_visit],
                backgroundColor: [documentStyle.getPropertyValue('--green-600'), documentStyle.getPropertyValue('--orange-500'), documentStyle.getPropertyValue('--gray-500')],
                hoverBackgroundColor: [documentStyle.getPropertyValue('--green-300'), documentStyle.getPropertyValue('--orange-300'), documentStyle.getPropertyValue('--gray-400')]
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
                    cutout: '60%',
                    color: textColor
                }
            }
        }
    };
};
</script>

<template>
    <div class="col-lg-4 col-xl-4 col-xxl-4 mb-3">
        <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
        <div class="card-header d-flex justify-content-between">
            <div class="card-heading">
            <h5 class="card-title">Application Analysis</h5>
            </div>
            
        </div>
        <div class="card-body pb-0">
            <div class="sales-chart">
            <Chart type="doughnut" :data="chartData" :options="chartOptions" />
            </div>
        </div>
        </div>
    </div>
</template>