<script setup>
import { onMounted, onUnmounted, ref } from "vue";

defineProps({
    images: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["delete", "download"]);

// Track which image's overlay is visible
const visibleOverlayId = ref(null);

const handleDelete = (event, imageId) => {
    if (visibleOverlayId.value === imageId) {
        emit("delete", imageId);
    } else {
        event.preventDefault();
        visibleOverlayId.value = imageId;
    }
};

const handleDownload = (event, image) => {
    if (visibleOverlayId.value === image.id) {
        emit("download", image);
    } else {
        event.preventDefault();
        visibleOverlayId.value = image.id;
    }
};

const handleMouseEnter = (imageId) => {
    visibleOverlayId.value = imageId;
};

const handleMouseLeave = () => {
    visibleOverlayId.value = null;
};

const handleOutsideClick = (event) => {
    if (!event.target.closest(".group")) {
        visibleOverlayId.value = null;
    }
};

onMounted(() => {
    document.addEventListener("click", handleOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener("click", handleOutsideClick);
});
</script>

<template>
    <div class="space-y-4">
        <div
            class="flex items-center border-b dark:border-transparent dark:bg-gray-900 py-4 dark:rounded-lg"
        >
            <div class="min-w-20 max-w-32 flex-1 rounded-lg"></div>
            <div class="ml-4 flex-1 text-wrap">
                <p class="font-semibold text-gray-800 dark:text-gray-100">
                    Title
                </p>
            </div>
            <div class="ml-4 flex-1 text-wrap">
                <p
                    class="hidden sm:block font-semibold text-gray-800 dark:text-gray-100"
                >
                    Modified
                </p>
            </div>
            <div class="w-24">
                <p class="font-semibold text-gray-800 dark:text-gray-100">
                    Actions
                </p>
            </div>
        </div>
        <div
            v-for="image in images.data"
            :key="image.id"
            class="flex rounded-lg align-middle items-center border-b dark:border-transparent dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-950 transition-colors group"
            @mouseenter="handleMouseEnter(image.id)"
            @mouseleave="handleMouseLeave"
        >
            <div class="min-w-24 max-w-32 min-h-20 flex-1 shadow-md rounded-lg">
                <div class="relative w-full" style="padding-top: 75%">
                    <img
                        :src="image.url"
                        alt=""
                        class="absolute rounded-lg inset-0 w-full h-full object-cover"
                    />
                </div>
            </div>
            <div class="ml-4 flex-1 text-wrap">
                <p
                    class="block sm:hidden font-sm text-gray-900 dark:text-gray-300 truncate text-ellipsis"
                >
                    {{
                        image.title.length > 15
                            ? image.title.substring(0, 15) + " ... "
                            : image.title
                    }}
                    <!-- {{ image.title }} -->
                </p>
                <p
                    class="hidden sm:block font-sm text-gray-900 dark:text-gray-300 truncate text-ellipsis"
                >
                    {{
                        image.title.length > 20
                            ? image.title.substring(0, 20) + " ... "
                            : image.title
                    }}
                    <!-- {{ image.title }} -->
                </p>
            </div>
            <div class="ml-4 flex-1 text-wrap">
                <p
                    class="hidden sm:block font-sm text-gray-900 dark:text-gray-300"
                >
                    {{ new Date(image.updated_at).toLocaleString() }}
                </p>
            </div>
            <div
                class="relative w-24 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-2 justify-end pr-4"
            >
                <button
                    @pointerup="(e) => handleDelete(e, image.id)"
                    class="p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-gray-600 dark:text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
