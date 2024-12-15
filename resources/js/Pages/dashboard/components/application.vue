<script setup>
import Chart from "primevue/chart";
import { ref } from "vue";
import { onMounted } from "vue";

const props = defineProps({
    rejected_application: Number,
    approved_application: Number,
    for_review_application: Number,
})

const chartData = ref();
const chartOptions = ref(null);

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

return {
    labels: ['Applications'],
    datasets: [
        {
            label: 'For Review',
            backgroundColor: documentStyle.getPropertyValue('--cyan-500'),
            borderColor: documentStyle.getPropertyValue('--cyan-500'),
            data: [props.for_review_application]
        },
        {
            label: 'Approved',
            backgroundColor: documentStyle.getPropertyValue('--gray-500'),
            borderColor: documentStyle.getPropertyValue('--gray-500'),
            data: [props.approved_application]
        },
        {
            label: 'Rejected',
            backgroundColor: documentStyle.getPropertyValue('--gray-500'),
            borderColor: documentStyle.getPropertyValue('--gray-500'),
            data: [props.rejected_application]
        }
    ]
};
};

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        indexAxis: 'y',
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500
                    }
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false
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
            <Chart type="bar" :data="chartData" :options="chartOptions" />
            </div>
        </div>
        </div>
    </div>
</template>
