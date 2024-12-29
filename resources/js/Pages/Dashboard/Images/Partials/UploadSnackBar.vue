<!-- UploadProgressSnackbar.vue -->
<script setup>
import { useImageUploadStore } from "@/Store/imageUploadStore";
import { ref, computed } from "vue";

const props = defineProps({
    uploads: {
        type: Array,
        default: () => [],
    },
    progressPercentage: {
        type: Number,
    },
});

const rerender = ref(0);

function childMethod() {
    rerender.value++;
}

defineExpose({
    childMethod,
});

const imageUploadStore = useImageUploadStore();

const isMinimized = ref(false);

const getStatusColor = (status) => {
    switch (status) {
        case "error":
            return "border-red-500 bg-red-50 dark:bg-red-950 dark:border-red-800";
        case "success":
            return "border-green-500 bg-green-50 dark:bg-green-950 dark:border-green-800";
        case "waiting":
            return "border-yellow-500 bg-yellow-50 dark:bg-yellow-950 dark:border-yellow-800";
        default:
            return "border-blue-500 bg-blue-50 dark:bg-blue-950 dark:border-blue-800";
    }
};

const getStatusText = (item) => {
    if (item.error) return item.error;
    if (item.status === "success") return "Upload complete";
    if (item.status === "waiting") return "File is in the upload queue ...";
    return "Uploading...";
};
</script>

<template>
    <TransitionGroup name="list">
        <div
            v-if="uploads.length > 0"
            :class="[
                'fixed bottom-8 right-4 z-40 sm:max-w-sm max-w-[93%] transition-all duration-300 ease-in-out',
                'bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700',
                isMinimized ? 'h-12' : 'h-auto',
            ]"
        >
            <div v-if="rerender % 2 == 1"></div>
            <!-- Header -->
            <div
                class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-800 rounded-t-lg"
            >
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200">
                        File Uploads
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{
                            uploads.filter(
                                (upload) => upload.status === "uploading"
                            ).length
                        }}
                        active,
                        {{
                            uploads.filter(
                                (upload) => upload.status === "waiting"
                            ).length
                        }}
                        pending,
                        {{
                            uploads.filter(
                                (upload) => upload.status === "success"
                            ).length
                        }}
                        successful,
                        {{
                            uploads.filter(
                                (upload) => upload.status === "error"
                            ).length
                        }}
                        error
                    </p>
                </div>
                <div class="flex items-center">
                    <button
                        @click="isMinimized = !isMinimized"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            :class="[
                                'h-6 w-6 transform transition-transform',
                                isMinimized ? 'rotate-180' : '',
                            ]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div
                :class="[
                    'overflow-y-auto overflow-x-hidden transition-all duration-300',
                    isMinimized ? 'max-h-[10vh]' : 'max-h-[60vh]',
                ]"
            >
                <div class="space-y-2 p-4">
                    <div
                        v-for="item in uploads"
                        :key="item.id"
                        class="flex items-center p-4 rounded-lg border-l-4 shadow-sm hover:shadow-md transition-all duration-200"
                        :class="[getStatusColor(item.status)]"
                    >
                        <div class="flex-1">
                            <p
                                class="font-medium text-sm text-gray-800 dark:text-gray-200"
                            >
                                {{
                                    item.name.length > 30
                                        ? item.name.substring(0, 30) +
                                          " ... ." +
                                          item.name.split(".").pop()
                                        : item.name
                                }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ getStatusText(item) }}
                            </p>
                            <div
                                v-if="item.status === 'uploading'"
                                class="mt-1"
                            >
                                <div
                                    class="h-1.5 w-full bg-gray-200 dark:bg-gray-700 rounded-full"
                                >
                                    <div
                                        class="h-1.5 bg-blue-600 dark:bg-blue-500 rounded-full transition-all duration-300"
                                        :style="{
                                            width: `${item.progress}%`,
                                        }"
                                    ></div>
                                </div>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-300 mt-1"
                                >
                                    {{ item.progress }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="border-t min-w-96 border-gray-200 dark:border-gray-700 p-3 bg-gray-50 dark:bg-gray-800 rounded-b-lg"
            >
                <div class="flex justify-between items-center">
                    <button
                        @click="imageUploadStore.clearAllCompleted()"
                        class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                        Clear Completed
                    </button>
                </div>
            </div>
        </div>
    </TransitionGroup>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}

.list-enter-from {
    opacity: 0;
    transform: translateX(30px);
}

.list-leave-to {
    opacity: 0;
    transform: translateX(-30px);
}

/* Custom Scrollbar */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #edf2f7;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #edf2f7;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: #a0aec0;
}
</style>
