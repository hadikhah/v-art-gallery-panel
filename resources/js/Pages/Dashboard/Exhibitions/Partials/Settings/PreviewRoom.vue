<script setup>
import createREGL from "regl";
import { initScene } from "@/Tools/room";
import { onMounted, ref, watch } from "vue";

/**
 * Props for the PreviewRoom component.
 */
const props = defineProps({
    ceilingTexture: {
        type: String,
    },
    wallTexture: {
        type: String,
    },
    floorTexture: {
        type: String,
    },
});

/**
 * Reactive camera state.
 * Tracks the rotation, height, and distance of the camera.
 */
const cameraState = ref({
    rotation: 0,
    height: 0,
    distance: 5,
});

let container = null; // Reference to the DOM container for the WebGL canvas

const colors = "#e8e8e810"; // Default color for room surfaces

const roomDimensions = {
    width: 30,
    length: 30,
    height: 8,
};

var regl; // Regl instance

/**
 * Callback function to update the camera state in the Vue component.
 * @param {Object} newState - Updated camera state from room.js.
 */
const setCameraState = (newState) => {
    cameraState.value = newState;
};

/**
 * Initialize WebGL and start rendering when the component is mounted.
 */
onMounted(async () => {
    container = document.getElementById("canvas-container");

    regl = createREGL({
        container: container,
        attributes: {
            antialias: true, // Enable anti-aliasing
        },
    });

    await initScene(
        regl,
        container,
        props.ceilingTexture,
        props.wallTexture,
        props.floorTexture,
        roomDimensions,
        colors,
        setCameraState
    ).catch((err) => console.error(err));
});

/**
 * Watch for changes to the texture props and reinitialize the scene.
 */
watch(props, async (newValue) => {
    await initScene(
        regl,
        container,
        newValue.ceilingTexture,
        newValue.wallTexture,
        newValue.floorTexture,
        roomDimensions,
        colors,
        setCameraState
    ).catch((err) => console.error(err));
});
</script>

<template>
    <div class="lg:w-2/3">
        <div class="sticky">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
            >
                <div class="absolute p-2 bg-transparent">
                    <h3
                        class="text-lg font-medium text-indigo-900 [text-shadow:0px_2px_5px_rgba(200,320,150,0.7)] dark:text-gray-50 dark:[text-shadow:0px_2px_5px_rgba(0,0,120,1)]"
                    >
                        Preview
                    </h3>
                </div>
                <div class="w-full h-auto">
                    <div
                        ref="container"
                        id="canvas-container"
                        class="h-[30rem] sm:h-[35rem]"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
canvas {
    width: 100%;
    height: 100%;
    border-radius: 10px !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>
