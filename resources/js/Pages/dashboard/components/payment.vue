
<template>

    <div class="col-lg-4 col-xl-4 col-xxl-4 mb-3">
        <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
        <div class="card-header d-flex justify-content-between">
            <div class="card-heading">
            <h5 class="card-title">Payment Analysis</h5>
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

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
    approved_payment: Number,
    pending_payment: Number
})
onMounted(() => {
    chartData.value = setChartData();
});

const chartData = ref();
const chartOptions = ref();


const setChartData = () =>  {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['Payments'],
        datasets: [
            {
                type: 'bar',
                label: 'Approved',
                backgroundColor: documentStyle.getPropertyValue('--cyan-500'),
                data: [12]
            },
            {
                type: 'bar',
                label: 'Pending',
                backgroundColor: documentStyle.getPropertyValue('--orange-500'),
                data: [13]
            },


        ]

    };
};

</script>
