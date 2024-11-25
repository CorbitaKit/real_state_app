<script setup>
import { router, useForm } from "@inertiajs/vue3"
import { ref } from "vue"

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    role_id: 3
})

const error = ref()
const submit = async () => {
    await form.post('/register',{
        onSuccess: (res) => {
            router.get("/dashboard");
        },
        onError: (res) => {
            error.value = res;
        },
    })
}

const login = () => {
    router.get('/login')
}
</script>

<template>
     <div class="app-contant">
                    <div class="container">
                        <div class="row justify-content-center align-items-center h-100-vh">
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center">
                                    <div class="register pt-4">
                                        <h1 class="mb-2">Jef Aldebal Real State Service </h1>
                                        <p>Welcome, Please create your account.</p>
                                        <div v-if="error">
                                            <p  v-for="(e, i) in error" :key="i" severity="error">
                                                {{ e }}
                                            </p>
                                            <div v-for="(e, i) in error" :key="i" class="alert alert-danger mb-2" role="alert">
                                                    {{ e }}
                                                </div>
                                        </div>
                                        
                                        <form action="https://themes.potenzaglobalsolutions.com/html/arioxa/auth-register.html" class="mt-2 mt-sm-5">
                                            <div class="row">
                                                
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Email*</label>
                                                        <input v-model="form.email" type="email" class="form-control" placeholder="Email" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Password*</label>
                                                        <input v-model="form.password" type="password" class="form-control" placeholder="Password" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Password Confirmation*</label>
                                                        <input v-model="form.password_confirmation" type="password" class="form-control" placeholder="Password" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="gridCheck">
                                                        <label class="form-check-label" for="gridCheck">
                                                            I accept terms & policy
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <a href="#" @click="submit" class="btn btn-light text-uppercase">Sign up</a>
                                                </div>
                                                <div class="col-12  mt-3">
                                                    <p>Already have an account ?<a href="/login"> Sign In</a></p>
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
        <Toast />
        <div class="w-1/2 h-screen hidden lg:block">
            <img src="/logo.jpeg" alt="Placeholder Image" class="object-cover w-full h-full">
        </div>
        <div class= "lg:p-36 md:p-52 sm:20 p-8 w-full lg:w-1/2">
            <h1 class="text-2xl font-semibold mb-4">Enter your credential details to Register</h1>
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
                
                <Password class="w-full" v-model="form.password" variant="filled" toggleMask />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-800">Confirm Password</label>
               
                <Password class="w-full" v-model="form.password_confirmation" variant="filled" toggleMask />
            </div>
           
            <button type="button" @click="submit" class="bg-lime-500 hover:bg-green-600 mb-2 text-white font-semibold rounded-md py-2 px-4 w-full">Register</button>
            <div class="mt-6 text-green-500 text-center">
                <a href="/login" class="hover:underline">Already have an account? Click here to</a>
            </div>
        </div>
    </div> -->
</template>

