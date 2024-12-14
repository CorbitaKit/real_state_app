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
    <header class="tw-bg-[#FCE35F40] tw-text-[#093E00] tw-shadow-md tw-h-full">
      <div class="container tw-mx-auto tw-px-6 tw-py-4 tw-flex tw-items-center tw-justify-between">
        <!-- Logo -->
        <div class="tw-flex tw-items-center tw-space-x-4">
          <img src="/logo101.png" alt="RealEstateApp Logo" class="tw-h-12">
          <h1 class="tw-text-3xl tw-text-[#093E00] tw-font-bold tw-hidden tw-md:block">Jef Aldebal Realty Service</h1>
        </div>
        <!-- Navigation -->
        <nav>
          <ul class="tw-flex tw-space-x-8 tw-font-bold">
            <li><a href="#" @click.prevent="home" class="tw-text-[#093E00] tw-hover:text-gray-300">Home</a></li>
            <li><a href="#properties" class="tw-hover:text-gray-300 tw-text-[#093E00]">Properties</a></li>
            <li><a href="#contact" class="tw-hover:text-gray-300 tw-text-[#093E00]">Contact</a></li>
            <li>
              <a href="#" @click.prevent="login" class="tw-bg-green-900 tw-text-white tw-px-8 tw-py-2 tw-rounded-full tw-text-lg ">
                Login
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </header>
</template>


