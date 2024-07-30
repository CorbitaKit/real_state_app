<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { router } from '@inertiajs/vue3'


defineOptions({layout: Layout})

const submit = () => {
    router.get('/users/create')
}
</script>

<template>
    <Header @submit="submit" :title="'Staff Page'"  :btnTxt="'Create Staff Account'" />
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <v-table class="mt-4">
            <thead>
            <tr>
                <th class="text-left">
                    Name
                </th>
                <th class="text-left">
                    Email
                </th>
                <th class="text-left">
                    Phone Number
                </th>
                <th class="text-left">
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>
            <tr >
                <td>asdasd</td>
                <td>sadasdsa</td>
                <td>sadasdsa</td>
                <td>
                <div class="flex content-between items-center">
                
                        <button type="button" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <v-icon name="fa-edit"></v-icon>
                        </button>
                    
                        <button type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <v-icon name="fa-trash"></v-icon>
                        </button>
                    
                </div>
                    
                </td>
            </tr>
            
            </tbody>
    </v-table>
    </div>
</template>