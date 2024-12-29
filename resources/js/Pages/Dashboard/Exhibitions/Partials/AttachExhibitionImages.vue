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
    allUserImages: {
        type: Array,
    },
    exhibitionImagePage: {
        type: Number,
    },
    userImagePage: {
        type: Number,
    },
    exhibition: {
        type: Object,
    },
});

const page = usePage();
const locale = page.props.locale;
const imageStore = useImageStore();

watch(
    () => imageStore.selectedImageIds,
    (newValue) => {
        form.selectedImages = newValue;
    }
);

const handleAddImage = (id) => {
    if (!form.processing) imageStore.toggleAddImage(id);
};

const handleRemoveImage = (id) => {
    if (!form.processing) imageStore.toggleRemoveImage(id);
};

const clearImages = () => {
    imageStore.replaceBatchImages([]);
    form.reset();
};

const form = useForm({
    selectedImages: [],
});
</script>

<template>
    <section>
        <div
            class="bg-white dark:bg-gray-800 p-2 shadow sm:rounded-lg sm:p-4 flex-none lg:w-full my-5"
        >
            <header>
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    My images
                </h2>

                <div class="md:flex md:my-0 my-2 justify-between">
                    <p
                        class="mt-1 me-6 text-sm text-gray-600 dark:text-gray-400"
                    >
                        Select images from uploaded images to add them to the
                        exhibition.
                    </p>
                    <div
                        class="mt-1 mx-3 text-center flex justify-between text-gray-600 dark:text-gray-400 transition-opacity duration-300 cursor-default"
                        :class="{
                            'opacity-100 cursor-pointer':
                                imageStore.selectedImageIds.length,
                            'opacity-0': !imageStore.selectedImageIds.length,
                        }"
                    >
                        <div class="mx-3 cursor-default">
                            <small>
                                {{ imageStore.selectedImageIds.length }} images
                                selected
                            </small>
                        </div>

                        <div class="flex justify-between" @click="clearImages">
                            <div class="mx-1">
                                <small> Deselect all </small>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-900 dark:border-gray-600 bg-gray-900 dark:bg-blue-900 transition-opacity duration-300"
                            >
                                <svg
                                    v-if="imageStore.selectedImageIds.length"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <line x1="7" y1="11" x2="17" y2="11" />
                                    <line x1="7" y1="12" x2="17" y2="12" />
                                    <line x1="7" y1="13" x2="17" y2="13" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div
                :class="{
                    'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-5 gap-4 min-h-20 mt-5':
                        allUserImages.total > 0,
                    'flex h-20': allUserImages.total === 0,
                }"
            >
                <div
                    v-if="allUserImages.total === 0"
                    class="mx-auto my-auto text-gray-600 dark:text-gray-400"
                >
                    No available image
                </div>
                <div
                    class="group overflow-hidden border-2 dark:border-transparent rounded-lg shadow-md transition-all transform hover:scale-105"
                    v-for="image in allUserImages.data"
                    :key="image.id"
                    :class="{
                        'border-green-500 dark:border-green-400 shadow-lg scale-105':
                            imageStore.selectedImageIds.includes(image.id),
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
                            @click="handleAddImage(image.id)"
                            :class="{
                                'opacity-100':
                                    imageStore.selectedImageIds.includes(
                                        image.id
                                    ),
                                'opacity-0':
                                    !imageStore.selectedImageIds.includes(
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
                                    imageStore.selectedImageIds.includes(
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
                v-if="allUserImages.total > 0"
                class="flex justify-between min-h-5"
            >
                <Pagination
                    prefetch
                    preserve-scroll
                    :only="['allUserImages']"
                    :links="allUserImages.links"
                />

                <form
                    @submit.prevent="
                        form.selectedImages = imageStore.selectedImageIds;
                        form.patch(
                            route('exhibition.images.toggle', {
                                lang: locale,
                                exhibition: exhibition.id,
                            }),
                            {
                                onSuccess: () => clearImages(),
                            }
                        );
                    "
                    class="space-y-4 md:space-y-0 md:flex-row md:items-stretch flex flex-col flex-wrap"
                    v-if="imageStore.selectedImageIds.length > 0"
                >
                    <div class="flex items-center row-gap-4 mt-6">
                        <PrimaryButton class="mx-1" :disabled="form.processing"
                            >Add</PrimaryButton
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form.wasSuccessful" class="text-green-600">
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>
