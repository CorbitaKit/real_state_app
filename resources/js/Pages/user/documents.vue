<script setup>
import { ref } from 'vue';
import Layout from '../layout/main.vue'
import VueDocumentEditor from 'vue-document-editor'


defineOptions({layout: Layout})
const printDiv = () => {
    const printContent = document.getElementById("printMe").outerHTML;
    const printWindow = window.open("", "_blank");
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Report</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                        text-align: left;
                    }
                </style>
            </head>
            <body>${printContent}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
};
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
        <button @click="printDiv">Print</button>
        <div class="col-xl-12 col-sm-12" style="color:black;">
            <div class="card card-statistics">
                <div class="card-body p-3" id="printMe">
                    <h1>
    <p class="MsoNormal" align="center" style="text-align:center">
        <b><span style="font-size:14.0pt;line-height:107%">CONTRACT TO SELL</span></b>
    </p>
    <p class="MsoNormal" align="center" style="text-align:center">
        <b><span style="font-size:14.0pt;line-height:107%"><br></span></b>
    </p>

    <p class="MsoNormal">
        <span style="font-size:14.0pt;line-height:107%">KNOW ALL MEN BY THESE PRESENTS:</span>
    </p>

    <p class="MsoNormal">
        <span style="font-size:14.0pt;line-height:107%">This contract to sell is made, executed, and entered into by and between:</span>
    </p>

    <p class="MsoNormal">
        <span style="font-size:14.0pt;line-height:107%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>SELLER_NAME</b>, of legal age, Filipino, single, and a resident of Panabo City, Davao del Norte, hereinafter referred to as the <b>SELLER</b>.</span>
    </p>

    <p class="MsoNormal" align="center" style="text-align:center">
        <b><span style="font-size:14.0pt;line-height:107%">-AND-</span></b>
    </p>

    <p class="MsoNormal">
        <span style="font-size:14.0pt;line-height:107%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>BUYER_NAME</b>, of legal age, Filipino, <b>married</b>, and a resident of <b>P-14, Caimito, Datu Abdul Didia, Panabo City, Davao del Norte</b>, hereinafter referred to as the <b>BUYER</b>.</span>
    </p>

    <p class="MsoNormal" align="center" style="text-align:center">
        <b><span style="font-size:14.0pt;line-height:107%">-WITNESSETH-</span></b>
    </p>

    <p class="MsoNormal">
        <b><span style="font-size:14.0pt;line-height:107%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WHEREAS</span></b><span style="font-size:14.0pt;line-height:107%">, the <b>SELLER</b> is authorized to enter into a contract to sell involving a parcel of land located at <b>ADDRESS SA PROPERTY</b>, and covered by <b>Transfer Certificate of Title No. 256092</b>, containing a total area of <b>Fifty thousand square meters</b>, more or less, issued by the Registry of Deeds of the province of Davao del Norte.</span>
    </p>

    <p class="MsoNormal">
        <span style="font-size:14.0pt;line-height:107%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Whereas, the <b>BUYER</b> has offered to buy a portion of said parcel of land containing an area of <b>200 square meters</b>, more or less, identified as <b>Phase 1, Block 14, Lot 5 &amp; 7</b>, of the aforesaid property, and the <b>SELLER</b> has agreed to sell the above-mentioned property under the terms and conditions herein below set forth:</span>
    </p>

    <p class="MsoNormal">
        <span style="font-size:14.0pt;line-height:107%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NOW THEREFORE, for and in consideration of the total sum of <b>Two Hundred Fifty-Nine Thousand Two Hundred Pesos (P259,200.00)</b> Philippine currency, and of the covenants herein after set forth, the <b>SELLER</b> agrees to sell, and the <b>BUYER</b> agrees to buy the aforesaid parcel of land, being a portion of the parcel of land covered by <b>Transfer Certificate of Title No. 256092</b>, subject to the following terms:</span>
    </p>

    <p class="MsoNormal">
        <span style="font-size:14.0pt;line-height:107%">----------------------------------------&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ---------------------------------------<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SELLER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BUYER</span>
    </p>
</h1>

                </div>
            </div>
        </div>

      
    </div>
</template>