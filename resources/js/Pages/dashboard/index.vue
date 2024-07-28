<script setup>
import Layout from '../layout/main.vue'
import Widget from './widget.vue'
import QuarterlySales from './components/quarterly-sales.vue';

defineOptions({ layout: Layout })
</script>


<template>
    <Widget />
    <div class="grid grid-cols-2 gap-2 ">
        <div class="card bg-white items-center">
            <div class="p-3 text-2xl semi-bold tabular-nums">
                <h3>Clients</h3>
            </div>
            <div class="">
                <DataTable :value="products" >
                    <Column field="code" header="Code"></Column>
                    <Column field="name" header="Name"></Column>
                    <Column field="category" header="Category"></Column>
                    <Column field="quantity" header="Quantity"></Column>
                </DataTable>
            </div>
        </div>
        <div class="card bg-white items-center">
            <div class="p-3 text-2xl semi-bold tabular-nums">
                <h3>Applications</h3>
            </div>
            <div class="">
                <DataTable :value="products" >
                    <Column field="code" header="Code"></Column>
                    <Column field="name" header="Name"></Column>
                    <Column field="category" header="Category"></Column>
                    <Column field="quantity" header="Quantity"></Column>
                </DataTable>
            </div>
        </div>
        
    </div>
    <div class="grid grid-cols-1 mt-4">
        <div class="card bg-white items-center py-4 px-4">
            <QuarterlySales />
        </div>
        <!-- <div class="card bg-white items-center py-4 px-4">
            <YearlySales />
        </div> -->
    </div>
</template>


