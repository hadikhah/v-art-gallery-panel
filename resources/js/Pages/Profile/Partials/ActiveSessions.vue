<!-- resources/js/Components/Dashboard/ActiveSessions.vue -->
<script setup>
import DesktopIcon from "@/Components/Icons/DesktopIcon.vue";
import MobileIcon from "@/Components/Icons/MobileIcon.vue";
import TabletIcon from "@/Components/Icons/TabletIcon.vue";
import { defineProps } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    sessions: {
        type: Array,
        required: true,
    },
});

const capitalizeFirst = (str) => {
    return str.charAt(0).toUpperCase() + str.slice(1);
};

const removeSession = (sessionId) => {
    if (confirm("Are you sure you want to remove this session?")) {
        router.delete(`/active-sessions/${sessionId}`, {
            preserveScroll: true,
        });
    }
};

const getDeviceIcon = (deviceType) => {
    const icons = {
        desktop: DesktopIcon,
        mobile: MobileIcon,
        tablet: TabletIcon,
    };
    return icons[deviceType] || icons.desktop;
};
</script>
<template>
    <div class="space-y-6">
        <div
            v-for="session in sessions"
            :key="session.id"
            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
        >
            <div class="flex items-center space-x-4">
                <!-- Device Type Icon -->
                <div class="p-2 bg-white dark:bg-gray-600 rounded-full">
                    <component
                        :is="getDeviceIcon(session.device_type)"
                        class="w-5 h-5 text-gray-600 dark:text-gray-300"
                    />
                </div>

                <!-- Session Info -->
                <div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="text-sm font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ session.browser }} on
                            {{ capitalizeFirst(session.device_type) }}
                        </span>
                        <span
                            v-if="session.is_current_device"
                            class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-100"
                        >
                            Current Device
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ session.ip_address }} · Last active
                        {{ session.last_activity }}
                    </div>
                </div>
            </div>

            <!-- Remove Session Button -->
            <button
                v-if="!session.is_current_device"
                @click="removeSession(session.id)"
                class="px-3 py-1 text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
            >
                Remove
            </button>
        </div>
    </div>
</template>
