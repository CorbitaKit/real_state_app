
const sidebar_menus = [
    // {
    //     name: 'Dashboard',
    //     icon: 'fa-list-alt',
    //     link: '/dashboard',

    // },
    {
        name: 'Properties',
        icon: 'fa-landmark',
        link: '/properties',
    },
    {
        name: 'My Payments',
        icon: 'fa-user-friends',
        link: '/payments/get-by-user'
    },
    {
        name: 'My Applications',
        icon: 'fa-tasks',
        link: '/application/get-by-user',
    },
    {
        name: 'My Properties',
        icon: 'fa-landmark',
        link: '/lots/get-client-lots',
    },

    
]

const admin_side_bar_menus = [
    {
        name: 'Dashboard',
        icon: 'ti ti-list',
        link: '/dashboard',
    },
    {
        name: 'Staffs',
        icon: 'ti ti-user',
        link: '/users'
    },
    {
        name: 'Clients',
        icon: 'ti ti-user',
        link: '/clients'
    },
    {
        name: 'Properties',
        icon: 'ti ti-home',
        link: '/properties',
    },
    // {
    //     name: 'Memberships',
    //     icon: 'fa-users',
    //     link: '/memberships',
    // },
    // {
    //     name: 'Transactions',
    //     icon: 'fa-chart-line',
    //     link: '/transactions',
    // },
    {
        name: 'Applications',
        icon: 'ti ti-write',
        link: '/applications',
    },
    {
        name: 'Billings',
        icon: 'ti ti-receipt',
        link: '/payments',
    },
    {
        name: 'Reports',
        icon: 'ti ti-folder',
        link: '/reports',
    },
]


//Navbar profile menu
//Icons used PrimeVue https://primevue.org/icons/
const profile_menus = [
    {
        name: 'Profile',
        icon: 'pi pi-user'
    },
    {
        name: 'Log Out',
        icon: 'pi pi-sign-out'
    }
]


export default {
    sidebar_menus,
    profile_menus,
    admin_side_bar_menus
}