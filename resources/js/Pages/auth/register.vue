<script setup>
import { router, useForm } from "@inertiajs/vue3"
import { ref } from "vue"

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    role_id: 3
})

const inputType1 = ref('password')
const inputType2 = ref('password')

const error = ref()
const submit = async () => {
    await form.post('/register',{
        onSuccess: (res) => {
            router.get("/email/verify");
        },
        onError: (res) => {
            error.value = res;
        },
    })
}

const login = () => {
    router.get('/login')
}


const togglePasswordVisibility = () => {
    // Toggle between 'password' and 'text'
    inputType1.value = inputType1.value === 'password' ? 'text' : 'password';
}


const togglePasswordConfirmationVisibility = () => {
    // Toggle between 'password' and 'text'
    inputType2.value = inputType2.value === 'password' ? 'text' : 'password';
}
</script>

<template>
     <div class="app-contant">
        <div class="container">
            <div class="row justify-content-center align-items-center h-100-vh">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center">
                        <div class="register pt-4">
                            <img src="/logo101.png" alt="RealEstateApp Logo" class="tw-h-12">
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
                                            <!-- <input v-model="form.password" type="password" class="form-control" placeholder="Password" /> -->
                                            <div class="input-group mb-3">
                                                <input placeholder="Password" :type="inputType1" v-model="form.password" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-success" type="button" id="button-addon2" @click="togglePasswordVisibility">
                                                        <i class="nav-icon ti ti-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Password Confirmation*</label>
                                            <!-- <input v-model="form.password_confirmation" type="password" class="form-control" placeholder="Password" /> -->
                                            <div class="input-group mb-3">
                                                <input placeholder="Password Confirmation" :type="inputType2" v-model="form.password_confirmation" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-success" type="button" id="button-addon2" @click="togglePasswordConfirmationVisibility">
                                                        <i class="nav-icon ti ti-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
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
</template>

