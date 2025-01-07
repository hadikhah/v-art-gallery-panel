<script setup>
import { defineProps } from 'vue';
import ImageIcon from '@/Components/Icons/ImageIcon.vue';
import GalleryIcon from '@/Components/Icons/GalleryIcon.vue';


const props = defineProps({
    title: String,
    value: Number,
    icon: String,
});

const icons = {
    image: ImageIcon,
    gallery: GalleryIcon,
};
</script>

<template>
    <div
        class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg"
    >
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full dark:bg-indigo-900">
                    <component
                        :is="icons[icon]"
                        class="w-6 h-6 text-indigo-600 dark:text-indigo-300"
                    />
                </div>
                <div class="mx-5">
                    <h4
                        class="text-sm font-medium text-gray-500 dark:text-gray-400"
                    >
                        {{ title }}
                    </h4>
                    <div class="flex items-baseline">
                        <span
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{ value }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
