<script setup>
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useImageStore } from "@/Store/ImageStore";
import { useForm, usePage, } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    exhibitionImages: {
        type: Array,
    },

    exhibition: {
        type: Object
    }

});

const page = usePage();

const locale = page.props.locale

const imageStore = useImageStore()

watch(
    () => imageStore.selectedImageIds,
    (newValue) => {
        form.selectedImages = newValue
    }
)

const handleRemoveImage = (id) => {
    if (!form.processing)
        imageStore.toggleRemoveImage(id)
}

const clearImages = () => {
    imageStore.replaceBatchSelectedRemoveImages([])
    form.reset()
}

const form = useForm({
    selectedImages: [],
});



</script>
<template>
    <div class="bg-white p-2 shadow sm:rounded-lg sm:p-4 flex-none lg:w-full my-5">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Exhibition images</h2>

            <p class="mt-1 text-sm text-gray-600">select an image to remove from exhibition images</p>
        </header>

        <div class="flex flex-wrap min-h-24">

            <div v-if="exhibitionImages.total === 0" class="mx-auto my-auto">
                No image has been added to exhibition yet
            </div>

            <div class="xl:w-1/6 lg:w-1/4  md:w-1/3 w-1/2   flex-none p-2 " v-for="image in exhibitionImages.data"
                :key="image.id">
                <!-- <span> {{ image.title }} </span> -->
                <img :src="image.path"
                    class="rounded-lg border-2  transition-transform duration-300 ease-in-out cursor-pointer hover:scale-105"
                    alt="" @click="handleRemoveImage(image.id)"
                    :class="{ 'border-gray-900 scale-105': imageStore.selectedRemoveImageIds.includes(image.id) }" />

                <div class="relative -top-3/4 left-1 flex items-center justify-center w-4 h-4 rounded-full border-2 border-gray-900 bg-gray-900 transition-opacity duration-300"
                    :class="{ '  opacity-100': imageStore.selectedRemoveImageIds.includes(image.id), 'opacity-0': !imageStore.selectedRemoveImageIds.includes(image.id) }">
                    <svg v-if="imageStore.selectedRemoveImageIds.includes(image.id)" xmlns="http://www.w3.org/2000/svg"
                        class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

        </div>

        <div v-if="exhibitionImages.total > 0" class="flex justify-between min-h-5">

            <Pagination prefetch preserve-scroll :only="['exhibitionImages']" :links="exhibitionImages.links" />

            <form @submit.prevent="
                form.selectedImages = imageStore.selectedRemoveImageIds;
            form.patch(
                route('exhibition.images.toggle', {
                    lang: locale,
                    exhibition: exhibition.id,
                }),
                {
                    onSuccess: () => clearImages()
                }
            );
            " class=" space-y-4 md:space-y-0 md:flex-row md:items-stretch flex flex-col flex-wrap"
                v-if="imageStore.selectedRemoveImageIds.length > 0">


                <div class="flex items-center row-gap-4">
                    <PrimaryButton class="mx-1 " :disabled="form.processing">REMOVE</PrimaryButton>

                    <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                        <p v-if="form.wasSuccessful" class="text-green-600">Successfully removed.</p>
                    </Transition>


                </div>
            </form>

        </div>
    </div>
</template>
