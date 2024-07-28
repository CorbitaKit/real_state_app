<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { ref } from 'vue'

const props = defineProps({
    userInfo: Object
})

const tab = ref('option-1')
defineOptions({layout: Layout})
</script>

<template>
    <Header  :displayBtn="false" :title="'Profile Details'" />
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <div class="flex content-between gap-4">
            <div class="card bg-white">
                <v-img
                :width="300"
                aspect-ratio="16/9"
                cover
                src="https://cdn.vuetifyjs.com/images/parallax/material.jpg"
                ></v-img>
                <v-table>
              
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>
                                {{ userInfo.personal_info.first_name }}
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>
                                {{ userInfo.email }}
                            </td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td>
                                {{ userInfo.personal_info.gender }} 
                            </td>
                        </tr>
                        <tr>
                            <td>Birth Day</td>
                            <td>
                                {{ userInfo.personal_info.birth_day }} 
                            </td>
                        </tr>
                        <tr>
                            <td>Phone Number</td>
                            <td>
                                {{ userInfo.personal_info.phone_number }} 
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </div>
            <div class="card bg-white w-full">
                <v-card>
                    <v-tabs
      v-model="tab"
      bg-color="primary"
    >
      <v-tab value="one">Address</v-tab>
      <v-tab value="two">Work Details</v-tab>
      <!-- <v-tab value="three">Proof Of Income</v-tab> -->
    </v-tabs>

    <v-card-text>
      <v-tabs-window v-model="tab">
        <v-tabs-window-item value="one">
            <v-table>
              <tbody>
                  <tr>
                      <td>Province</td>
                      <td>
                        {{ userInfo.address.province }}
                      </td>
                  </tr>
                  <tr>
                      <td>City</td>
                      <td>
                        {{ userInfo.address.city }}

                      </td>
                  </tr>
                  <tr>
                      <td>Barangay</td>
                      <td>
                        {{ userInfo.address.barangay }}

                      </td>
                  </tr>
                  <tr>
                      <td>Purok</td>
                      <td>
                        {{ userInfo.address.purok }}

                      </td>
                  </tr>
              </tbody>
          </v-table>
        </v-tabs-window-item>
       
        <v-tabs-window-item value="two">
            <v-table>
              <tbody>
                  <tr>
                      <td>Company</td>
                      <td>
                        {{ userInfo.work_details.company_name }}
                      </td>
                  </tr>
                  <tr>
                      <td>Company Address</td>
                      <td>
                        {{ userInfo.work_details.company_address }}
                      </td>
                  </tr>
                  <tr>
                      <td>Company Email</td>
                      <td>
                        {{ userInfo.work_details.company_address }}
                      </td>
                  </tr>
                  <tr>
                      <td>Company Number</td>
                      <td>
                        {{ userInfo.work_details.company_number }}
                      </td>
                  </tr>
                  <tr>
                      <td>Length of stay</td>
                      <td>
                        {{ userInfo.work_details.length_of_stay }}
                      </td>
                  </tr>
                  <tr>
                      <td>Employment Status</td>
                      <td>
                        {{ userInfo.work_details.status }}


                      </td>
                  </tr>
                  <tr>
                      <td>Gross Monthly Income</td>
                      <td>
                        {{ userInfo.work_details.gross_monthly_income }}
                      </td>
                  </tr>
              </tbody>
          </v-table>
        </v-tabs-window-item>

        <!-- <v-tabs-window-item value="three">
          Three
        </v-tabs-window-item> -->
      </v-tabs-window>
    </v-card-text>
                    
                </v-card>
            </div>
        </div>
    </div>
</template>