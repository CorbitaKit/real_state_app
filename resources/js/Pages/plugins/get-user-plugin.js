import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
export function getUser () {

    const page = usePage()
    const user = computed(() => page.props.user)

    const getUserInfo = () => {
        return user.value
    }


    return { getUserInfo }
}