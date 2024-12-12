<script setup>
import { ref, watch } from "vue";
import Chart from "primevue/chart";
import { useVueToPrint } from "vue-to-print";


const props = defineProps({
  daily: Object,
  weekly: Object,
  monthly: Object
})
// Period options
const periods = ["Daily", "Weekly", "Monthly"];
const selectedPeriod = ref("Daily");
const sales = props.daily



const salesRef = ref()
const sales_view = ref(false)
const { handlePrint } = useVueToPrint({
  content: salesRef,
  documentTitle: "AwesomeFileName",
});

const changePeriod = (period) => {
  selectedPeriod.value = period;

  if (period === 'Daily') {
    sales.value = props.daily
  } else if (period === 'Weeklt') {
    sales.value = props.weekly
  } else {
    sales.value = props.monthly
  }
};
</script>

<template>
<Dialog v-model:visible="sales_view" modal :style="{ width: '60rem' }">
    <div ref="salesRef">
        <v-table class="responsive">
            <tbody>
                <tr>
                    <td></td>
                    <td>
                        <img src="/header.png" style="height: 100px;"/>
                    </td>
                    <td>
                        <h1 style="margin-left:100px;">JEFF ALDEBAL REALTY SERVICE</h1>
                    Door 3, CEASAR APARMENT, Sto. Niño, Carmen, Davao del Norte
                    </td>
                </tr>
            </tbody>
        </v-table>
        <h1 class="text-center">{{ selectedPeriod }} Reports</h1>

        <v-table>
            <thead>
                    <tr>
                        <th scope="col">
                            DATE
                        </th>
                        <th scope="col">
                            A.R. / O.R. NO
                        </th>
                        <th scope="col">
                            NAME
                        </th>

                        <th scope="col">
                            AMOUNT
                        </th>
                        <th scope="col">
                            LOCATION
                        </th>
                        <th scope="col">
                            BLOCK
                        </th>

                        <th scope="col">
                            LOT
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="sale in sales">
                        <td>{{ sale.date_of_payment }}</td>
                        <td>{{ sale.invoice_number }}</td>
                        <td>{{ sale.user.personal_info.first_name }}
                        {{ sale.user.personal_info.last_name }} </td>
                        <td>{{ sale.amount }}</td>
                        <td>
                            Phase {{ sale.lots[0].property.phase }},
                            Block {{ sale.lots[0].block }},
                            {{ sale.lots[0].name }},
                            Barangay {{ sale.lots[0].property.barangay }},
                            {{ sale.lots[0].property.city }}
                            {{ sale.lots[0].property.province }}

                        </td>
                        <td>{{ sale.lots[0].block }}</td>
                        <td>{{ sale.lots[0].name }}</td>
                    </tr>
                </tbody>
        </v-table>
    </div>
    <button class="btn btn-block btn-info" @click="handlePrint">Print</button>
</Dialog>
  <div class="col-lg-9 col-xl-9 col-xxl-9 mb-3">
    <div class="card card-statistics h-100 mb-0 border-0 shadow-none">
      <div class="card-header d-flex justify-content-between">
        <div class="card-heading">
          <h5 class="card-title">Income Analysis</h5>
        </div>
        <div class="dropdown">
          <a
            class="p-2"
            href="#!"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="fe fe-more-horizontal"></i>
          </a>
          <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
            <h6 class="mb-1">Action</h6>
            <a
              class="dropdown-item"
              href="#!"
              v-for="period in periods"
              :key="period"
              @click="changePeriod(period)"
            >
              <i class="fa-fw far fa-file-alt pr-2"></i>{{ period }} View
            </a>
            <a href="#" @click="sales_view = true"><i class="fa-fw far fa-file-alt pr-2"></i>Print</a>
          </div>
        </div>
      </div>
      <div class="card-body pb-0">
        <div class="card-body table-responsive">
            <table class="table mb-0 table-border-3">
                <thead>
                    <tr>
                        <th scope="col">
                            DATE
                        </th>
                        <th scope="col">
                            A.R. / O.R. NO
                        </th>
                        <th scope="col">
                            NAME
                        </th>

                        <th scope="col">
                            AMOUNT
                        </th>
                        <th scope="col">
                            LOCATION
                        </th>
                        <th scope="col">
                            BLOCK
                        </th>

                        <th scope="col">
                            LOT
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="sale in sales">
                        <td>{{ sale.date_of_payment }}</td>
                        <td>{{ sale.invoice_number }}</td>
                        <td>{{ sale.user.personal_info.first_name }}
                        {{ sale.user.personal_info.last_name }} </td>
                        <td>{{ sale.amount }}</td>
                        <td>
                            Phase {{ sale.lots[0].property.phase }},
                            Block {{ sale.lots[0].block }},
                            {{ sale.lots[0].name }},
                            Barangay {{ sale.lots[0].property.barangay }},
                            {{ sale.lots[0].property.city }}
                            {{ sale.lots[0].property.province }}

                        </td>
                        <td>{{ sale.lots[0].block }}</td>
                        <td>{{ sale.lots[0].name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</template>
