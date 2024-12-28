<!-- Components/ConfirmationModal.vue -->
<script setup>
defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["confirm", "cancel"]);
</script>

<template>
    <!-- Background overlay -->
    <div
        v-show="show"
        class="fixed inset-0 transition-opacity"
        aria-hidden="true"
    >
        <div
            class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"
        ></div>
        <span
            class="hidden sm:inline-block sm:align-middle sm:h-screen"
            aria-hidden="true"
            >&#8203;</span
        >
    </div>

    <Transition
        enter-active-class="ease-out duration-150"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div v-show="show" class="fixed inset-0 z-50 overflow-y-auto">
            <div
                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div class="fixed inset-0" aria-hidden="true">
                    <div class="absolute inset-0"></div>
                </div>

                <span
                    class="hidden sm:inline-block sm:align-middle sm:h-screen"
                    aria-hidden="true"
                    >&#8203;</span
                >
                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800"
                >
                    <div
                        class="bg-white m-auto px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800"
                    >
                        <div class="sm:flex sm:items-start">
                            <div
                                class="m-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-red-500"
                            >
                                <svg
                                    class="h-6 w-6 text-red-600 dark:text-red-300"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ title }}
                                </h3>
                                <div class="mt-2">
                                    <p
                                        class="text-sm text-gray-500 dark:text-gray-300"
                                    >
                                        {{ message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700"
                    >
                        <button
                            :disabled="disabled"
                            type="button"
                            @click="$emit('confirm')"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                            :class="{
                                'bg-red-400 cursor-not-allowed': disabled,
                            }"
                        >
                            Delete
                        </button>
                        <button
                            type="button"
                            @click="$emit('cancel')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
