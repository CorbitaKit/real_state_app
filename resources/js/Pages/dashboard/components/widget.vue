<script setup>

const props = defineProps({
    properties: Number,
    clients: Number,
    applications: Number,
    sales: Number
})

</script>

<template>
    <div class="col-lg-5 col-xl-4 col-xxl-3 mb-3">
        <div class="card card-statistics border-0 shadow-none">
            <div class="card-body">
                <div class="d-flex align-items-center w-100">
                    <div class="mr-3">
                        <h3 class="mb-0">{{ properties }}</h3>
                        <p>Properties <i class="fe fe-activity text-primary font-md ml-2"></i></p>
                    </div>
                    <div class="avatar-type avatar-type-lg bg-primary-inverse ml-auto">
                        <i class="ti ti-map text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-xl-4 col-xxl-3 mb-3">
        <div class="card card-statistics border-0 shadow-none">
            <div class="card-body">
                <div class="d-flex align-items-center w-100">
                    <div class="mr-3">
                        <h3 class="mb-0">{{ clients }}</h3>
                        <p>Clients <i class="fe fe-trending-up text-orange font-md ml-2"></i></p>
                    </div>
                    <div class="avatar-type avatar-type-lg bg-orange-inverse ml-auto">
                        <i class="ti ti-user text-orange"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-xl-4 col-xxl-3 mb-3">
        <div class="card card-statistics border-0 shadow-none">
            <div class="card-body">
                <div class="d-flex align-items-center w-100">
                    <div class="mr-3">
                        <h3 class="mb-0">{{ applications }}</h3>
                        <p>Applications <i class="fe fe-trending-up text-cyan font-md ml-2"></i></p>
                    </div>
                    <div class="avatar-type avatar-type-lg bg-cyan-inverse ml-auto">
                        <i class="ti ti-notepad text-cyan"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-xl-4 col-xxl-3 mb-3">
        <div class="card card-statistics mb-0 border-0 shadow-none">
            <div class="card-body">
                <div class="d-flex align-items-center w-100">
                    <div class="mr-3">
                        <h3 class="mb-0">₱{{ sales }}</h3>
                        <p>Total Earnings <i class="fe fe-corner-left-up text-pink font-md ml-2"></i></p>
                    </div>
                    <div class="avatar-type avatar-type-lg bg-pink-inverse ml-auto">
                        <i class="ti ti-money text-pink"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
