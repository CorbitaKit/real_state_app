<script setup>
import { ref, watch } from "vue"


const props = defineProps({
    file: {
    type: Object,
    required: true
  },
    height: String,
    width: String
})

const emit = defineEmits(['update:file'])

const isImage = ref(false)
const filePreview = ref()



const handleFileUpload = (event) => {
    
  const uploadedFile = event.target.files[0]
  if (uploadedFile) {
    props.file.value = uploadedFile

    if (uploadedFile.type.startsWith('image/')) {
        isImage.value = true
        const reader = new FileReader()
        reader.onload = (e) => {
            filePreview.value = e.target.result
        }
        reader.readAsDataURL(uploadedFile)
        emit('update:file', uploadedFile)
    } else {
        isImage.value = false
        filePreview.value = null
    }
  }
}

</script>

<template>
    <div class="mb-4">
        <label for="file-upload" class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-w-full tw-h-32 tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-cursor-pointer tw-hover:bg-gray-100">
            <svg class="tw-w-8 tw-h-8 tw-text-gray-500 tw-mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0l7-7m-7 7H3m4 0h4M7 20v4m0 0l-7-7m0 0h7m-7 7H3m4-7h4m0 4v4m0 0h7m-7-4h4m-4 0v4" />
            </svg>
            <span class="tw-text-gray-500">Click to upload or drag and drop</span>
            <input id="file-upload" type="file" class="tw-hidden" @change="handleFileUpload" />
        </label>
        
    </div>
    <div class="tw-mb-4">
        <div v-if="file" >
            <img v-if="isImage" :src="filePreview" alt="File preview" class="tw-rounded-lg" :class="[height ? height : 'tw-h-full', weight ? weight : 'tw-w-full']"/>
        </div>
    </div>
</template>