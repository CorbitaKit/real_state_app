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
    <header class="tw-bg-lime-400 tw-text-black tw-shadow-md tw-h-full app-style">
      <div class="container tw-mx-auto tw-px-6 tw-py-4 tw-flex tw-items-center tw-justify-between">
        <!-- Logo -->
        <div class="tw-flex tw-items-center tw-space-x-4">
          <img src="/logo.jpeg" alt="RealEstateApp Logo" class="tw-h-12">
          <h1 class="tw-text-3xl tw-text-black tw-font-bold tw-hidden tw-md:block">Jef Aldebal Realty Service</h1>
        </div>
        <!-- Navigation -->
        <nav>
          <ul class="tw-flex tw-space-x-6">
            <li><a href="#" @click.prevent="home" class="tw-text-black tw-hover:text-gray-300">Home</a></li>
            <li><a href="#properties" class="tw-hover:text-gray-300 tw-text-black">Properties</a></li>
            <li><a href="#contact" class="tw-hover:text-gray-300 tw-text-black">Contact</a></li>
            <li><a href="#" @click.prevent="login" class="tw-hover:text-gray-300 tw-text-black">Login</a></li>
          </ul>
        </nav>
      </div>
    </header>
</template>

<style>
.app-style{
  
  background: linear-gradient(-154deg, #C7E9C0 8%, #238B46 90%);

}
</style>