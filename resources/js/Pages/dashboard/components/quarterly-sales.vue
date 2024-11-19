
<template>
    <div class="card">
        <Chart type="bar" :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
    sales: Array // Expecting sales to be an array of objects
});

const chartData = ref();
const chartOptions = ref();

onMounted(() => {
    chartData.value = setChartData(props.sales); // Pass the sales prop to setChartData
    chartOptions.value = setChartOptions();
});

const setChartData = (sales) => {
    // Initialize sales amounts for each quarter
    const salesData = [0, 0, 0, 0]; // Q1, Q2, Q3, Q4

    // Loop through sales data to accumulate amounts for each quarter
    props.sales.forEach(sale => {
        const quarter = sale.quarter; // Assuming sale object has a 'quarter' property
        const amount = sale.total_sales; // Assuming sale object has a 'total_sales' property

        if (quarter >= 1 && quarter <= 4) {
            salesData[quarter - 1] += amount; // Add to the respective quarter
        }
    });

    return {
        labels: ['Q1', 'Q2', 'Q3', 'Q4'],
        datasets: [
            {
                label: 'Sales',
                data: salesData, // Use the calculated sales data
                backgroundColor: [
                    'rgba(249, 115, 22, 0.2)', 
                    'rgba(6, 182, 212, 0.2)', 
                    'rgb(107, 114, 128, 0.2)', 
                    'rgba(139, 92, 246, 0.2)'
                ],
                borderColor: [
                    'rgb(249, 115, 22)', 
                    'rgb(6, 182, 212)', 
                    'rgb(107, 114, 128)', 
                    'rgb(139, 92, 246)'
                ],
                borderWidth: 1
            }
        ]
    };
};

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
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
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
};
</script>
