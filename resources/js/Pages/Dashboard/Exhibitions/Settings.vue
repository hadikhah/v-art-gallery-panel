<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PreviewRoom from "./Partials/Settings/PreviewRoom.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { onMounted, reactive, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextureManagement from "./Partials/Settings/TextureManagement.vue";
import Dimensions from "./Partials/Settings/Dimensions.vue";
import AudioSettings from "./Partials/Settings/AudioSettings.vue";

const props = defineProps({
    exhibition: {
        type: Object,
    },
    textures: {
        type: Array,
    },
    textureDefaultTypes: {
        type: Object,
    },
    defaultSettings: {
        type: Object,
    },
    defaultTextures: {
        type: Object,
    },
    songs: {
        type: Array,
    },
});

const locale = usePage().props.locale;

const showTextureModal = ref(false);
const showAudioModal = ref(false);
const selectedTextureType = ref(null);

onMounted(()=>(selectedTextureType.value = "wall"));

const selectedSongs = ref(props.exhibition?.songs || []);

const textureUrls = reactive({
    ceiling:
        props.exhibition.ceiling_texture?.url ?? props.defaultTextures.ceiling,
    wall: props.exhibition.wall_texture?.url ?? props.defaultTextures.wall,
    floor: props.exhibition.floor_texture?.url ?? props.defaultTextures.floor,
});

const form = useForm({
    map_size: props.exhibition?.map_size || 1,
    cell_size: props.exhibition?.cell_size || 10,
    wall_thickness: props.exhibition?.wall_thickness || 0.4,

    ceiling_texture_id: props.exhibition.ceiling_texture?.id,
    wall_texture_id: props.exhibition.wall_texture?.id,
    floor_texture_id: props.exhibition.floor_texture?.id,

    has_background_song: props.exhibition?.has_background_song || false,
    playlist: props.exhibition?.songs?.map((song) => song.id) || [],
});

const updateTexture = (texture) => {
    form[`${selectedTextureType.value}_texture_id`] = texture.id;
    textureUrls[selectedTextureType.value] = texture.url;
    showTextureModal.value = false;
};

const openTextureModal = (type) => {
    selectedTextureType.value = type;
    showTextureModal.value = true;
};
const closeTextureModal = () => {
    showTextureModal.value = false;
};

const showSongsModal = () => {
    showAudioModal.value = true;
};

const closeSongsModal = () => {
    showAudioModal.value = false;
};
const toggleSongSelection = (song) => {
    const index = selectedSongs.value.findIndex((s) => s.id === song.id);
    if (index === -1) selectedSongs.value.push(song);
    else selectedSongs.value.splice(index, 1);
};

const saveAudioSettings = () => {
    form.playlist = selectedSongs.value.map((song) => song.id);
    showAudioModal.value = false;
};

const saveSettings = () => {
    if (form.isDirty) {
        console.log(form.data());
        form.patch(
            route("exhibition.settings.update", {
                lang: locale,
                exhibition: props.exhibition.id,
            }),
            {
                preserveScroll: true,
            }
        );
    }
};
</script>

<template>
    <Head title="Exhibition Settings" />
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100"
            >
                Exhibition Settings
            </h2>
        </template>

        <div class="py-5 px-4 sm:px-6 lg:px-8">
            <div class="max-w-8xl mx-auto">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Preview Section  -->

                    <PreviewRoom
                        :ceilingTexture="textureUrls.ceiling"
                        :wallTexture="textureUrls.wall"
                        :floorTexture="textureUrls.floor"
                        class="w-full"
                    ></PreviewRoom>
                    <!-- Settings Section -->
                    <div class="lg:w-1/3 space-y-6">
                        <PrimaryButton
                            v-show="form.isDirty"
                            @click="saveSettings"
                            class="w-full dark:focus:bg-indigo-800 full dark:bg-indigo-700 dark:hover:bg-indigo-800 !text-center py-3 px-6 rounded-xl transition duration-200 ease-in-out transform hover:scale-95 justify-center shadow-lg"
                        >
                            Save Settings
                        </PrimaryButton>
                        <!-- Texture Management -->
                        <TextureManagement
                            :ceilingTexture="textureUrls.ceiling"
                            :wallTexture="textureUrls.wall"
                            :floorTexture="textureUrls.floor"
                            :ceilingTextureError="
                                form.errors.ceiling_texture_id
                            "
                            :wallTextureError="form.errors.wall_texture_id"
                            :floorTextureError="form.errors.floor_texture_id"
                            @showModal="openTextureModal"
                            @closeModal="closeTextureModal"
                            @updateTexture="updateTexture"
                            :defaultTextures
                            :showTextureModal
                            :selectedTextureType
                            :textures
                        />

                        <!-- Exhibition Dimensions with Range Inputs -->
                        <Dimensions
                            v-model:area_size="form.map_size"
                            v-model:room_size="form.cell_size"
                            v-model:wall_thickness="form.wall_thickness"
                            :areaSizeError="form.errors.map_size"
                            :roomSizeError="form.errors.cell_size"
                            :wallThicknessError="form.errors.wall_thickness"
                        />
                        <!-- Audio Settings -->
                        <AudioSettings
                            v-model="form.has_background_song"
                            @showModal="showSongsModal"
                            @closeModal="closeSongsModal"
                            @toggleSongSelection="toggleSongSelection"
                            @saveAudioSettings="saveAudioSettings"
                            :songs
                            :selectedSongs
                            :showAudioModal
                            :playlistError="form.errors.playlist"
                            :musicEnabledError="form.errors.has_background_song"
                        />
                        <!-- Save Button -->
                        <PrimaryButton
                            v-show="form.isDirty"
                            @click="saveSettings"
                            class="w-full full dark:focus:bg-indigo-800 dark:bg-indigo-700 dark:hover:bg-indigo-800 !text-center py-3 px-6 rounded-xl transition duration-200 shadow-lg ease-in-out transform hover:scale-95 justify-center"
                        >
                            Save Settings
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
.overflow-y-auto::-webkit-scrollbar {
    width: 8px !important;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #444 !important ;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #666 !important;
    border-radius: 8px ip !important;
    cursor: pointer;
}
</style>
