import { defineStore } from "pinia";

export const useImageUploadStore = defineStore("imageUploadStore", {
    state: () => ({
        idCount: 0,
        inprogress: 0,
        queueCount: 0,
        imageUploadQueue: [
            // { status: "success",/** "uploading"  "waiting" "error"  */
            //  id: 1,
            //  progress: 80,
            //  name: "somefile name that has a really big name in it even more",
            // error: "file size more than 1MB", name: "somefile name"
            //  },
        ]
    }),
    actions: {
        generateId() {
            return this.idCount = this.idCount++
        },
        addQueuedFile(file) {
            file.status = "waiting"
            file.id = this.generateId()
            this.imageUploadQueue.push(file)
            this.queueCount++
        },
        addInvalidFile(file, error) {
            const errorItem = { id: this.generateId(), status: "error", error, name: file.name }
            this.imageUploadQueue.push(errorItem)
        },
        changeFileStatus(fileIndex, newStatus) {
            if (this.imageUploadQueue[fileIndex]) {
                this.imageUploadQueue[fileIndex].status = newStatus;
                // If needed, you can reset progress or handle other properties
                if (newStatus === "uploading") {
                    this.imageUploadQueue[fileIndex].progress = 0; // Reset progress for new uploads
                    this.inprogress++
                    this.queueCount--
                }
            } else {
                console.error(`file upload with id ${id} not found.`);
            }
        },
        changeFileProgress(fileIndex, newProgress) {
            if (this.imageUploadQueue[fileIndex]) {
                this.imageUploadQueue[fileIndex].progress = newProgress;

            } else {
                console.error(`file upload with id ${id} not found.`);
            }

        },
        validateImage(file) {
            if (
                file.size > 1024 * 1024
            )
                return this.addInvalidFile(file, "image size must be less than 1 MB")
            if (

                (file.type !== "image/jpeg" && file.type !== "image/png")
            )
                return this.addInvalidFile(file, "image type must be png or jpeg ")

            const img = new Image();

            img.src = URL.createObjectURL(file);
            let error = { count: 0, message: "" }
            img.onload = () => {
                if (img.width > 2400)
                    return error = { count: 1, message: "image image width must be less than : 2400" }
                // return this.addInvalidFile(file, "image image width must be less than : 2400")
                if (img.height > 1200)
                    return error = { count: 1, message: "image image height must be less than : 1200" }
                // return this.addInvalidFile(file, "image image height must be less than : 1200")
            };
            console.log(error)

            if (error.count > 0)
                return this.addInvalidFile(file, error.message)

            this.addQueuedFile(file)
        },

        clearAllCompleted() {
            if (this.inprogress === 0) {
                const newQueue = this.imageUploadQueue.filter(item => item.status !== "success" && item.status !== "error")
                this.imageUploadQueue = newQueue
            }
        }

    }
})
