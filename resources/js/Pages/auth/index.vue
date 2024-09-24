<script setup>
import { router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";


const form = useForm({
    email: null,
    password: null,
});

const error = ref();

const submit = async () => {
    await axios.get("/sanctum/csrf-cookie");
    await form.post("/login", {
        onSuccess: (res) => {
            router.get("/properties");
        },
        onError: (res) => {
            error.value = res;
        },
    });
};
</script>

<template>
    <div class="bg-sky-100 flex justify-center items-center h-screen">
        <div class="w-1/2 h-screen hidden lg:block">
            <img src="https://img.freepik.com/fotos-premium/imagen-fondo_910766-187.jpg?w=826" alt="Placeholder Image" class="object-cover w-full h-full">
        </div>
        <div class= "lg:p-36 md:p-52 sm:20 p-8 w-full lg:w-1/2">
            <h1 class="text-2xl font-semibold mb-4">Enter your credentials to Login</h1>
            <div v-if="error">
                <Message  v-for="(e, i) in error" :key="i" severity="error">
                    {{ e }}
                </Message>
            </div>
            <div class="mb-4 bg-sky-100">
                <label for="email" class="block text-gray-600">Email</label>
                <input type="email" v-model="form.email" id="email" name="email" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-800">Password</label>
                <!-- <input type="password" v-model="form.password" id="password" name="password" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off"> -->
                <Password class="w-full" @keyup.enter="submit" v-model="form.password" variant="filled" :feedback="false" toggleMask />
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="remember" name="remember" class="text-red-500">
                <label for="remember" class="text-green-900 ml-2">Remember Me</label>
            </div>

            <div class="mb-6 text-blue-500">
                <a href="#" class="hover:underline">Forgot Password?</a>
            </div>
            <button type="button" @click="submit" class="bg-red-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">Login</button>

            <div class="mt-6 text-green-500 text-center">
                <a href="/register" class="hover:underline">Sign up Here</a>
            </div>
        </div>
    </div>
</template>

<style>
.p-password-input {
    width:100%;
    border-width: 1px;
    border-radius: 0.375rem;
}
</style>