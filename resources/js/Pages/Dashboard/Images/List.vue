<script setup>
import Pagination from "@/Components/Pagination.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Skeleton from "@/Components/Skeleton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Deferred, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import GridView from "./Partials/GridView.vue";
import DetailedView from "./Partials/DetailedView.vue";
import UploadImage from "./Partials/UploadImage.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";

const props = defineProps({
    images: {
        type: Object,
    },
});

// State to manage view mode
const isGridView = ref(true);

// Delete modal state
const showDeleteModal = ref(false);
const imageToDelete = ref(null);
const disabled = ref(false);

const openDeleteModal = (image) => {
    imageToDelete.value = image;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    imageToDelete.value = null;
};

const page = usePage();
const locale = page.props.locale;

const confirmDelete = () => {
    if (imageToDelete.value) {
        disabled.value = true;
        router.delete(
            route("images.destroy", {
                lang: locale,
                image: imageToDelete.value,
            }),
            {
                onSuccess: () => {
                    closeDeleteModal();
                },
                only: ["images"],
            }
        );
        disabled.value = false;
    }
};

const handleDelete = (image) => {
    openDeleteModal(image);
};

const handleDownload = (image) => {
    window.open(route("images.download", { lang: locale, image: image.id }));
};
</script>

<template>
    <Head title="Exhibitions" />
    <AuthenticatedLayout class="dropzone">
        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg p-6"
                >
                    <Deferred data="images">
                        <template #fallback>
                            <Skeleton :count="12" :grid="true" />
                        </template>

                        <div
                            class="mb-5"
                            :class="[
                                images.data.length > 0
                                    ? 'flex justify-between items-center'
                                    : '',
                            ]"
                        >
                            <UploadImage :startUpload :images></UploadImage>
                            <SelectInput
                                v-if="images.total > 0"
                                v-model="isGridView"
                                optionTextKeyName="title"
                                id="view-select"
                                :options="[
                                    { value: true, title: 'Grid View' },
                                    { value: false, title: 'Detailed View' },
                                ]"
                            />
                        </div>

                        <div
                            class="mx-auto text-center p-5 pt-10"
                            v-if="images.total === 0"
                        >
                            <p class="text-gray-600 dark:text-gray-400">
                                No images available.
                            </p>
                        </div>

                        <GridView
                            v-if="isGridView"
                            :images="images"
                            @delete="handleDelete"
                            @download="handleDownload"
                        />
                        <DetailedView
                            v-else
                            :images="images"
                            @delete="handleDelete"
                            @download="handleDownload"
                        />

                        <Pagination
                            v-if="images.total > 0"
                            :links="images.links"
                            class="mt-6"
                        />
                    </Deferred>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <ConfirmationModal
            :disabled="disabled"
            :show="showDeleteModal"
            title="Delete Image"
            message="Are you sure you want to delete this image? This action cannot be undone."
            @confirm="confirmDelete"
            @cancel="closeDeleteModal"
        />
    </AuthenticatedLayout>
</template>
