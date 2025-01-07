<script setup>
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import GearIcon from "@/Components/Icons/GearIcon.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Skeleton from "@/Components/Skeleton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Deferred, Link, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    exhibitions: {
        type: Object,
    },
    statusList: {
        type: Array,
    },
});

const page = usePage();
const locale = page.props.locale;

const showDeleteModal = ref(false);
const exhibitionToDelete = ref(null);
const disabled = ref(false);

const openDeleteModal = (exhibition) => {
    exhibitionToDelete.value = exhibition;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    exhibitionToDelete.value = null;
};

const confirmDelete = () => {
    if (exhibitionToDelete.value) {
        disabled.value = true;
        router.delete(
            route(
                "exhibition.destroy",
                {
                    lang: locale,
                    exhibition: exhibitionToDelete.value.id,
                },
                {
                    onSuccess: () => {
                        closeDeleteModal();
                    },
                    only: ["exhibitions", "statusList"],
                }
            )
        );
        disabled.value = false;
        closeDeleteModal();
    }
};
</script>

<template>
    <Head title="Exhibitions" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100"
            >
                My Exhibitions
            </h2>
        </template>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <Deferred data="exhibitions">
                        <template #fallback>
                            <Skeleton :count="11" />
                        </template>

                        <!-- Create New Exhibition Button -->
                        <!-- @click="console.log(exhibitions)" -->
                        <div
                            class="p-5"
                            :class="{ 'text-center': exhibitions.total === 0 }"
                        >
                            <div
                                v-if="exhibitions.total === 0"
                                class="mb-4 text-gray-600 dark:text-gray-400"
                            >
                                No exhibitions so far, create a new one....
                            </div>
                            <Link
                                :href="
                                    route(`exhibition.create`, { lang: locale })
                                "
                                prefetch
                            >
                                <PrimaryButton
                                    class="inline-flex items-center gap-2"
                                >
                                    <PlusIcon class="w-5 h-5" />
                                    New Exhibition
                                </PrimaryButton>
                            </Link>
                        </div>

                        <!-- Exhibitions List -->
                        <div class="p-4 text-gray-900 dark:text-gray-300">
                            <div class="space-y-3">
                                <div
                                    v-if="exhibitions.total > 0"
                                    class="hidden sm:flex p-4 border dark:border-transparent dark:bg-gray-900 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-950 transition-colors flex-col sm:flex-row items-center justify-between gap-4"
                                >
                                    <div class="flex-none w-full sm:w-2/5">
                                        <div class="ml-4">
                                            <p
                                                class="font-semibold text-gray-800 dark:text-gray-100"
                                            >
                                                Title
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p
                                            class="sm:block ml-7 font-semibold text-gray-800 dark:text-gray-100"
                                        >
                                            status
                                        </p>
                                    </div>
                                    <div>
                                        <p
                                            class="hidden mr-8 sm:block font-semibold text-gray-800 dark:text-gray-100"
                                        >
                                            total views
                                        </p>
                                    </div>
                                    <div class="w-24">
                                        <p
                                            class="hidden sm:block font-semibold text-gray-800 dark:text-gray-100"
                                        >
                                            Actions
                                        </p>
                                    </div>
                                </div>
                                <div
                                    v-for="exhibition in exhibitions.data"
                                    :key="exhibition.id"
                                    class="p-4 border dark:border-transparent dark:bg-gray-900 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-950 transition-colors"
                                >
                                    <div
                                        class="flex flex-col sm:flex-row items-center justify-between gap-4"
                                    >
                                        <div
                                            class="flex justify-between flex-none w-full sm:w-3/5"
                                        >
                                            <!-- Title -->
                                            <div class="min-w-[200px] max-w-xl">
                                                <h3
                                                    class="font-medium text-gray-900 dark:text-gray-100"
                                                >
                                                    {{
                                                        exhibition.title
                                                            .length > 50
                                                            ? exhibition.title.substring(
                                                                  0,
                                                                  50
                                                              ) + " ... "
                                                            : exhibition.title
                                                    }}
                                                    <!-- {{ exhibition.title }} -->
                                                </h3>
                                            </div>
                                            <!-- Status Badge -->
                                            <div class="flex-shrink-0">
                                                <span
                                                    :class="{
                                                        'bg-green-100 text-green-800 border-green-200':
                                                            exhibition.status ===
                                                            statusList.filter(
                                                                (item) =>
                                                                    item.key ===
                                                                    'STATUS_PUBLIC'
                                                            )[0].value,
                                                        'bg-red-100 text-red-800 border-red-200':
                                                            exhibition.status !==
                                                            statusList.filter(
                                                                (item) =>
                                                                    item.key ===
                                                                    'STATUS_PUBLIC'
                                                            )[0].value,
                                                    }"
                                                    class="px-3 py-1 text-sm font-medium rounded-full border"
                                                >
                                                    {{ exhibition.status }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="inline sm:hidden">
                                                total views :
                                            </div>
                                            {{
                                                exhibition.view_rate_sum_views ??
                                                0
                                            }}
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center gap-2">
                                            <Link
                                                :href="
                                                    route(
                                                        `exhibition.settings`,
                                                        {
                                                            lang: locale,
                                                            exhibition:
                                                                exhibition.id,
                                                        }
                                                    )
                                                "
                                                class="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full"
                                            >
                                                <GearIcon
                                                    class="w-6 h-6 text-orange-400"
                                                />
                                            </Link>
                                            <Link
                                                :href="
                                                    route(`exhibition.edit`, {
                                                        lang: locale,
                                                        exhibition:
                                                            exhibition.id,
                                                    })
                                                "
                                                class="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full"
                                                prefetch
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                    />
                                                </svg>
                                            </Link>

                                            <button
                                                @click="
                                                    openDeleteModal(exhibition)
                                                "
                                                class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 hover:bg-red-100 dark:hover:bg-red-600 rounded-full"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    />
                                                </svg>
                                            </button>

                                            <a
                                                :href="
                                                    route('exhibition.visit', {
                                                        slug: exhibition.slug,
                                                    })
                                                "
                                                target="_blank"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 hover:bg-blue-100 dark:hover:bg-blue-600 rounded-full"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <Pagination
                                v-if="exhibitions.total > 0"
                                :links="exhibitions.links"
                                class="mt-6"
                            />
                        </div>
                    </Deferred>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <ConfirmationModal
            :show="showDeleteModal"
            :disabled="disabled"
            title="Delete Exhibition"
            message="Are you sure you want to delete this exhibition? This action cannot be undone."
            @confirm="confirmDelete"
            @cancel="closeDeleteModal"
        />
    </AuthenticatedLayout>
</template>
