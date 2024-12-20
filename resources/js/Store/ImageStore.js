import { defineStore } from "pinia"


export const useImageStore = defineStore("imageStore", {
    state: () => ({
        selectedImageIds: [],
        selectedRemoveImageIds: [],
    }),
    actions: {
        toggleAddImage(id) {
            const idIndex = this.selectedImageIds.indexOf(id)

            if (idIndex === -1)
                return this.selectedImageIds.push(id)

            return this.selectedImageIds.splice(idIndex, 1)
        },
        toggleRemoveImage(id) {
            const idIndex = this.selectedRemoveImageIds.indexOf(id)

            if (idIndex === -1)
                return this.selectedRemoveImageIds.push(id)

            return this.selectedRemoveImageIds.splice(idIndex, 1)
        },
        replaceBatchImages(ids = []) {
            return this.selectedImageIds = ids
        },
        replaceBatchSelectedRemoveImages(ids = []) {
            return this.selectedRemoveImageIds = ids
        }
    }


})
