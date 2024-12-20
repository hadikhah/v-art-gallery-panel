<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: Array,
    only: {
        type: Array
    },
    "preserve-scroll": {
        type: Boolean
    },
    prefetch: {
        type: Boolean
    },
});

</script>

<template>
    <div class="mt-5">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label" />
                <div v-else-if="link.active" v-html="link.label"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-indigo-500 focus:text-indigo-500 bg-gray-900 text-white cursor-pointer">
                </div>
                <Link :prefetch :preserve-scroll :only v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-indigo-500 focus:text-indigo-500"
                    :class="{ 'bg-gray-900 text-white': link.active }" :href="link.url">

                <span v-html="link.label"></span>
                </Link>
            </template>
        </div>
    </div>
</template>
