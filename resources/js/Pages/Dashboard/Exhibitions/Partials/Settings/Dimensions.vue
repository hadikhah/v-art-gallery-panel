<script setup>
import InputError from "@/Components/InputError.vue";
import { countPlacements } from "@/Tools/map";
import { onMounted, ref } from "vue";

const props = defineProps({
    areaSizeError: {
        type: String,
    },
    roomSizeError: {
        type: String,
    },
    wallThicknessError: {
        type: String,
    },
});

const areaSizeModel = defineModel("area_size", {
    type: Number,
});
const roomSizeModel = defineModel("room_size", {
    type: Number,
});
const wallThicknessModel = defineModel("wall_thickness", {
    type: Number,
});

const availablePictures = ref(0);

onMounted(async () => {
    await updateAvailablePictures();
});

const updateAvailablePictures = async () => {
    availablePictures.value = await countPlacements(
        parseInt(areaSizeModel.value),
        parseInt(roomSizeModel.value),
        parseFloat(wallThicknessModel.value)
    );
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-medium mb-6 dark:text-gray-200">Dimensions</h3>
        <h3 class="text-sm font-medium mb-6 dark:text-gray-200">
            Maximum images : {{ availablePictures }}
        </h3>
        <div class="space-y-6">
            <div>
                <div class="flex justify-between mb-2">
                    <label class="text-sm font-medium dark:text-gray-300"
                        >Area Size</label
                    >
                    <span class="text-sm dark:text-gray-400">
                        {{ areaSizeModel }}
                    </span>
                </div>
                <input
                    type="range"
                    v-model.number="areaSizeModel"
                    min="1"
                    max="4"
                    step="1"
                    @input="updateAvailablePictures"
                    class="w-full h-2 bg-gray-200 dark:accent-indigo-500 accent-gray-700 rounded-lg appearance-none cursor-pointer dark:bg-gray-600"
                />
                <InputError class="mt-2" :message="areaSizeError" />
            </div>
            <div>
                <div class="flex justify-between mb-2">
                    <label class="text-sm font-medium dark:text-gray-300"
                        >Room Size</label
                    >
                    <span class="text-sm dark:text-gray-400"
                        >{{ roomSizeModel }}m</span
                    >
                </div>
                <input
                    type="range"
                    v-model.number="roomSizeModel"
                    min="5"
                    max="20"
                    step="1"
                    @input="updateAvailablePictures"
                    class="w-full h-2 dark:accent-indigo-500 accent-gray-700 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-600"
                />
                <InputError class="mt-2" :message="roomSizeError" />
            </div>
            <div>
                <div class="flex justify-between mb-2">
                    <label class="text-sm font-medium dark:text-gray-300"
                        >Wall Thickness</label
                    >
                    <span class="text-sm dark:text-gray-400"
                        >{{ wallThicknessModel }}m</span
                    >
                </div>
                <input
                    type="range"
                    v-model.number="wallThicknessModel"
                    min="0.2"
                    max="0.8"
                    step="0.1"
                    @input="updateAvailablePictures"
                    class="w-full h-2 dark:accent-indigo-500 accent-gray-700 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-600"
                />
                <InputError class="mt-2" :message="wallThicknessError" />
            </div>
        </div>
    </div>
</template>
