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

const isActive = (path) => {
    return window.location.pathname === path;
};

</script>
<!-- QF4UK7BVJ23K1QRADGB4BPJT -->

<template>
    <aside class="app-navbar">
        <!-- begin navbar-header -->
        <div class="navbar-header align-items-center d-lg-block d-none">
            <a class="navbar-brand" href="/dashboard">
                <img src="/logo-final.png" class="img-fluid logo-desktop tw-p-6" alt="logo" />
                <img src="/assets/img/logo-icon.png" class="img-fluid logo-mobile" alt="logo" />
            </a>
        </div>
        <!-- begin sidebar-nav -->
        <div class="sidebar-nav scrollbar scroll_light">

            <ul class="metismenu" id="sidebarNav">
                <!-- <li class="active" v-for="menu in admin_side_bar_menus">
                    <a :href="menu.link" aria-expanded="false">
                        <i class="nav-icon" :class="menu.icon"></i>
                        <span class="nav-title">{{ menu.name }}</span>
                    </a>
                </li> -->
                <li  :class="{active: isActive('/dashboard')}" v-if="user.role.name != 'Staff'">
                    <a href="/dashboard" aria-expanded="false">
                        <i class="nav-icon ti ti-list"></i>
                        <span class="nav-title">Dashboard</span>
                    </a>
                </li>
                <li :class="{active: isActive('/properties') || isActive('/properties/create')}" v-if="user.role.name != 'Client'">
                    <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="nav-icon ti ti-home"></i>
                        <span class="nav-title">Properties</span>
                    </a>
                    <ul aria-expanded="false">
                        <li> <a href='/properties'><i class="nav-icon ti ti-list"></i> Property List</a> </li>
                        <li v-if="user.role.name === 'Admin'"> <a href='/properties/create'><i class="nav-icon ti ti-plus"></i>Add Property</a> </li>

                    </ul>
                </li>
                <li :class="{active: isActive('/properties') || isActive('/properties/create')}" v-if="user.role.name === 'Client'">
                    <a  href="/properties" aria-expanded="false">
                        <i class="nav-icon ti ti-home"></i>
                        <span class="nav-title">Properties</span>
                    </a>

                </li>
                <li v-if="user.role.name === 'Admin'" :class="{active: isActive('/clients') || isActive('/users/create')}">
                    <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="nav-icon ti ti-user"></i>
                        <span class="nav-title">Staffs</span>
                    </a>
                    <ul aria-expanded="false">
                        <li> <a href='/users'><i class="nav-icon ti ti-user"></i> Staff List</a> </li>
                        <li> <a href='/users/create'><i class="nav-icon ti ti-plus"></i>Add Staff</a> </li>

                    </ul>
                </li>
                <li v-if="user.role.name === 'Admin' || user.role.name === 'Staff'" :class="{active: isActive('/clients') || isActive('/users/create-client')}">
                    <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="nav-icon ti ti-user"></i>
                        <span class="nav-title">Clients</span>
                    </a>
                    <ul aria-expanded="false">
                        <li> <a href='/clients'><i class="nav-icon ti ti-user"></i> Client List</a> </li>
                        <li> <a href='/users/create-client'><i class="nav-icon ti ti-plus"></i>Add client</a> </li>

                    </ul>
                </li>
                <li :class="{active: isActive('/applications')}" v-if="user.role.name === 'Admin' || user.role.name === 'Staff'">
                    <a href="/applications" aria-expanded="false">
                        <i class="nav-icon ti ti-receipt"></i>
                        <span class="nav-title">Application List</span>
                    </a>

                </li>
                <li :class="{active: isActive('/payments')}" v-if="user.role.name === 'Admin' || user.role.name === 'Staff'">
                    <a href="/payments" aria-expanded="false">
                        <i class="nav-icon ti ti-receipt"></i>
                        <span class="nav-title">Billings</span>
                    </a>
                </li>
                <li :class="{active: isActive('/payments/get-by-user') || isActive('/payments/create')}" v-if="user.role.name === 'Client'">
                    <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <i class="nav-icon ti ti-receipt"></i>
                        <span class="nav-title">My Payments</span>
                    </a>
                    <ul aria-expanded="false">
                        <li> <a href='/payments/get-by-user'><i class="nav-icon ti ti-receipt"></i> Payment List</a> </li>
                        <li> <a href='/payments/create'><i class="nav-icon ti ti-receipt"></i> Make A Payment</a> </li>

                    </ul>
                </li>
                <li :class="{active: isActive('/application/get-by-user')}"  v-if="user.role.name === 'Client'">
                    <a href="/application/get-by-user" aria-expanded="false">
                        <i class="nav-icon ti ti-file"></i>
                        <span class="nav-title">My Application</span>
                    </a>
                </li>
                <li :class="{active: isActive('/lots/get-client-lots')}" v-if="user.role.name === 'Client'">
                    <a href="/lots/get-client-lots" aria-expanded="false">
                        <i class="nav-icon ti ti-file"></i>
                        <span class="nav-title">My Properties</span>
                    </a>
                </li>

                <li :class="{active: isActive('/reports')}"  v-if="user.role.name === 'Admin'">
                    <a href="/reports" aria-expanded="false">
                        <i class="nav-icon ti ti-folder"></i>
                        <span class="nav-title">Reports</span>
                    </a>
                </li>
                <!-- <li :class="{active: isActive('/documents')}"  v-if="user.role.name === 'Client'">
                    <a href="/documents" aria-expanded="false">
                        <i class="nav-icon ti ti-folder"></i>
                        <span class="nav-title">My Documents</span>
                    </a>
                </li> -->

            </ul>
        </div>
        <!-- end sidebar-nav -->
    </aside>
</template>

