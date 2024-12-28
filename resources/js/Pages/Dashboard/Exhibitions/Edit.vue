<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AttachExhibitionImages from "./Partials/AttachExhibitionImages.vue";
import UpdateExhibitionInformationForm from "./Partials/UpdateExhibitionInformationForm.vue";
import Skeleton from "@/Components/Skeleton.vue";
import { Deferred, router } from "@inertiajs/vue3";
import { useImageStore } from "@/Store/ImageStore";
import ExhibitionImages from "./Partials/ExhibitionImages.vue";

const props = defineProps({
    exhibition: {
        type: Object,
    },
    exhibitionImages: {
        type: Array,
    },
    allUserImages: {
        type: Array,
    },
    statusList: {
        type: Array,
    },
    exhibitionImagePage: {
        type: Number,
    },
    userImagePage: {
        type: Number,
    },
});

const imageStore = useImageStore();

router.on("navigate", (event) => {
    if (event.detail.page.component !== "Dashboard/Exhibitions/Edit") {
        if (imageStore.selectedImageIds.length > 0) {
            imageStore.replaceBatchImages([]);
        }
    }
});
</script>

<template>
    <Head title="Edit Exhibition" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                Exhibitions . Edit exhibition
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-full sm:px-6 flex flex-col md:flex-row flex-wrap align-center items-stretch justify-around">
                <div class="bg-white dark:bg-gray-800 sm:p-4 p-8 md:me-2 my-2 shadow max-h-fit sm:rounded-lg flex-auto md:w-4/12">
                    <UpdateExhibitionInformationForm
                        :exhibition="exhibition"
                        :statusList="statusList"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 sm:p-4 p-8 my-2 shadow sm:rounded-lg flex-none overflow-auto md:w-7/12">
                    <Deferred :data="['allUserImages']">
                        <template #fallback>
                            <Skeleton :count="5" />
                        </template>
                        <AttachExhibitionImages
                            :allUserImages="allUserImages"
                            :exhibitionImages="exhibitionImages"
                            :userImagePage="userImagePage"
                            :exhibition="exhibition"
                        />
                    </Deferred>
                    <Deferred :data="['exhibitionImages']">
                        <template #fallback>
                            <Skeleton :count="5" />
                        </template>
                        <ExhibitionImages
                            :exhibitionImages="exhibitionImages"
                            :exhibition="exhibition"
                        />
                    </Deferred>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
