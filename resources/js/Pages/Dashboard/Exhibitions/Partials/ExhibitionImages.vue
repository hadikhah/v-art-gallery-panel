<script setup>
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useImageStore } from "@/Store/ImageStore";
import { useForm, usePage } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    exhibitionImages: {
        type: Array,
    },
    exhibition: {
        type: Object,
    },
});

const page = usePage();
const locale = page.props.locale;
const imageStore = useImageStore();

watch(
    () => imageStore.selectedRemoveImageIds,
    (newValue) => {
        form.selectedImages = newValue;
    }
);

const handleRemoveImage = (id) => {
    if (!form.processing) imageStore.toggleRemoveImage(id);
};

const clearImages = () => {
    imageStore.replaceBatchSelectedRemoveImages([]);
    form.reset();
};

const form = useForm({
    selectedImages: [],
});
</script>

<template>
    <div
        class="bg-white dark:bg-gray-800 p-2 shadow sm:rounded-lg sm:p-4 flex-none lg:w-full my-5"
    >
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Exhibition images
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Select images and remove them from exhibition images.
            </p>
        </header>

        <div
            :class="{
                'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-5 gap-4 min-h-20 mt-5':
                    exhibitionImages.total > 0,
                'flex h-20': exhibitionImages.total === 0,
            }"
        >
            <div
                v-if="exhibitionImages.total === 0"
                class="mx-auto my-auto text-gray-600 dark:text-gray-400"
            >
                No image has been added to the exhibition yet.
            </div>

            <div
                class="group overflow-hidden border-2 dark:border-gray-800 rounded-lg shadow-md transition-all transform hover:scale-105"
                v-for="image in exhibitionImages.data"
                :key="image.id"
                :class="{
                    'border-green-500 dark:border-green-400 shadow-lg scale-105':
                        imageStore.selectedRemoveImageIds.includes(image.id),
                }"
            >
                <div class="relative w-full" style="padding-top: 75%">
                    <img
                        :src="image.url"
                        class="absolute inset-0 w-full h-full object-cover"
                        alt=""
                    />
                    <div
                        class="absolute cursor-pointer inset-0 bg-gradient-to-b from-transparent via-transparent to-black/70 opacity-0 group-hover:opacity-100 transition-opacity"
                        @click="handleRemoveImage(image.id)"
                        :class="{
                            'opacity-100':
                                imageStore.selectedRemoveImageIds.includes(
                                    image.id
                                ),
                            'opacity-0':
                                !imageStore.selectedRemoveImageIds.includes(
                                    image.id
                                ),
                        }"
                    >
                        <div
                            class="absolute bottom-2 left-2 text-white text-sm font-medium"
                        >
                            {{
                                image.title.length > 10
                                    ? image.title.substring(0, 10) +
                                      " ... ." +
                                      image.title.split(".").pop()
                                    : image.title
                            }}
                            <!-- {{ image.title }} -->
                        </div>
                        <div
                            v-if="
                                imageStore.selectedRemoveImageIds.includes(
                                    image.id
                                )
                            "
                            class="absolute bottom-1 right-1 flex space-x-2"
                        >
                            <div
                                class="p-0.5 rounded-full bg-green-400 hover:bg-green-600 transition-colors"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-900"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="exhibitionImages.total > 0"
            class="flex justify-between min-h-5"
        >
            <Pagination
                prefetch
                preserve-scroll
                :only="['exhibitionImages']"
                :links="exhibitionImages.links"
            />

            <form
                @submit.prevent="
                    form.selectedImages = imageStore.selectedRemoveImageIds;
                    form.patch(
                        route('exhibition.images.toggle', {
                            lang: locale,
                            exhibition: exhibition.id,
                        }),
                        {
                            onSuccess: () => clearImages(),
                            only: ['exhibitionImages', 'allUserImages'],
                            'preserve-scroll': true,
                        }
                    );
                "
                class="space-y-4 md:space-y-0 md:flex-row md:items-stretch flex flex-col flex-wrap"
                v-if="imageStore.selectedRemoveImageIds.length > 0"
            >
                <div class="flex items-center row-gap-4 mt-6">
                    <PrimaryButton class="mx-1" :disabled="form.processing"
                        >REMOVE</PrimaryButton
                    >

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="form.wasSuccessful" class="text-green-600">
                            Successfully removed.
                        </p>
                    </Transition>
                </div>
            </form>
        </div>
    </div>
</template>
