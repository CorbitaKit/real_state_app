<script setup>
import { router } from '@inertiajs/vue3'
import ProfileMenus from '../../plugins/menu-plugin'
import {getUser} from '../../plugins/get-user-plugin'

const { profile_menus } = ProfileMenus
const { getUserInfo } = getUser()
const user = getUserInfo()
const handleDropdownClick = (menu) => {
    if (menu.name === 'Profile') {
        router.get('/users/' + user.id)
    } else if (menu.name === 'Log Out') {
        router.post('/logout', user);
    }
}
</script>


<template>
    <div class="absolute right-[10px] top-[40px] w-[150px] my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700" id="language-dropdown-menu">
        <ul class="py-2 font-medium" role="none">
            <li v-for="(menu, i) in profile_menus" :key="i">
                <a @click="handleDropdownClick(menu)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                <div class="inline-flex items-center gap-3">
                    <i :class="menu.icon" />
                    {{ menu.name }}
                </div>
                </a>
            </li>
          
        </ul>
    </div>
</template>