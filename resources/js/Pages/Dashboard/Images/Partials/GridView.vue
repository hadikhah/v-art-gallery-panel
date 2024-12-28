<script setup>
import { onMounted, onUnmounted, ref } from "vue";

const props = defineProps({
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
    <div
        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3"
    >
        <div
            v-for="image in images.data"
            :key="image.id"
            class="group overflow-hidden rounded-lg shadow-md transition-transform transform hover:scale-105"
            @mouseenter="handleMouseEnter(image.id)"
            @mouseleave="handleMouseLeave"
        >
            <div class="relative w-full" style="padding-top: 75%">
                <img
                    :src="image.url"
                    alt=""
                    class="absolute inset-0 w-full h-full object-cover"
                />
                <!-- Overlay with gradient -->
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/70 opacity-0 group-hover:opacity-100 transition-opacity overlay"
                    :class="{ 'opacity-100': visibleOverlayId === image.id }"
                >
                    <div
                        class="absolute bottom-2 left-2 text-white text-sm font-medium"
                    >
                        {{ image.title }}
                    </div>
                    <div class="absolute bottom-2 right-2 flex space-x-2">
                        <button
                            @pointerup="(e) => handleDelete(e, image.id)"
                            class="p-1.5 rounded-full bg-white/20 hover:bg-white/40 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-white"
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
        </div>
    </div>
</template>

<style scoped>
@media (hover: none) {
    /* Styles for touch devices */
    .group .overlay {
        pointer-events: none;
    }

    .group .overlay.opacity-100 {
        pointer-events: auto;
    }
}
</style>
