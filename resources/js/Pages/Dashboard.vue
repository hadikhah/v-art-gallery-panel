<!-- resources/js/Pages/Dashboard/Index.vue -->
<script setup>
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import StatsCard from "@/Components/Dashboard/StatsCard.vue";
import ViewsChart from "@/Components/Dashboard/ViewsChart.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    userDetails: {
        type: Object,
    },
    exhibitionViewsChart: Object,
    period: {
        type: String,
        default: "day",
    },
});

// Reactive state for the selected period
const selectedPeriod = ref(props.period);

const page = usePage();
const locale = page.props.locale;

// Function to handle period change
const handlePeriodChange = (period) => {
    selectedPeriod.value = period;
    router.get(
        route("dashboard", { lang: locale }),
        { period },
        {
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <Head title="dashboard" />

    <AuthenticatedLayout title="Dashboard">
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4"
                >
                    <StatsCard
                        title="Total Images"
                        :value="userDetails.images_count ?? 0"
                        icon="image"
                    />
                    <StatsCard
                        title="Total Exhibitions"
                        :value="userDetails.exhibitions_count ?? 0"
                        icon="gallery"
                    />
                    <StatsCard
                        title="Total Exhibitions Views"
                        :value="userDetails.exhibition_view_rate_sum_views ?? 0"
                        icon="gallery"
                    />
                </div>

                <!-- Chart -->
                <div
                    class="mt-6 overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                            >
                                Exhibition Views
                            </h3>
                            <!-- Period Filter Dropdown -->
                            <div class="relative">
                                <select
                                    v-model="selectedPeriod"
                                    @change="
                                        handlePeriodChange($event.target.value)
                                    "
                                    class="block w-full px-4 py-2 pr-8 leading-tight bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="day">Last 24 Hours</option>
                                    <option value="week">Last Week</option>
                                    <option value="month">Last Month</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
                                >
                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <ViewsChart
                            :labels="
                                Object.keys(exhibitionViewsChart).map((item) =>
                                    period === 'day' ? item.split(' ')[1] : item
                                )
                            "
                            :data="Object.values(exhibitionViewsChart)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
