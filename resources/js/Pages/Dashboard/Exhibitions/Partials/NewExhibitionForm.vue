<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextArea from "@/Components/TextArea.vue";
import TextInput from "@/Components/TextInput.vue";
import { slugify } from "@/Tools/slugify";
import { useForm, usePage } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps({
    statusList: {
        type: Array,
    },
});

const page = usePage();
const locale = page.props.locale;

const form = useForm({
    title: "",
    slug: "",
    status: "",
    description: "",
});

watch(
    () => form.title,
    (newValue) => {
        form.slug = slugify(newValue);
    }
);
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                New Exhibition
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Step 1: Exhibition information.
            </p>
        </header>

        <form
            @submit.prevent="
                form.post(
                    route('exhibition.store', {
                        lang: locale,
                    })
                )
            "
            class="mt-6 space-y-4 md:space-y-0 md:flex-row md:items-stretch flex flex-col flex-wrap"
        >
            <div class="flex-auto m-1">
                <InputLabel for="title" value="Title" />

                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 w-full"
                    v-model="form.title"
                    required
                    autofocus
                    autocomplete="title"
                />

                <InputError class="mt-2" :message="form.errors.title" />
            </div>
            <div class="flex-auto m-1">
                <InputLabel for="slug" value="Slug" />

                <TextInput
                    disabled
                    id="slug"
                    type="text"
                    class="mt-1 w-full"
                    v-model="form.slug"
                    required
                />

                <InputError class="mt-2" :message="form.errors.slug" />
            </div>
            <div class="flex-none w-full m-1">
                <InputLabel for="description" value="Description" />

                <TextArea
                    id="description"
                    class="mt-1 w-full"
                    v-model="form.description"
                    rows="4"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>
            <div class="w-full m-1 flex-auto">
                <InputLabel for="status" value="Status" />

                <SelectInput
                    :options="statusList"
                    id="status"
                    class="mt-1 w-full"
                    v-model="form.status"
                    required
                />
                <InputError class="mt-2" :message="form.errors.status" />
            </div>

            <div class="flex items-center row-gap-4">
                <PrimaryButton class="mx-1 my-5" :disabled="form.processing">
                    Save and next step
                </PrimaryButton>

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
    </section>
</template>
