<script setup>
import { router } from "@inertiajs/vue3"

const home = () => {
  router.get('/')
}

const login = () => {
  router.get('/login')
}
</script>


<template>
    <header class="header text-white shadow-md h-full">
      <div class="container mx-auto px-6 py-4 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-4">
          <img src="/logo.jpeg" alt="RealEstateApp Logo" class="h-12">
          <h1 class="text-3xl font-bold hidden md:block">Jef Aldebal Realty Service</h1>
        </div>
        <!-- Navigation -->
        <nav>
          <ul class="flex space-x-6">
            <li><a href="#" @click.prevent="home" class="hover:text-gray-300">Home</a></li>
            <li><a href="#properties" class="hover:text-gray-300">Properties</a></li>
            <li><a href="#contact" class="hover:text-gray-300">Contact</a></li>
            <li><a href="#" @click.prevent="login" class="hover:text-gray-300">Login</a></li>
          </ul>
        </nav>
      </div>
    </header>
</template>

<style scoped>
.header {
  background-color: red;
}
</style>