import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
export function getUser () {

    const page = usePage()
    const user = computed(() => page.props.user)
    const notifications = computed(() => page.props.notifications)

    const getUserInfo = () => {
        return user.value
    }

    const getNotifications = () => {
        return notifications.value
    }


    return { getUserInfo, getNotifications }
}
