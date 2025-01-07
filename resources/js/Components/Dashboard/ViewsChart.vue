<!-- resources/js/Components/Dashboard/ViewsChart.vue -->
<script setup>
import { useDarkModeStore } from "@/Store/darkMode";
import { computed } from "vue";
import VueApexCharts from "vue3-apexcharts";

const props = defineProps({
    labels: Array,
    data: Array,
});

const series = computed(() => [
    {
        name: "Views",
        data: props.data,
    },
]);

const theme = useDarkModeStore();

const chartOptions = computed(() => ({
    chart: {
        toolbar: { show: false },
        zoom: { enabled: false },
    },
    colors: ["#6366F1"],
    dataLabels: { enabled: false },
    markers: {
        colors: ["#9C27B0"],
    },
    yaxis: [
        {
            axisTicks: {
                show: true,
            },
            axisBorder: {
                show: true,
                color: "gray",
            },
            labels: {
                style: {
                    colors: theme.isDark ? "white" : "black",
                },
            },
            title: {
                style: {
                    color: theme.isDark ? "white" : "black",
                },
            },
        },
    ],
    stroke: {
        curve: "smooth",
        width: 4,
    },
    xaxis: {
        categories: props.labels,

        axisTicks: {
            show: true,
        },
        axisBorder: {
            show: true,
            color: "gray",
        },
        labels: {
            style: {
                colors: theme.isDark ? "white" : "black",
            },
        },
        title: {
            style: {
                color: theme.isDark ? "white" : "black",
            },
        },
    },
    grid: {
        borderColor: theme.isDark ? "#E2E8F050" : "gray",
    },
    tooltip: {
        theme: "dark",
    },
}));
</script>

<template>
    <div>
        <VueApexCharts
            type="line"
            height="350"
            :options="chartOptions"
            :series="series"
        />
    </div>
</template>
