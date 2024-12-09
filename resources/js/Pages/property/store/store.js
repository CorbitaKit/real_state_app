import { defineStore } from "pinia";

const getDefaultPropertyState = () => ({
    province: '',
    city: '',
    barangay: '',
    phase: '',
    purok: '',
    file: {},
    number_of_lot: 0,
    lots: [],
    number_of_lot_groups: 0,
    lot_groups: [],
    balance_payable: 36,
    blocks: 0,
    description: '',
});

export const usePropertyStore  = defineStore('propertyStore', {
    state: () => {
        return {
            property: getDefaultPropertyState(),
            properties: [],
        }
    },
    actions: {
        resetProperty() {
            this.property = getDefaultPropertyState();
        }
    }
})