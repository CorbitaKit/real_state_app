import { useToast } from "primevue/usetoast"



export const useToaster = () => {
    const toast = useToast();

    const show = (severity, summary, detail) => {
        toast.add({ severity: severity, summary: summary, detail: detail, life: 3000 });
    };

    return { show }
}