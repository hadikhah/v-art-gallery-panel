<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    songs: {
        type: Array,
        required: true,
    },
    selectedSongs: {
        type: Array,
        required: true,
    },
    selectedSongs: {
        type: Array,
        required: true,
    },
    showAudioModal: {
        type: Boolean,
    },
    playlistError: {
        type: String,
    },
    musicEnabledError: {
        type: String,
    },
});

const musicEnabled = defineModel({
    type: Boolean,
});

console.log(props.selectedSongs);

const emit = defineEmits([
    "showModal",
    "closeModal",
    "toggleSongSelection",
    "saveAudioSettings",
]);
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-medium mb-6 dark:text-gray-200">
            Audio Settings
        </h3>
        <div class="space-y-4">
            <label class="flex items-center space-x-3 cursor-pointer">
                <div class="relative">
                    <input
                        type="checkbox"
                        v-model="musicEnabled"
                        class="sr-only peer"
                    />
                    <div
                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-gray-800 dark:peer-checked:bg-indigo-500 after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all dark:bg-gray-700"
                    ></div>
                </div>
                <span class="text-sm font-medium dark:text-gray-300"
                    >Enable Background Music</span
                >
                <InputError class="mt-2" :message="musicEnabledError" />
            </label>
            <div v-if="musicEnabled">
                <PrimaryButton
                    @click="emit('showModal')"
                    class="w-full dark:focus:bg-indigo-800 px-4 py-2 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition duration-200 justify-center"
                >
                    Manage Playlist ({{ selectedSongs.length }}
                    songs selected)
                </PrimaryButton>
            </div>
            <InputError class="mt-2" :message="playlistError" />
        </div>
    </div>

    <!-- Audio Selection Modal -->
    <Modal :show="showAudioModal" @close="emit('closeModal')">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full max-h-[80vh] overflow-hidden"
        >
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium dark:text-gray-200">
                        Manage Playlist
                    </h3>
                    <button
                        @click="emit('closeModal')"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <!-- Grid View -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div
                        v-for="song in songs"
                        :key="song.id"
                        class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 cursor-pointer"
                        :class="{
                            'ring-2 ring-blue-500': selectedSongs
                            .map((song) => song.id).includes(
                                song.id
                            ),
                        }"
                        @click="emit('toggleSongSelection', song)"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center"
                                >
                                    <img
                                        :src="song.thumbnail_url"
                                        alt=""
                                        class="rounded-full"
                                    />
                                    <svg
                                        class="w-6 h-6 text-blue-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate"
                                >
                                    {{ song.title }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ song.duration }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div
                                    class="w-6 h-6 border-2 rounded-full flex items-center justify-center"
                                    :class="{
                                        'bg-blue-500 border-blue-500':
                                            selectedSongs
                                                .map((song) => song.id)
                                                .includes(song.id),
                                        'border-gray-300 dark:border-gray-500':
                                            !selectedSongs.map((song) => song.id)
                                            .includes(song.id),
                                    }"
                                >
                                    <svg
                                        v-if="selectedSongs
                                                .map((song) => song.id).includes(song.id)"
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end gap-4">
                    <button
                        @click="emit('closeModal')"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
                    >
                        Cancel
                    </button>
                    <PrimaryButton
                        @click="emit('saveAudioSettings')"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                    >
                        Save Playlist
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </Modal>
</template>
