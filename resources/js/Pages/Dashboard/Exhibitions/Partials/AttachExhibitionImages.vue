<script setup>
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Skeleton from "@/Components/Skeleton.vue";
import { useImageStore } from "@/Store/ImageStore";
import { Deferred, router, useForm, usePage, WhenVisible } from "@inertiajs/vue3";
import { computed, onBeforeUnmount, onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
    exhibitionImages: {
        type: Array,
    },
    allUserImages: {
        type: Array,
    },
    exhibitionImagePage: {
        type: Number
    },
    userImagePage: {
        type: Number
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

const handleAddImage = (id) => {
    if (!form.processing)
        imageStore.toggleAddImage(id)
};

const handleRemoveImage = (id) => {
    if (!form.processing)
        imageStore.toggleRemoveImage(id)
}

const clearImages = () => {
    imageStore.replaceBatchImages([]);
    // imageStore.replaceBatchSelectedRemoveImages([])
    form.reset()
}

const form = useForm({
    selectedImages: [],
});

</script>
<template>
    <section>
        <div class="bg-white p-2 shadow sm:rounded-lg sm:p-4 flex-none lg:w-full my-5">

            <header>
                <h2 class="text-lg font-medium text-gray-900">My images</h2>

                <div class="md:flex md:my-0 my-2 justify-between">

                    <p class="mt-1 me-6  text-sm text-gray-600">choose an image from your uploaded images to be shown in
                        the
                        exhibition.</p>
                    <div class="mt-1 mx-3 text-center flex justify-between text-gray-600 transition-opacity duration-300 cursor-default"
                        :class="{ 'opacity-100 cursor-pointer': imageStore.selectedImageIds.length, 'opacity-0': !imageStore.selectedImageIds.length }">
                        <div class="mx-3 cursor-default">
                            <small>
                                {{ imageStore.selectedImageIds.length }}
                                images selected
                            </small>

                        </div>

                        <div class="flex justify-between" @click="clearImages">

                            <div class="mx-1">
                                <small>
                                    deselect all
                                </small>

                            </div>
                            <div
                                class="  w-5 h-5 rounded-full border-2 border-gray-900 bg-gray-900 transition-opacity duration-300">

                                <svg v-if="imageStore.selectedImageIds.length" xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <line x1="7" y1="11" x2="17" y2="11" />
                                    <line x1="7" y1="12" x2="17" y2="12" />
                                    <line x1="7" y1="13" x2="17" y2="13" />

                                </svg>


                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex flex-wrap min-h-20 ">
                <div v-if="allUserImages.total === 0" class="mx-auto my-auto">
                    all images are added to the exhibition
                </div>
                <div class="xl:w-1/6 lg:w-1/4  md:w-1/3 w-1/2 flex-none p-2 " v-for="image in allUserImages.data"
                    :key="image.id">
                    <!-- <span> {{ image.title }} </span> -->
                    <img :src="image.path"
                        class="rounded-lg border-2  transition-transform duration-300 ease-in-out cursor-pointer hover:scale-105 "
                        @click=" handleAddImage(image.id)" alt="" :class="{
                            'border-blue-500 shadow-lg scale-105 ':
                                imageStore.selectedImageIds.includes(image.id)
                        }" />


                    <div class="relative -top-3/4 left-1 flex items-center justify-center w-4 h-4 rounded-full border-2 border-blue-500 bg-blue-500 transition-opacity duration-300"
                        :class="{ '  opacity-100': imageStore.selectedImageIds.includes(image.id), 'opacity-0': !imageStore.selectedImageIds.includes(image.id) }">
                        <svg v-if="imageStore.selectedImageIds.includes(image.id)" xmlns="http://www.w3.org/2000/svg"
                            class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div v-if="allUserImages.total > 0" class="flex justify-between min-h-5">
                <Pagination prefetch preserve-scroll :only="['allUserImages']" :links="allUserImages.links" />

                <form @submit.prevent="
                    form.selectedImages = imageStore.selectedImageIds;
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
                    v-if="imageStore.selectedImageIds.length > 0">



                    <div class="flex items-center row-gap-4">
                        <PrimaryButton class="mx-1 " :disabled="form.processing">Add</PrimaryButton>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-if="form.wasSuccessful" class="text-green-600">Saved.</p>
                        </Transition>


                    </div>
                </form>
            </div>
        </div>


    </section>
</template>
