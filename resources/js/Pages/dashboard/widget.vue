<script setup>
const props = defineProps(
    {
        clients: Object,
        applications: Object,
        payments: Number,
        properties: Number
    },
)
</script>


<template>
    <div class="grid grid-cols-4 gap-3 mx-auto mb-4">
        <div class="bg-white h-[100px] flex items-center p-9">
            <div class="flex content-between items-center gap-4">
                <v-icon  name="fa-users" scale="2"></v-icon>
                <h2 class="bold">
                    Clients
                </h2>
                <span>
                    <Tag :value="clients.length" />
                </span>
            </div>


        </div>
        <div class="bg-white h-[100px] flex items-center p-9">
            <div class="flex content-between items-center gap-4">
                <v-icon  name="fa-tasks" scale="2"></v-icon>
                <span class="bold">
                    Applications
                </span>

                <span>
                    <Tag :value="applications.length" />
                </span>
            </div>
        </div>
        <div class="bg-white h-[100px] flex items-center p-9">
            <div class="flex content-between items-center gap-4">
                <v-icon  name="fa-receipt" scale="2"></v-icon>
                <span class="bold">
                    Payments
                </span>
                <span>
                    <Tag :value="payments" />
                </span>
            </div>
        </div>
        <div class="bg-white h-[100px] flex items-center p-9">
            <div class="flex content-between items-center gap-4">
                <v-icon  name="fa-landmark" scale="2"></v-icon>
                <span class="bold">
                    Properties
                </span>
                <span>
                    <Tag :value="properties" />
                </span>
            </div>
        </div>
    </div>

</template>
