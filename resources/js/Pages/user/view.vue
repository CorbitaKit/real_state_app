<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import FileUpload from '../components/fileupload.vue'


import axios from 'axios'
const props = defineProps({
    userInfo: Object
})


const tab = ref('option-1')
const form = useForm(props.userInfo)
const visible = ref(false)

const profile_form = useForm({
    file: {},
    user_id: ''
})


const uploadPic = (id) => {
    profile_form.user_id = props.userInfo.id
    visible.value = true

    profile_form.post('/upload-profile-picture', {
        onSuccess: () => {
            Swal.fire({
                title: "Success",
                text: "Profile picture uploaded successfully!",
                icon: "success"
            })
            visible.value = false
            window.location.reload()
        }
    })

}

const updatePersonalInfo = () => {
    axios.patch('/update-personal-info/' + props.userInfo.id, form.personal_info)
    .then(request => {
        Swal.fire({
            title: "Success!",
            text: "Profile Updated Successfully!",
            icon: "success"
        });
        window.location.reload()
    })
}
defineOptions({layout: Layout})
</script>

<template>
    <Dialog v-model:visible="visible" modal header="Upload Profile Picture" :style="{ width: '50rem' }">
        <FileUpload :file="profile_form.file" />
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="uploadPic"></Button>
        </div>
    </Dialog>
   <div class="row">
      <div class="col-md-12 mb-2">
          <!-- begin page title -->
          <div class="d-block d-sm-flex flex-nowrap align-items-center">
              <div class="page-title mb-2 mb-sm-0">
                  <h1>Account Settings</h1>
              </div>
              <div class="ml-auto d-flex align-items-center">
                  <nav>
                      <ol class="breadcrumb p-0 m-b-0">
                          <li class="breadcrumb-item">
                              <a href="index.html"><i class="ti ti-home"></i></a>
                          </li>
                          <li class="breadcrumb-item">
                              Pages
                          </li>
                          <li class="breadcrumb-item active text-primary" aria-current="page">Profile</li>
                      </ol>
                  </nav>
              </div>
          </div>
          <!-- end page title -->
      </div>
  </div>
  <div class="account-contant">
    <div class="card card-statistics">
        <div class="card-body py-0">
            <div class="row align-items-center">
                <div class="col-lg-4 col-xl-2 col-xxl-3">
                    <div class="page-account-profil pl-0 py-2  py-lg-3">
                        <div class="profile-img rounded-circle">
                            <div class="d-flex">
                                <div class="avatar avatar-xl">
                                    <img src="/assets/img/avatar/01.jpg" class="img-fluid avatar-img rounded-circle" alt="users-avatar" v-if="!userInfo.profile_picture">
                                    <img :src="'/storage/app/public/'+userInfo.profile_picture.url" class="img-fluid avatar-img rounded-circle" alt="users-avatar" v-else>

                                </div>
                                <div class="profile ml-2">
                                    <h5 class="mb-0">{{ userInfo.personal_info.first_name }} {{ userInfo.personal_info.last_name }}</h5>
                                    <p>{{ userInfo.role.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-6 col-xxl-6">
                    <div class="py-2 py-lg-3 profile-counter">

                    </div>
                </div>
                <div class="col-xl-4 col-xxl-3">
                    <div class="profile-btn text-center d-flex py-2  py-lg-3 justify-content-xl-end">
                        <div><button class="btn btn-light text-primary mr-2" @click="visible = true">Upload New Avatar</button></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-4 col-12 border-top border-lg-right">
                    <div class="page-account-form">
                        <div class="form-titel border-bottom p-1">
                            <h5 class="mb-0 py-2">Edit Your Personal Information</h5>
                        </div>
                        <div class="p-2">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name1">First Name</label>
                                        <input type="text" v-model="form.personal_info.first_name" class="form-control" id="name1" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="title1">Last Name</label>
                                        <input type="text" v-model="form.personal_info.last_name" class="form-control" id="title1">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="phone1">Phone Number</label>
                                        <input type="text" v-model="form.personal_info.phone_number" class="form-control" id="phone1" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email1">Email</label>
                                        <input type="email" v-model="form.email" class="form-control" id="email1" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="add1">Address</label>
                                    <input type="text" v-model="form.personal_info.address" class="form-control" id="add1" >
                                </div>
                                <button type="button" @click="updatePersonalInfo" class="btn btn-primary">Save and Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-12 border-top border-lg-right">
                    <div class="page-account-form">
                        <div class="form-titel border-bottom p-1">
                            <h5 class="mb-0 py-2">Edit Your Personal Address</h5>
                        </div>
                        <div class="p-2">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name1">Phase</label>
                                        <input type="text" v-model="form.address.complete_address" class="form-control" id="name1">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="title1">Purok</label>
                                        <input type="text" v-model="form.address.purok" class="form-control" id="title1">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="phone1">Barangay</label>
                                        <input type="text" v-model="form.address.barangay" class="form-control" id="phone1">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email1">City</label>
                                        <input type="email" v-model="form.address.city" class="form-control" id="email1" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="add1">Province</label>
                                    <input type="text" v-model="form.address.province" class="form-control" id="add1" >
                                </div>
                                <button type="submit" class="btn btn-primary">Save and Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-12 border-top border-lg-right">
                    <div class="page-account-form">
                        <div class="form-titel border-bottom p-1">
                            <h5 class="mb-0 py-2">Edit Your Account Information</h5>
                        </div>
                        <div class="p-2">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name1">Email</label>
                                        <input type="text" v-model="form.email" class="form-control" id="name1" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="title1">Password</label>
                                        <input type="password" v-model="form.password" class="form-control" id="title1" >
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="title1">Confirm Password</label>
                                        <input type="password" v-model="form.password_confirmation" class="form-control" id="title1" >
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary">Save and Update</button>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
  </div>
</template>
