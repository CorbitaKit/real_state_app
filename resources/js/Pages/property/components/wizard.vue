<script setup>
import FileUpload  from '../../components/fileupload.vue';
import Form from './form.vue'
import Lot from './lot.vue'
import Map from './map.vue'
import Block from './block.vue'
import { usePropertyStore } from '../store/store'
import { useToaster } from '../../composables/toast'
import Swal from 'sweetalert2'

const { show } = useToaster()
const useProperty = usePropertyStore()
const emits = defineEmits(['submit'])


const checkIfFileUploaded = () => {
    if (useProperty.property.file.value === undefined) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please upload a select an image first",
        });
        return false
    }
    return true
}

const checkIfFieldsAreFilled = () => {
   
    const excludedKey = [
        'file',
        'number_of_lot',
        'lots',
        'number_of_lot_groups',
        'lot_groups',
        'down_payment',
        'balance_payable',
        'blocks',
        'description'
    ]
    const hasEmptyField = Object.keys(useProperty.property).some(key => {
        return !excludedKey.includes(key) && !useProperty.property[key];
    });

    if (hasEmptyField) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please fill out all fields",
        });
        return false;
    }
        return true;
}

const checkIfLotGroupIsSet = () => {


    if (!useProperty.property.blocks || !useProperty.property.description) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please fill out all fields",
        });
        return false
    }else if (!useProperty.property.number_of_lot || !useProperty.property.number_of_lot_groups) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please fill out all fields",
        });
        return false
    }

    const hasAnyUnset = useProperty.property.lot_groups.some(lot_group => !lot_group.is_set);

    if (hasAnyUnset) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please fill out all fields",
        });

    }
    return !hasAnyUnset
}

const checkkIfhasUnset = () => {
    const hasAnyUnset = useProperty.property.lots.some(lot => !lot.is_set)
    if (hasAnyUnset) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "There is a lot that hasn't been assigned to a lot group",
        });
        return false
    }
    return true
}

const submit = () => {
    
    const hasAnyUnsetBlock = useProperty.property.lots.some(lot => lot.block == 0)
     if (hasAnyUnsetBlock) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "There is a lot that hasn't been assigned to a block",
        });
        return false
    }
    emits('submit')
}

const handleFileUpdate = (file) => {
  useProperty.property.file = file
}

</script>

<template>
    <form-wizard step-size="xs" @on-complete="submit">
        <tab-content title="Property Layout image upload" :before-change="checkIfFileUploaded" @update:file="handleFileUpdate">
            <FileUpload :file="useProperty.property.file"/>
        </tab-content>
        <tab-content title="Property Address" :before-change="checkIfFieldsAreFilled">
            <Form />
        </tab-content>
        <tab-content title="Lot Details" :before-change="checkIfLotGroupIsSet">
            <Lot />
        </tab-content>
        <tab-content title="Lot Information" :before-change="checkkIfhasUnset">
           <Map />
        </tab-content>
        <tab-content title="Block Information">
           <Block />
        </tab-content>
    </form-wizard>
</template>