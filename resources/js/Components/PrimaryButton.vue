<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
});
</script>
<template>
    <button
        :type
        class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs uppercase tracking-wider text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none active:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:active:bg-gray-800"
    >
        <slot />
    </button>
</template>
