<script setup>
const props = defineProps({
    properties: Object
})

</script>

<template>
<div class="col-xl-12 col-xxl-12 mb-3">
    <div class="d-block d-sm-flex align-items-center justify-content-between mb-2 tab nav-border">
        <div class="card-heading mb-sm-0">
            <h5 class="card-title mb-sm-0">Property overview </h5>
        </div>
    </div>
    <div class="card card-statistics border-0 shadow-none mb-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0 table-border-3">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Property ID</th>
                            <th>Address</th>
                            <th>Number of Lots</th>
                            <th>Lots Occupied </th>
                        </tr>
                    </thead>
                    <tbody class="mb-0">
                        <tr v-for="property in properties">
                            <td>
                                <div class="avatar avatar-lg">
                                    <Image alt="Image" preview>
                                        <template #previewicon>
                                            <i class="pi pi-search"></i>
                                        </template>
                                        <template #image>
                                            <img :src="'/storage/'+property.files[0]?.url" alt="image" class="tw-h-[50px]"/>
                                        </template>
                                        <template #preview="slotProps">
                                            <img :src="'/storage/'+property.files[0]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                                        </template>
                                    </Image>
                                </div>
                            </td>
                            <td>{{ property.id }}</td>
                            <td>
                                Phase {{ property.phase }},
                                Purok {{ property.purok }},
                                Barangay {{ property.barangay }}
                                {{ property.city }} City,
                                {{ property.region }}

                            </td>
                            <td>
                                {{ property.lots.length }}
                            </td>
                            <td>
                                {{ property.lots.filter(lot => lot.status === "Occupied").length }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</template>
