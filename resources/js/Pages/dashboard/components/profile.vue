<script setup>
import { ref } from "vue"


const props = defineProps({
    clients: Object
})

const lots = ref()

const setLots = (user_lots) => {
    lots.value = user_lots
}


</script>

<template>
    <div class="col-xxl-4 mb-3">
        <div class="card card-statistics border-0 shadow-none mb-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0 table-border-3">
                    <thead>
                        <tr>
                            <th>Name</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="mb-0">
                        <tr v-for="client in clients" :key="client.id">
                           <td>
                            {{ client.personal_info.first_name }} 
                            {{ client.personal_info.last_name }} 
                           </td>
                           <td>
                            <a @click="printDiv" href="javascript:void(0);" class="btn btn-icon btn-round btn-inverse-success mr-2 tooltip-wrapper" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print client chart of account">
                                <i class="fas fa-file"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-icon btn-round btn-inverse-info mr-2 tooltip-wrapper" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print client transaction record">
                                <i class="fas fa-file"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-icon btn-round btn-inverse-warning tooltip-wrapper" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print client record">
                                <i class="fas fa-file"></i>
                            </a>
                           </td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
   
</template>