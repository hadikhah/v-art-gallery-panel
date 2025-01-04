<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";

const props = defineProps({
    defaultTextures: {
        type: Object,
    },
    ceilingTexture: {
        type: String,
    },
    wallTexture: {
        type: String,
    },
    floorTexture: {
        type: String,
    },
    selectedTextureType: {
        type: String,
    },
    ceilingTextureError: {
        type: String,
    },
    wallTextureError: {
        type: String,
    },
    floorTextureError: {
        type: String,
    },
    showTextureModal: {
        type: Boolean,
    },
    textures: {
        type: Array,
    },
});

const emit = defineEmits(["showModal", "closeModal", "updateTexture"]);
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-medium mb-6 dark:text-gray-200">
            Texture Settings
        </h3>
        <div class="space-y-4">
            <div
                v-for="type in ['ceiling', 'wall', 'floor']"
                :key="type"
                class="flex flex-col gap-2"
            >
                <div class="flex items-center justify-between">
                    <span class="capitalize dark:text-gray-300 text-sm"
                        >{{ type }} Texture</span
                    >
                    <PrimaryButton
                        class="full dark:focus:bg-indigo-800 px-4 py-2 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition duration-200 justify-center"
                        @click="emit('showModal', type)"
                    >
                        Change
                    </PrimaryButton>
                </div>
                <div
                    class="h-20 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden"
                >
                    <!-- Current texture preview -->
                    <div
                        class="w-full h-full bg-center bg-cover"
                        :style="{
                            backgroundImage: `url(${props[type + 'Texture']})`,
                        }"
                    ></div>
                </div>
                <InputError
                    class="mt-2"
                    :message="`${props[type + 'TextureError'] ?? ''}`"
                />
            </div>
        </div>
    </div>
    <Modal @close="emit('closeModal')" :show="showTextureModal">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full max-h-[80vh] overflow-hidden"
        >
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium dark:text-gray-200">
                        Select {{ selectedTextureType }} Texture
                    </h3>
                    <button
                        @click="emit('closeModal')"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                    >
                        <span class="sr-only">Close</span>
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
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
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"
                >
                    <div
                        v-for="texture in textures"
                        :key="texture.id"
                        @click="emit('updateTexture', texture)"
                        class="group cursor-pointer"
                    >
                        <div
                            class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden ring-2 ring-transparent hover:ring-blue-500 transition-all duration-200"
                        >
                            <div
                                class="w-full h-full bg-center bg-cover transform transition-transform duration-200 group-hover:scale-110"
                                :style="{
                                    backgroundImage: `url(${texture.url})`,
                                }"
                            ></div>
                        </div>
                        <p class="mt-2 text-sm text-center dark:text-gray-300">
                            {{ texture.name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
