<script setup>
import { ref } from "vue";
import {getUser} from '../plugins/get-user-plugin'
import profileDropdown from '../layout/dropdowns/profile-image-dropdown.vue'

const { getUserInfo } = getUser()
const user = getUserInfo()
const showDropdown = ref(false)


</script>

<template>
    <div class="h-[60px] bg-amber-200 p-4 flex justify-between items-center ">
        <div class="">
            <v-icon scale="1.5" name="fa-align-justify" class="cursor-pointer" @click="$emit('open')"/>
        </div>
        <div class="flex">
            <input type="text" class="rounded-full w-[200%]" placeholder="Search..."/>
        </div>
        
        <div class="flex justify-between items-center gap-5">
            <div class="flex pr-4 cursor-pointer">
                <v-icon name="fa-envelope" ></v-icon>
            </div>
            <div class="flex pr-4 cursor-pointer">
                <v-icon name="fa-bell"></v-icon>
            </div>
          
            <div class="cursor-pointer flex justify-between gap-2">
                <button @click="showDropdown = !showDropdown" type="button"  class="inline-flex items-center font-medium justify-center px-4 py-2 text-sm rounded-lg cursor-pointer">
                    <img class="rounded-full w-10 " src="https://sm.ign.com/t/ign_nordic/cover/a/avatar-gen/avatar-generations_prsz.300.jpg" alt="...">
                    <span v-if="user.personal_info" class="text-sm pl-2 pt-2">{{ user.personal_info.first_name }} {{ user.personal_info.last_name }}</span> 
                    <span v-else class="text-sm pl-2 pt-2">Client Client</span> 
                </button>

                <profileDropdown v-show="showDropdown"/>
            </div>
        </div>
        
    </div>
</template>