<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

const props = defineProps({
    options: {
        type: Array,
    },
    optionValueKeyName: {
        type: String,
    },
    optionTextKeyName: {
        type: String,
    },
    default: {
        type: String,
    },
    selected: {
        type: String,
    },
});
const optionValueKeyName = props.optionValueKeyName;
const optionTextKeyName = props.optionTextKeyName;

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        ref="input"
        v-model="model"
        class="rounded-md cursor-pointer border border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
        :key="key"
    >
        <option
            v-for="option in options"
            :key="option.value"
            :value="option[optionValueKeyName ?? `value`]"
            :default="option.default"
        >
            {{ option[optionTextKeyName ?? `value`] }}
        </option>
    </select>
</template>
