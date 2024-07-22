<script setup>
import { ref } from "vue";
import SideBar from '../layout/sidebar.vue'
import Navbar from '../layout/navbar.vue'

const open = ref(true)
</script>



<template>
    <div class="flex">
        <SideBar :open="open"/>

        <div class="w-screen h-screen bg-gray-100 flex flex-col">
            <Navbar @open="open = !open" class="flex-shrink-0"/>
            <div class="bg-slate-100 p-4 flex-grow overflow-auto">
                <div class="container">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>