<script setup>
import NewtabIcon from "@/Components/Icons/NewtabIcon.vue";
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Skeleton from "@/Components/Skeleton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Deferred, Link, usePage } from "@inertiajs/vue3";

defineProps({
    exhibitions: {
        type: Array,
    },
    statusList: {
        type: Array,
    },
});

const page = usePage();
const locale = page.props.locale;
</script>

<template>

    <Head title="Exhibitions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Exhibitions
            </h2>
        </template>
        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <Deferred data="exhibitions">
                        <template #fallback>
                            <Skeleton :count="11"></Skeleton>
                        </template>

                        <div class="p-4 text-gray-900">
                            <div v-for="exhibition in exhibitions.data" :key="exhibition.id">
                                <div class="p-4 mt-2 border rounded-md flex justify-between">
                                    <div class="w-72">
                                        {{ exhibition.title }}
                                    </div>
                                    <div :class="exhibition.status ===
                                            statusList.filter(
                                                (item) => item.key === `STATUS_PUBLIC`
                                            )[0].value
                                            ? `border-lime-600 text-lime-800`
                                            : `border-red-600 text-red-600`
                                        " class="mx-5 border border-lg rounded text-center p-1">
                                        <span>
                                            {{ exhibition.status }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between w-1/7">
                                        <Link :href="route(`exhibition.edit`, {
                                            lang: locale,
                                            exhibition: exhibition.id,
                                        })
                                            " class="mx-1" prefetch>
                                        <WarningButton> edit </WarningButton>
                                        </Link>
                                        <a class="mx-1" href="http://localhost/exhibition/v30" target="_blank">
                                            <PrimaryButton>
                                                preview
                                                <NewtabIcon></NewtabIcon>
                                            </PrimaryButton>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <Pagination :links="exhibitions.links" />
                        </div>
                    </Deferred>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style></style>
