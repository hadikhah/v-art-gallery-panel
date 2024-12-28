<script setup>
defineProps({
    count: Number,
    grid: {
        type: Boolean,
        default: () => false,
    },
});
</script>

<template>
    <div
        class="animate-pulse p-4 space-y-4"
        :class="[
            grid
                ? 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3'
                : '',
        ]"
        v-if="count"
    >
        <div
            class="h-4 bg-neutral-200 rounded w-3/4 dark:bg-neutral-700"
            v-show="!grid"
        ></div>
        <template v-for="index in count" :key="index">
            <div
                class="group bg-neutral-200 rounded dark:bg-neutral-700"
                :class="{
                    'h-28': count && grid,
                    'h-6': (count && !grid) || (!count && !grid),
                }"
            ></div>
        </template>
    </div>
    <div v-else>
        <div class="animate-pulse p-4 space-y-4">
            <div
                class="h-4 bg-neutral-200 rounded w-3/4 dark:bg-neutral-700"
            ></div>
            <div class="h-6 bg-neutral-200 rounded dark:bg-neutral-700"></div>
            <div class="h-6 bg-neutral-200 rounded dark:bg-neutral-700"></div>
        </div>
    </div>
</template>

<style></style>
