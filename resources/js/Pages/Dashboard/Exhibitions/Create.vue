<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NewExhibitionForm from "./Partials/NewExhibitionForm.vue";

const props = defineProps({
    statusList: {
        type: Array,
    },
});
</script>

<template>
    <Head title="Edit Exhibition" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100"
            >
                Create Exhibition
            </h2>
        </template>

        <div class="py-4">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <NewExhibitionForm :statusList="statusList" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
