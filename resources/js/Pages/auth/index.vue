<script setup>
import { router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";


const form = useForm({
    email: null,
    password: null,
});

const error = ref();
const inputType = ref('password')

const submit = async () => {
    await axios.get("/sanctum/csrf-cookie");
    await form.post("/login", {
        onSuccess: (res) => {
            window.location.href ='/properties'
        },
        onError: (res) => {
            error.value = res;
        },
    });
};

const togglePasswordVisibility = () => {
    // Toggle between 'password' and 'text'
    inputType.value = inputType.value === 'password' ? 'text' : 'password';
}
</script>

<template>
    <div class="app-contant">
        <div class="container">
            <div class="row justify-content-center align-items-center h-100-vh">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center">
                        <div class="login pt-4">
                            <h1 class="mb-2">Jef Aldebal Real State Service </h1>
                            <p>Welcome back, please login to your account.</p>
                            <form action="#" class="mt-3 mt-sm-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Email*</label>
                                            <input type="email" v-model="form.email" class="form-control" placeholder="Email" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Password*</label>
                                            <div class="input-group mb-3">
                                                <input placeholder="Password" :type="inputType" @keyup.enter="submit" v-model="form.password" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-success" type="button" id="button-addon2" @click="togglePasswordVisibility">
                                                        <i class="nav-icon ti ti-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-block d-sm-flex  align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck">
                                                <label class="form-check-label" for="gridCheck">
                                                    Remember Me
                                                </label>
                                            </div>
                                            <a href="javascript:void(0);" class="ml-auto">Forgot Password ?</a>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <a href="#" @click.prevent="submit" class="btn btn-light text-uppercase">Sign In</a>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <p>Don't have an account ?<a href="/register"> Sign Up</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid" src="assets/img/bg/login.svg" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="bg-sky-100 flex justify-center items-center h-screen">
        <div class="w-1/2 h-screen hidden lg:block">
            <img src="/logo.jpeg" alt="Placeholder Image" class="object-cover w-full h-full">
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
               
                <Password class="w-full" @keyup.enter="submit" v-model="form.password" variant="filled" :feedback="false" toggleMask />
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="remember" name="remember" class="text-red-500">
                <label for="remember" class="text-green-900 ml-2">Remember Me</label>
            </div>

            <div class="mb-6 text-blue-500">
                <a href="#" class="hover:underline">Forgot Password?</a>
            </div>
            <button type="button" @click="submit" class="bg-lime-500 hover:lime-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">Login</button>

            <div class="mt-6 text-green-500 text-center">
                <a href="/register" class="hover:underline">Sign up Here</a>
            </div>
        </div>
    </div> -->
</template>

<style>
.p-password-input {
    width:100%;
    border-width: 1px;
    border-radius: 0.375rem;
}
</style>