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
</script>


<template>
      <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8" >
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900" >
               Create your account
            </h2>
        </div>
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="#" method="POST">
                <div>
                    <label  for="email" class="block text-sm font-medium leading-6 text-gray-900" >Email address</label >
                    <div class="mt-2">
                        <input id="email" v-model="form.email"  name="email" type="email" autocomplete="email" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    </div>
                    <div class="mt-2">
                        <input id="password" v-model="form.password" name="password" type="password" autocomplete="current-password" @keyup.enter="submit" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                    </div>
                    <div class="mt-2">
                        <input id="password_confirm" v-model="form.password_confirmation" name="password" type="password" autocomplete="current-password" @keyup.enter="submit" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                    </div>
                </div>
                
                <div>
                    <button @click="submit" type="button" class="flex w-full justify-center rounded-md bg-green-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Create Account
                    </button>
                </div>
                <div v-if="error">
                    <Message  v-for="(e, i) in error" :key="i" severity="error">
                        {{ e }}
                    </Message>
                </div>
                
            </form>
        </div>
    </div>
</template>

