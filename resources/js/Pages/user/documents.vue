<script setup>
import Layout from '../layout/main.vue'

defineOptions({layout: Layout})

</script>

<template>
     <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Documents</h1>
                </div>
                
                <div class="ml-auto d-flex align-items-center">
                    
                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>
                            
                            <li class="breadcrumb-item active text-primary" aria-current="page">Documents</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-statistics">
                <div class="card-body p-3">
                    <div class="p-0 d-flex">
                        <div class="mb-2">
                            <img src="assets/img/file-icon/doc.png" alt="png-img">
                        </div>
                        <div class="ml-2">
                            <h5 class="mb-0">contract.docx</h5>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="btn bg-primary-inverse text-primary btn-sm mt-2 btn-block">Download</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-statistics">
                <div class="card-body p-3">
                    <div class="p-0 d-flex">
                        <div class="mb-2">
                            <img src="assets/img/file-icon/ai.png" alt="png-img">
                        </div>
                        <div class="ml-2">
                            <h5 class="mb-0">arioxa_demo.AVI</h5>
                            <p class="mb-2">28.8 kb</p>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="btn bg-primary-inverse text-primary btn-sm mt-2 btn-block">Download</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-statistics">
                <div class="card-body p-3">
                    <div class="p-0 d-flex">
                        <div class="mb-2">
                            <img src="assets/img/file-icon/css.png" alt="png-img">
                        </div>
                        <div class="ml-2">
                            <h5 class="mb-0">arioxa_demo.css</h5>
                            <p class="mb-2">28.8 kb</p>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="btn bg-primary-inverse text-primary btn-sm mt-2 btn-block">Download</a>
                </div>
            </div>
        </div>
    </div>
</template>