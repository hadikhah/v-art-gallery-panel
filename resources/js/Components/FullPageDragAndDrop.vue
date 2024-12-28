<template>
    <div>
        <div
            v-if="isDragging"
            class="fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center z-50"
            @dragover.prevent="handleDragOver"
            @dragleave="handleDragLeave"
            @drop.prevent="handleDrop"
        >
            <div class="flex flex-col items-center">
                <UploadIcon></UploadIcon>

                <p class="text-white text-xl font-semibold">Drop to upload</p>
            </div>
        </div>

        <slot />
    </div>
</template>

<script setup>
import UploadIcon from "@/Components/Icons/UploadIcon.vue";
import { ref } from "vue";

const props = defineProps({
    onUpload: {
        type: Function,
        required: true,
    },
});

const fileInput = ref(null); // Create a ref for the file input
const progress = ref(0);
const isDragging = ref(false); // State to track dragging

const selectFiles = () => {
    if (fileInput.value) {
        fileInput.value.click(); // Use the ref to access the file input
    }
};

const handleFiles = (event) => {
    const files = event.target.files;
    uploadFiles(Array.from(files));
};

const handleDragOver = () => {
    isDragging.value = true; // Show overlay when dragging over
};

const handleDragLeave = () => {
    isDragging.value = false; // Hide overlay when leaving the area
};

const handleDrop = (event) => {
    const files = event.dataTransfer.files;
    uploadFiles(Array.from(files));
    isDragging.value = false; // Hide overlay after drop
};

const uploadFiles = (files) => {
    const validFiles = validateFiles(files);
    if (validFiles.length > 0) {
        props.onUpload(validFiles, updateProgress);
    }
};

const validateFiles = (files) => {
    const validFiles = [];
    for (const file of files) {
        if (
            file.size <= 1024 * 1024 &&
            (file.type === "image/jpeg" || file.type === "image/png")
        ) {
            const img = new Image();
            img.src = URL.createObjectURL(file);
            img.onload = () => {
                if (img.width <= 2400 && img.height <= 1200) {
                    validFiles.push(file);
                }
            };
        }
    }
    return validFiles;
};

const updateProgress = (percent) => {
    progress.value = percent;
};
</script>
