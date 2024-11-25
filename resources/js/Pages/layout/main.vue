<script setup>
import { ref } from "vue";
import SideBar from '../layout/sidebar.vue'
import Navbar from '../layout/navbar.vue'

const open = ref(true)
</script>



<template>
    <Navbar />
    <div class="app-container">
        <SideBar />
        <div class="app-main" id="main">
            <div class="container-fluid">
                <slot />
            </div>
        </div>
    </div>
</template>