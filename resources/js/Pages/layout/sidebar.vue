<script setup>
import { Link } from '@inertiajs/vue3'
import sidebarMenu from '../plugins/menu-plugin'
import {getUser} from '../plugins/get-user-plugin'

const props = defineProps({
    open: Boolean
})


const { getUserInfo } = getUser()
const { sidebar_menus, admin_side_bar_menus } = sidebarMenu

const user = getUserInfo()

</script>


<template>
    <div :class="['h-screen md:h-auto lg:h-auto p-5 pt-8 bg-gray-800 relative duration-300', open ? 'w-80' : 'hidden']">
        <div class="flex-inline">
            <v-icon 
                name="fa-landmark"
                scale="2"
                fill="green"
                class="text-4xl rounded cursor-pointer block float-left mr-2"
            />
            <h1 :class="['text-white origin-left font-medium text-1xl pt-2 duration-300', !open ? 'hidden': '']">Real State App</h1>
        </div>

        <ul class="pt-3" v-if="user.role_id == 3">
            <li v-for="(menu, i) in sidebar_menus" :key="i" class="text-white  bold text-sm flex items-center gap-x-4 cursor-pointer p-2 hover:bg-sky-700 rounded-md mt-2">
                <Link :href="menu.link">
                    <div class="flex content-between gap-3 bg">
                        <span class="text-2xl block float-left">
                            <v-icon :name="menu.icon" />
                        </span>
                        <span :class="['text-base font-medium flex-1 pt-2', !open ? 'hidden' : '']">
                            {{ menu.name }}
                        </span>
                    </div>
                </Link>
            </li>
        </ul>
        <ul class="pt-3" v-else>
            <li v-for="(menu, i) in admin_side_bar_menus" :key="i" class="text-white bold text-sm flex items-center gap-x-4 cursor-pointer p-2 hover:bg-sky-700 rounded-md mt-2">
                <Link :href="menu.link">
                    <div class="flex content-between gap-3">
                        <span class="text-2xl block float-left">
                            <v-icon :name="menu.icon" />
                        </span>
                        <span :class="['text-base font-medium flex-1 pt-2', !open ? 'hidden' : '']">
                            {{ menu.name }}
                        </span>
                    </div>
                </Link>
            </li>
        </ul>
    </div>
</template>