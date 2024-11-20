<script setup>
import { Link } from '@inertiajs/vue3'
import sidebarMenu from '../plugins/menu-plugin'
import {getUser} from '../plugins/get-user-plugin'
import { router } from '@inertiajs/vue3'


const { getUserInfo } = getUser()
const { sidebar_menus, admin_side_bar_menus } = sidebarMenu

const user = getUserInfo()

const redirect = (route) => {
    router.get(route)
}

</script>


<template>
    <aside class="app-navbar">
        <!-- begin navbar-header -->
        <div class="navbar-header align-items-center d-lg-block d-none">
            <a class="navbar-brand" href="index.html">
                <img src="/assets/img/logo.png" class="img-fluid logo-desktop" alt="logo" />
                <img src="/assets/img/logo-icon.png" class="img-fluid logo-mobile" alt="logo" />
            </a>
        </div>
        <!-- begin sidebar-nav -->
        <div class="sidebar-nav scrollbar scroll_light">
            <div class="d-flex align-items-center text-left px-2 mb-2 user-setting">
                <div class="position-relative">
                    <div class="avatar">
                        <img class="avatar-img rounded-circle" src="/assets/img/avatar/02.jpg" alt="avatar-img">
                        <span class="bg-success user-status"></span>
                    </div>
                </div>
                <div class="ml-2">
                    <h6 class="mb-0 text-white">{{ user.personal_info.first_name }} {{ user.personal_info.last_name }}</h6>
                    <small class="d-block text-white">{{ user.role.name }}</small>
                </div>
           
            </div>
            <ul class="metismenu" id="sidebarNav">
                <!-- <li class="active" v-for="menu in admin_side_bar_menus">
                    <a :href="menu.link" aria-expanded="false">
                        <i class="nav-icon" :class="menu.icon"></i>
                        <span class="nav-title">{{ menu.name }}</span>
                    </a>
                </li> -->
                <li class="active">
                    <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="nav-icon ti ti-home"></i>
                        <span class="nav-title">Properties</span>
                    </a>
                    <ul aria-expanded="false">
                        <li> <a href='/properties'><i class="nav-icon ti ti-list"></i> Property List</a> </li>
                        <li> <a href='/properties/create'><i class="nav-icon ti ti-plus"></i>Add Property</a> </li>
                        
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="nav-icon ti ti-user"></i>
                        <span class="nav-title">Clients</span>
                    </a>
                    <ul aria-expanded="false">
                        <li> <a href='/clients'><i class="nav-icon ti ti-user"></i> Client List</a> </li>
                        <li> <a href='/users/create-client'><i class="nav-icon ti ti-plus"></i>Add client</a> </li>
                        
                    </ul>
                </li>
            </ul>
        </div>
        <!-- end sidebar-nav -->
    </aside>
</template>