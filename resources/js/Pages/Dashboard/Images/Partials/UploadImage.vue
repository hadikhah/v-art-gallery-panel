<script setup>
import UploadIcon from "@/Components/Icons/UploadIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { onBeforeMount, onBeforeUnmount, ref } from "vue";
import UploadSnackBar from "./UploadSnackBar.vue";
import { useImageUploadStore } from "@/Store/imageUploadStore";
import { useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    startUpload: {
        type: Function,
    },
    images: {
        type: Array,
    },
});

const input = ref(null);
const fileInput = useForm({
    input: null,
});

const page = usePage();

const progress = ref(0);
const isDragging = ref(false);
const dragCounter = ref(0);

const progressPercentage = ref(0);

// Handle drag events
const handleDragEnter = (e) => {
    e.preventDefault();
    dragCounter.value++;
    isDragging.value = true;
};

const handleDragLeave = (e) => {
    e.preventDefault();
    dragCounter.value--;
    if (dragCounter.value === 0) {
        isDragging.value = false;
    }
};

const handleDragOver = (e) => {
    e.preventDefault();
};

const handleDrop = (e) => {
    e.preventDefault();
    dragCounter.value = 0;
    isDragging.value = false;

    const files = e.dataTransfer.files;
    uploadFiles(Array.from(files));
};

onBeforeMount(() => {
    window.addEventListener("dragenter", handleDragEnter);
    window.addEventListener("dragleave", handleDragLeave);
    window.addEventListener("dragover", handleDragOver);
    window.addEventListener("drop", handleDrop);
});

onBeforeUnmount(() => {
    window.removeEventListener("dragenter", handleDragEnter);
    window.removeEventListener("dragleave", handleDragLeave);
    window.removeEventListener("dragover", handleDragOver);
    window.removeEventListener("drop", handleDrop);
});

const selectFiles = () => {
    if (input.value) {
        input.value.click();
    }
};

const handleFiles = (event) => {
    console.log("handleFiles");

    const files = event.target.files;
    uploadFiles(Array.from(files));

    fileInput.reset();
};

const uploadFiles = async (files) => {
    files.forEach((element) => {
        imageUploadStore.validateImage(element);
    });

    const locale = page.props.locale;

    startUpload(route("images.upload", { lang: locale }));
};

const myChild = ref(null);

const childeRerender = () => {
    myChild.value.childMethod();
};

const imageUploadStore = useImageUploadStore();

const startUpload = (route) => {
    if (imageUploadStore.inprogress !== 0) return;
    const form = useForm({
        image: null,
    });

    const fileIndex = imageUploadStore.imageUploadQueue.findIndex(
        (element) => element.status === "waiting"
    );
    if (fileIndex === -1) {
        console.log;
        return form.reset();
    }
    const file = imageUploadStore.imageUploadQueue[fileIndex];
    form.image = file;

    form.post(route, {
        onStart: () => {
            imageUploadStore.changeFileStatus(fileIndex, "uploading");
        },
        onProgress: (data) => {
            imageUploadStore.changeFileProgress(fileIndex, data.percentage);
            childeRerender();
        },
        onFinish: () => {
            imageUploadStore.inprogress--;
            childeRerender();
            startUpload(route);
        },
        onSuccess: () => {
            imageUploadStore.imageUploadQueue[fileIndex].status = "success";
            childeRerender();
        },
        onError: (err) => {
            imageUploadStore.imageUploadQueue[fileIndex].status = "error";
            imageUploadStore.imageUploadQueue[fileIndex].error =
                err.image || err.image_name;
            childeRerender();
        },
        only: ["images"],
    });
};
</script>

<template>
    <UploadSnackBar
        ref="myChild"
        :progressPercentage
        :uploads="imageUploadStore.imageUploadQueue"
    />

    <div
        v-if="isDragging"
        class="dropzone fixed h-screen w-screen inset-0 overflow-hidden bg-gray-700 bg-opacity-50 dark:bg-gray-900 flex items-center justify-center z-50"
    >
        <div class="dropzone flex flex-col items-center">
            <UploadIcon class="dropzone" />
            <p class="dropzone text-white text-xl font-semibold">
                Drop to upload
            </p>
        </div>
    </div>

    <form @submit.prevent>
        <PrimaryButton @click="selectFiles" v-if="images.total > 0">
            Upload Image
            <UploadIcon class="ms-1" />
        </PrimaryButton>
        <div
            v-if="images.total === 0"
            class="border-2 w-full flex-1 border-dashed border-gray-300 dark:border-gray-600 p-4 rounded-lg"
        >
            <div class="flex flex-col items-center mt-5">
                <UploadIcon />
                <p class="text-gray-500 dark:text-gray-300 my-5">
                    Drag & drop images here or
                </p>
                <PrimaryButton @click="selectFiles">
                    Upload Image
                </PrimaryButton>
                <small class="text-gray-500 dark:text-gray-400 my-5"
                    >only jpeg / png</small
                >
                <small class="text-gray-500 dark:text-gray-400"
                    >size < 1 MB</small
                >
                <small class="text-gray-500 dark:text-gray-400 my-5"
                    >width < 2400 , height < 1200</small
                >
            </div>
        </div>
        <input
            type="file"
            multiple
            accept="image/jpeg,image/png"
            :model="fileInput.input"
            class="hidden"
            ref="input"
            @input="handleFiles"
        />
        <div v-if="progress > 0" class="mt-2">
            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div
                    :style="{ width: progress + '%' }"
                    class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full"
                ></div>
            </div>
            <p class="text-sm dark:text-gray-300">
                {{ progress }}% uploading...
            </p>
        </div>
    </form>
</template>
