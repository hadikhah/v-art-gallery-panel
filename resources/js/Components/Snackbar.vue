<script setup>
import { useToastStore } from "@/Store/toast";
import { storeToRefs } from "pinia";

const store = useToastStore();
const { toasts } = storeToRefs(store);
const { removeToast } = store;
</script>

<template>
    <TransitionGroup
        name="snackbar"
        tag="div"
        class="fixed w-full sm:w-auto max-h-[60vh] z-40 space-y-2 top-4 left-1/2 transform -translate-x-1/2 sm:left-4 sm:transform sm:translate-x-4"
    >
        <div
            v-for="toast in toasts"
            :key="toast.id"
            class="flex p-4 rounded-lg shadow-lg border-l-4 transition-all duration-300 flex items-center justify-between sm:max-w-sm max-w-[93%]"
            :class="[
                toast.type === 'error' &&
                    'border-red-500 bg-red-50 dark:bg-red-900 dark:border-red-700',
                toast.type === 'success' &&
                    'border-green-500 bg-green-50 dark:bg-green-900 dark:border-green-700',
                toast.type === 'warning' &&
                    'border-yellow-500 bg-yellow-50 dark:bg-yellow-900 dark:border-yellow-700',
            ]"
        >
            <div>
                <span class="text-sm text-gray-800 dark:text-gray-200">{{
                    toast.message
                }}</span>
            </div>
            <div>
                <button
                    @click="removeToast(toast.id)"
                    class="hover:text-gray-900 dark:hover:text-gray-300"
                >
                    <span class="sr-only">Close</span>
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </TransitionGroup>
</template>

<style scoped>
.snackbar-enter-active,
.snackbar-leave-active {
    transition: all 0.3s ease;
}
.snackbar-enter-from {
    opacity: 0;
    transform: translateY(-30px);
}
.snackbar-leave-to {
    opacity: 0;
    transform: translateX(-100px);
}
/* Custom Scrollbar */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-base-color: transparent;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 0px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: transparent;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: transparent;
}
</style>
