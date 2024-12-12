<script setup>
import { ref } from "vue";
import {getUser} from '../plugins/get-user-plugin'
import profileDropdown from '../layout/dropdowns/profile-image-dropdown.vue'
import { router } from '@inertiajs/vue3'

const { getUserInfo, getNotifications } = getUser()
const user = getUserInfo()
const notifications = getNotifications()
const showDropdown = ref(false)

const logout = () => {
    router.post('/logout', user);
}
const profile = () => {
    router.get('/users/' + user.id)
}

const readNotification = (notification) => {

    router.get('/read-notification/' + notification.id)

    if (notification.is_admin === 1 && notification.type === 'application') {
        window.location.href = '/applications'
    } else if (notification.is_admin === 0 && notification.type === 'application') {
        window.location.href = '/application/get-by-user'
    } else if (notification.is_admin === 1 && notification.type === 'payment') {
        window.location.href = '/payments'
    } else if (notification.is_admin === 0 && notification.type === 'payment') {
        window.location.href = '/payments/get-by-user'
    }



}
</script>

<template>
    <!-- <div class="h-[60px] bg-amber-200 p-4 flex justify-between items-center ">
        <div class="">
            <v-icon scale="1.5" name="fa-align-justify" class="cursor-pointer" @click="$emit('open')"/>
        </div>
        <div class="flex">
            <input type="text" class="rounded-full w-[200%]" placeholder="Search..."/>
        </div>

        <div class="flex justify-between items-center gap-5">
            <div class="flex pr-4 cursor-pointer">
                <v-icon name="fa-envelope" ></v-icon>
            </div>
            <div class="flex pr-4 cursor-pointer">
                <v-icon name="fa-bell"></v-icon>
            </div>

            <div class="cursor-pointer flex justify-between gap-2">
                <button @click="showDropdown = !showDropdown" type="button"  class="inline-flex items-center font-medium justify-center px-4 py-2 text-sm rounded-lg cursor-pointer">
                    <img class="rounded-full w-10 " src="https://sm.ign.com/t/ign_nordic/cover/a/avatar-gen/avatar-generations_prsz.300.jpg" alt="...">
                    <span v-if="user.personal_info" class="text-sm pl-2 pt-2">{{ user.personal_info.first_name }} {{ user.personal_info.last_name }}</span>
                    <span v-else class="text-sm pl-2 pt-2">Client Client</span>
                </button>

                <profileDropdown v-show="showDropdown"/>
            </div>
        </div>

    </div> -->
    <header class="app-header top-bar">
        <nav class="navbar navbar-expand-lg">
            <div class="navbar-header align-items-center d-lg-none d-block">
                <a href="javascript:void:(0)" class="mobile-toggle"><i class="ti ti-align-right"></i></a>
                <a class="navbar-brand" href="index.html">
                    <img src="assets/img/logo.png" class="img-fluid logo-desktop" alt="logo" />
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="navigation d-flex">
                    <ul class="navbar-nav nav-left">
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link sidebar-toggle">
                                <i class="ti ti-align-right"></i>
                            </a>
                        </li>

                        <li class="nav-item full-screen d-none d-lg-block" id="btnFullscreen">
                            <a href="javascript:void(0)" class="nav-link expand">
                                <i class="icon-size-fullscreen"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="search-box ml-auto mr-3 d-none d-xl-block">

                    </div>
                    <ul class="navbar-nav nav-right ml-auto ml-xl-0">
                        <li class="nav-item dropdown mr-2">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fe fe-bell"></i>
                                <span class="nav-label label label-sm label-danger position-absolute top-0 ml-n2 mt-n2" v-if="notifications.length > 0">{{ notifications.length }}</span>
                            </a>
                            <div class="dropdown-menu extended animated fadeIn" aria-labelledby="navbarDropdown">
                                <ul>
                                    <li class="dropdown-header bg-gradient p-3 text-white text-left">Notifications
                                        <a href="#" class="float-right btn btn-square btn-inverse-light btn-xs m-0">
                                            <span class="font-13"> Clear all</span></a>
                                    </li>
                                    <li class="dropdown-body min-h-240 nicescroll" v-for="notification in notifications" :key="notification.id">
                                        <ul class="scrollbar scroll_dark max-h-240 mCustomScrollbar _mCS_2 mCS-autoHide" style="position: relative; overflow: visible;">
                                            <div id="mCSB_2" class="mCustomScrollBox mCS-minimal-dark mCSB_vertical mCSB_outside" tabindex="0" style="max-height: none;">
                                                <div id="mCSB_2_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
                                                    <li>
                                                        <a href="javascript:void(0)" @click="readNotification(notification)">
                                                            <div class="notification d-flex flex-row align-items-center">
                                                                <div class="notify-icon bg-img align-self-center">
                                                                    <div class="avatar-type avatar-type-md">
                                                                        <span>HY</span>
                                                                    </div>
                                                                </div>
                                                                <div class="notify-message">
                                                                    <p class="font-weight-bold">{{ notification.text }}</p>
                                                                    <small>Just now</small>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </div>
                                            </div>

                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown user-profile">
                            <a href="javascript:void(0)" class="nav-link dropdown-toggle " id="navbarDropdown4" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="d-flex align-items-center text-left">
                                <div class="mr-2">
                                    <h6 class="mb-0">{{ user.personal_info?.first_name }} {{ user.personal_info?.last_name }}</h6>
                                    <small class="d-block">{{ user.role.name }}</small>
                                </div>
                                    <div class="avatar position-relative">
                                        <img class="avatar-img rounded-circle" src="/assets/img/avatar/02.jpg" alt="avatar-img">
                                        <span class="bg-success user-status"></span>
                                    </div>

                            </div>
                            </a>
                            <div class="dropdown-menu animated fadeIn" aria-labelledby="navbarDropdown">
                                <div class="px-4 py-3 bg-holder bg-overlay-primary-70" style="background-image: url(assets/img/blog/05.jpg);">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="mr-1">
                                            <h5 class="text-white mb-0">{{ user.personal_info?.first_name }} {{ user.personal_info?.last_name }}</h5>
                                            <small class="text-white">{{ user.email }}</small>
                                        </div>
                                        <a href="#" class="text-white font-20 tooltip-wrapper" data-toggle="tooltip" data-placement="top" title="" data-original-title="Logout"> <i
                                                        class="zmdi zmdi-power"></i></a>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a class="dropdown-item d-flex nav-link" @click.prevent="profile" href="javascript:void(0)">
                                        <i class="far fa-user pr-1 text-success" ></i> Profile</a>
                                    <a class="dropdown-item d-flex nav-link" @click.prevent="logout" href="javascript:void(0)">
                                        <i class="far fa-compass pr-1 text-warning"></i> Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>
