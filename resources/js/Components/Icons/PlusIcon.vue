<script setup>
const props = defineProps({
    class: {
        type: String,
    },
});
</script>

<template>
    <div
        class="relative -top-3/4 left-1 flex items-center justify-center w-4 h-4 rounded-full border-2 border-gray-900 bg-gray-900 transition-opacity duration-300 dark:border-gray-600 dark:bg-gray-800"
        :class="props.class"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M12 4v16M4 12h16" />
        </svg>
    </div>
</template>
