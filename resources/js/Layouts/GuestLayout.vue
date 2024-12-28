<script setup>
import ThemeToggleButton from "@/Components/ThemeToggleButton.vue";
import { Link, usePage } from "@inertiajs/vue3";

import { useDarkModeStore } from "@/Store/darkMode";
import { storeToRefs } from "pinia";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const locale = usePage().props.locale;

const darkModeStore = useDarkModeStore();
const { isDark } = storeToRefs(darkModeStore);
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center dark:bg-gray-900 dark:text-gray-50 bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div
            class="mt-6 w-full overflow-hidden bg-white dark:bg-gray-800 dark:text-gray-50 px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <div class="flex justify-between pb-4">
                <div>
                    <Link :href="route('home', { lang: locale })">
                        <ApplicationLogo
                            class="h-20 w-20 fill-current text-gray-500"
                        />
                    </Link>
                </div>
                <div>
                    <ThemeToggleButton />
                </div>
            </div>
            <slot />
        </div>
    </div>
</template>
