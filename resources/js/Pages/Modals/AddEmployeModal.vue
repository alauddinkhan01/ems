<template>
        <vue-final-modal
            name="addEmployeModal"
            v-slot="{ params, close }"
            v-bind="$attrs"
            classes="flex justify-center items-center"
            content-class="relative flex flex-col max-h-full mx-4 p-4 border dark:border-gray-800 rounded bg-white dark:bg-gray-900"
        >
            <span class="mr-8 text-2xl font-bold">
                <slot name="title">New User</slot>
            </span>
            <div class="flex-grow overflow-y-auto">
                <slot :params="params"></slot>
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <div>
                            <label class="text-gray-700" for="name">Name</label>
                            <input
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                type="text"
                                id="name"
                                v-model="formObj.name"
                            />
                            <span v-if="error.name" class="text-red-600">Please enter name</span>
                        </div>

                        <div>
                            <label class="text-gray-700" for="email"
                            >Email</label
                            >
                            <input
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                type="email"
                                id="email"
                                v-model="formObj.email"
                            />
                            <span v-if="error.email" class="text-red-600">Please enter email</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="password"
                            >Password</label
                            >
                            <input
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                type="password"
                                id="password"
                                v-model="formObj.password"
                            />
                            <span v-if="error.password" class="text-red-600">Please enter password</span>
                            <span v-if="error.passwordConfirmation" class="text-red-600">Password dosen't match</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="confirmpassword"
                            >Confirm Password</label
                            >
                            <input
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                type="password"
                                id="confirmpassword"
                                v-model="formObj.confirmPassword"
                            />
                            <span v-if="error.confirmPassword" class="text-red-600">Please enter confirm password</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="mobile"
                            >Mobile</label
                            >
                            <input
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                type="number"
                                id="mobile"
                                v-model="formObj.mobile"
                            />
                            <span v-if="error.mobile" class="text-red-600">Please enter mobile</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="role"
                            >Role</label
                            >
                            <select
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                v-model="formObj.role"
                            >
                                <option value="" selected disabled>Select Role</option>
                                <option v-for="(role , indes) in roles" :key="role.id" :value=" role.name ">{{role.name}}</option>
                               
                            </select>
                            <span v-if="error.role" class="text-red-600">Please enter role</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="department"
                            >Department</label
                            >
                            <select
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                v-model="formObj.department_id"
                            >
                                <option value="" selected disabled>Select department</option>
                                <option v-for="(department , indes) in departments" :key="department.id" :value=" department.id ">{{department.name}}</option>
                               
                            </select>
                            <span v-if="error.department_id" class="text-red-600">Please select department</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="status"
                            >Status</label
                            >
                            <select
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                v-model="formObj.status"
                            >
                                <option value="" selected disabled>Select Status</option>
                                <option value="Active">Active</option>
                                <option value="InActive">InActive</option>
                               
                            </select>
                            <span v-if="error.status" class="text-red-600">Please select status</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="passwordConfirmation"
                            >Image</label
                            >
                            <input
                                class=" mt-2 block w-full text-sm text-indigo-500 
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-600 file:text-white
                                    hover:file:bg-indigo-500
                                " 
                                type="file"
                                @input="formObj.image = $event.target.files[0]"
                            />
                            <span v-if="error.image" class="text-red-600">Please select image</span>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button
                            class="px-4 py-2 text-gray-200 bg-indigo-800 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-700"
                            type="submit"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
            <button class="absolute top-0 right-0 mt-2 mr-2" @click="close">
            <mdi-close></mdi-close>
            </button>
        </vue-final-modal>
  </template>

  <script>
    import { $vfm } from "vue-final-modal";

    export default{
        props:['roles','departments'],
        data(){
            return{
                title:"Add Employe",
                formObj:{
                    name:'',
                    email:'',
                    password:'',
                    confirmPassword:'',
                    mobile:'',
                    role:'',
                    department_id:'',
                    status:'',
                    image:[]
                },
                error:{
                    name:false,
                    email:false,
                    password:false,
                    confirmPassword:false,
                    mobile:false,
                    role:false,
                    department_id:false,
                    status:false,
                    image:false,
                    passwordConfirmation:false
                }
            }
        },

        methods:{
            submit(){
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                this.formObj.name == '' ? this.error.name = true : this.error.name = false
                this.formObj.email == '' ? this.error.email = true : this.error.email = false
                this.formObj.password == '' ? this.error.password = true : this.error.password = false
                this.formObj.confirmPassword == '' ? this.error.confirmPassword = true : this.error.confirmPassword = false
                this.formObj.mobile == '' ? this.error.mobile = true : this.error.mobile = false
                this.formObj.role == '' ? this.error.role = true : this.error.role = false
                this.formObj.department_id == '' ? this.error.department_id = true : this.error.department_id = false
                this.formObj.status == '' ? this.error.status = true : this.error.status = false
                this.formObj.image == '' ? this.error.image = true : this.error.image = false
                if (
                    this.error.name || 
                    this.error.email ||
                    this.error.password ||
                    this.error.confirmPassword ||
                    this.error.mobile ||
                    this.error.role ||
                    this.error.department_id ||
                    this.error.status ||
                    this.image
                ){
                    return false;
                }
                if (this.formObj.password != this.formObj.confirmPassword) {
                    this.error.passwordConfirmation = true;
                    return false
                }
                this.error.passwordConfirmation = false;
                this.axios.post(route('store.employe'),this.formObj,config).then((response)=>{
                    $vfm.hide('addEmployeModal');
                    this.$toast.success(
                        response.data.success,
                        {
                            position:"top"
                        }
                    )
                    this.$inertia.visit(route('employes'));
                }).catch((errors)=>{
                    this.$toast.error(
                        errors.response.data.message,
                        {
                            position:"top"
                        }
                    )
                })
            }
        }
    }
  </script>