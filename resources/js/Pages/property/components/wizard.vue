<script setup>
import FileUpload  from '../../components/fileupload.vue';
import Form from './form.vue'
import Lot from './lot.vue'
import Map from './map.vue'
import { usePropertyStore } from '../store/store'
import { useToaster } from '../../composables/toast'


const { show } = useToaster()
const useProperty = usePropertyStore()
const emits = defineEmits(['submit'])


const checkIfFileUploaded = () => {
    if (useProperty.property.file.value === undefined) {
        show(
            'error', 
            'Opps!', 
            'Please upload a file first before proceeding'
        )
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
        'balance_payable'
    ]
    const hasEmptyField = Object.keys(useProperty.property).some(key => {
        return !excludedKey.includes(key) && !useProperty.property[key];
    });

    if (hasEmptyField) {
        show('error', 'Opps!', 'Please fill out all the fields');
        return false;
    }
        return true;
}

const checkIfLotGroupIsSet = () => {


    if (!useProperty.property.down_payment || !useProperty.property.balance_payable) {
        show(
            'error', 
            'Opps!', 
            'Please fill out all down payment and balance payable fields'
        )
        return false
    }else if (!useProperty.property.number_of_lot || !useProperty.property.number_of_lot_groups) {
        show('error', 'Opps', 'Please fill out the number of lots and number of lot groups field')
        return false
    }

    const hasAnyUnset = useProperty.property.lot_groups.some(lot_group => !lot_group.is_set);

    if (hasAnyUnset) {
        show('error', 'Opps!', 'Please fill out all the fields in lot group fields')

    }
    return !hasAnyUnset
}

const submit = () => {
    const hasAnyUnset = useProperty.property.lots.some(lot => !lot.is_set)
    if (hasAnyUnset) {
        show('error', 'Opps!', 'There are lots that is not assign to a lot group')
        return false
    }
    emits('submit')
}

const handleFileUpdate = (file) => {
  useProperty.property.file = file
}

</script>

<template>
    <Toast />
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
        <tab-content title="Lot Information">
           <Map />
        </tab-content>
    </form-wizard>
</template>